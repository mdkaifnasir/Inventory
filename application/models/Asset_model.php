<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Asset_model extends CI_Model
{

    public function get_all()
    {
        $this->db->select('assets.*, categories.name as category_name, colleges.name as college_name, colleges.code as college_code');
        $this->db->from('assets');
        $this->db->join('categories', 'categories.id = assets.category_id', 'left');
        $this->db->join('colleges', 'colleges.id = assets.college_id', 'left');
        $this->db->where('assets.is_deleted', 0);
        $this->db->order_by('assets.created_at', 'DESC');
        return $this->db->get()->result();
    }

    public function get_filtered_assets($search = null, $category_id = null, $condition = null, $status = null, $college_id = null, $sub_category = null)
    {
        $this->db->select('assets.*, categories.name as category_name, colleges.name as college_name, colleges.code as college_code');
        $this->db->from('assets');
        $this->db->join('categories', 'categories.id = assets.category_id', 'left');
        $this->db->join('colleges', 'colleges.id = assets.college_id', 'left');
        $this->db->where('is_deleted', 0);

        if ($search) {
            $this->db->group_start();
            $this->db->like('assets.name', $search);
            $this->db->or_like('assets.asset_tag', $search);
            $this->db->or_like('assets.serial_number', $search);
            $this->db->or_like('assets.brand_model', $search);
            $this->db->group_end();
        }

        if ($category_id) {
            $this->db->where('assets.category_id', $category_id);
        }

        if ($sub_category) {
            $this->db->where('assets.sub_category', $sub_category);
        }

        if ($condition) {
            $this->db->where('assets.asset_condition', $condition);
        }

        if ($status) {
            $this->db->where('assets.status', $status);
        }

        if ($college_id) {
            $this->db->where('assets.college_id', $college_id);
        }

        $this->db->order_by('assets.created_at', 'DESC');
        return $this->db->get()->result();
    }

    public function assign_to_college($asset_id, $college_id)
    {
        $this->db->where('id', $asset_id);
        $status = $college_id ? 'Deployed' : 'In Stock';
        $db_college_id = ($college_id == 0 || empty($college_id)) ? NULL : $college_id;
        return $this->db->update('assets', ['college_id' => $db_college_id, 'status' => $status]);
    }

    public function split_and_allocate($asset_id, $college_id, $quantity_to_allocate, $return_date = null)
    {
        // Get original asset
        $original = $this->db->get_where('assets', ['id' => $asset_id])->row_array();
        if (!$original)
            return false;

        $current_qty = (int) $original['quantity'];

        $this->db->trans_start();

        if ($quantity_to_allocate >= $current_qty) {
            // Full allocation
            $this->db->where('id', $asset_id);
            $status = $college_id ? ($return_date ? 'On Loan' : 'Deployed') : 'In Stock';
            $db_college_id = ($college_id == 0 || empty($college_id)) ? NULL : $college_id;

            $this->db->update('assets', [
                'college_id' => $db_college_id,
                'status' => $status,
                'return_date' => $return_date
            ]);
        } else {
            // Partial allocation - Split record
            // 1. Update original quantity
            $this->db->where('id', $asset_id);
            $this->db->update('assets', ['quantity' => $current_qty - $quantity_to_allocate]);

            // 2. Insert new record for allocated portion
            $new_asset = $original;
            unset($new_asset['id']);
            unset($new_asset['created_at']);
            unset($new_asset['updated_at']);

            $new_asset['quantity'] = $quantity_to_allocate;
            $new_asset['college_id'] = ($college_id == 0 || empty($college_id)) ? NULL : $college_id;
            $new_asset['status'] = $new_asset['college_id'] ? ($return_date ? 'On Loan' : 'Deployed') : 'In Stock';
            $new_asset['return_date'] = $return_date;

            // Better unique tag: Original-Suffixed (using more characters of uniqid)
            $suffix = substr(strtoupper(uniqid()), -4);
            $new_asset['asset_tag'] = $original['asset_tag'] . "-" . $suffix;

            // Link to parent for batch tracking
            $new_asset['parent_id'] = $original['parent_id'] ? $original['parent_id'] : $original['id'];

            $this->db->insert('assets', $new_asset);
        }

        $this->db->trans_complete();
        return $this->db->trans_status();
    }

    public function get_batch_distribution($asset_id)
    {
        // First find the root parent ID
        $asset = $this->db->get_where('assets', ['id' => $asset_id])->row();
        if (!$asset)
            return [];

        $root_id = $asset->parent_id ? $asset->parent_id : $asset->id;

        // Fetch all assets in this batch (root + all children)
        $this->db->select('assets.*, colleges.name as college_name, colleges.code as college_code');
        $this->db->from('assets');
        $this->db->join('colleges', 'colleges.id = assets.college_id', 'left');
        $this->db->group_start();
        $this->db->where('assets.id', $root_id);
        $this->db->or_where('assets.parent_id', $root_id);
        $this->db->group_end();
        $this->db->order_by('assets.created_at', 'ASC');

        return $this->db->get()->result();
    }

    public function insert($data)
    {
        if ($this->db->insert('assets', $data)) {
            return $this->db->insert_id();
        }
        return false;
    }
    public function get_distribution_summary($search = null, $category_id = null, $condition = null, $status = null, $college_id = null)
    {
        $this->db->select('colleges.id as college_id, colleges.name, colleges.code, COUNT(assets.id) as record_count, SUM(assets.quantity) as total_units');
        $this->db->from('colleges');
        $this->db->join('assets', 'assets.college_id = colleges.id AND assets.is_deleted = 0', 'left');

        if ($search) {
            $this->db->group_start();
            $this->db->like('assets.name', $search);
            $this->db->or_like('assets.asset_tag', $search);
            $this->db->or_like('assets.serial_number', $search);
            $this->db->or_like('assets.brand_model', $search);
            $this->db->group_end();
        }

        if ($category_id) {
            $this->db->where('assets.category_id', $category_id);
        }

        if ($condition) {
            $this->db->where('assets.asset_condition', $condition);
        }

        if ($status) {
            $this->db->where('assets.status', $status);
        }

        if ($college_id) {
            $this->db->where('colleges.id', $college_id);
        }

        $this->db->group_by('colleges.id');
        $this->db->order_by('total_units', 'DESC');
        return $this->db->get()->result();
    }

    public function auto_consolidate_stock($asset_id = null)
    {
        $target_where = [];

        if ($asset_id) {
            // Fetch asset details separately to avoid Query Builder collision on 'from'
            $asset = $this->db->get_where('assets', ['id' => $asset_id])->row();
            if (!$asset || $asset->status !== 'In Stock')
                return false;

            $target_where = [
                'name' => $asset->name,
                'category_id' => $asset->category_id,
                'brand_model' => $asset->brand_model,
                'asset_condition' => $asset->asset_condition
            ];
        }

        // Now build the grouping query
        $this->db->select('name, category_id, brand_model, asset_condition, serial_number, status');
        $this->db->from('assets');
        $this->db->where('status', 'In Stock');

        if (!empty($target_where)) {
            $this->db->where($target_where);
        }

        $this->db->group_by(['name', 'category_id', 'brand_model', 'asset_condition', 'serial_number']);
        $this->db->having('COUNT(id) > 1');
        $groups = $this->db->get()->result();

        if (empty($groups))
            return true;

        $this->db->trans_start();

        foreach ($groups as $group) {
            // Find all records in this group
            $items = $this->db->get_where('assets', [
                'name' => $group->name,
                'category_id' => $group->category_id,
                'brand_model' => $group->brand_model,
                'asset_condition' => $group->asset_condition,
                'serial_number' => $group->serial_number,
                'status' => 'In Stock'
            ])->result();

            if (count($items) <= 1)
                continue;

            $total_qty = 0;
            $to_delete = [];
            $primary_item = $items[0]; // Keep the first (oldest) one

            foreach ($items as $index => $item) {
                $total_qty += $item->quantity;
                if ($index > 0) {
                    $to_delete[] = $item->id;
                }
            }

            // Update primary
            $this->db->where('id', $primary_item->id);
            $this->db->update('assets', ['quantity' => $total_qty]);

            // Delete fragments
            if (!empty($to_delete)) {
                $this->db->where_in('id', $to_delete);
                $this->db->delete('assets');
            }
        }

        $this->db->trans_complete();
        return $this->db->trans_status();
    }

    public function consolidate_stock($asset_id)
    {
        // 1. Find the root parent
        $asset = $this->db->get_where('assets', ['id' => $asset_id])->row();
        if (!$asset)
            return false;

        $root_id = $asset->parent_id ? $asset->parent_id : $asset->id;

        // 2. Get all 'In Stock' records in this batch
        $this->db->where('status', 'In Stock');
        $this->db->group_start();
        $this->db->where('id', $root_id);
        $this->db->or_where('parent_id', $root_id);
        $this->db->group_end();
        $in_stock_items = $this->db->get('assets')->result();

        if (count($in_stock_items) <= 1)
            return false;

        // 3. Sum quantities
        $total_qty = 0;
        $to_delete = [];
        $root_record_to_update = null;

        foreach ($in_stock_items as $item) {
            $total_qty += $item->quantity;
            if ($item->id == $root_id) {
                $root_record_to_update = $item;
            } else {
                $to_delete[] = $item->id;
            }
        }

        // If root record wasn't 'In Stock', pick the first one as new 'parent' or just update the existing root
        // Best practice: Always push back to the absolute root (id = parent_id)

        $this->db->trans_start();

        // Update root (even if it wasn't in the 'In Stock' list, it will become the holder)
        $this->db->where('id', $root_id);
        $this->db->update('assets', [
            'quantity' => $total_qty,
            'status' => 'In Stock',
            'college_id' => NULL,
            'return_date' => NULL
        ]);

        // Delete others
        if (!empty($to_delete)) {
            $this->db->where_in('id', $to_delete);
            $this->db->delete('assets');
        }

        $this->db->trans_complete();
        return $this->db->trans_status();
    }

    public function delete($id)
    {
        $this->db->where('id', $id);
        return $this->db->update('assets', [
            'is_deleted' => 1,
            'deleted_at' => date('Y-m-d H:i:s')
        ]);
    }

    public function get_trashed()
    {
        $this->db->select('assets.*, categories.name as category_name, colleges.name as college_name');
        $this->db->from('assets');
        $this->db->join('categories', 'categories.id = assets.category_id', 'left');
        $this->db->join('colleges', 'colleges.id = assets.college_id', 'left');
        $this->db->where('assets.is_deleted', 1);
        $this->db->order_by('assets.deleted_at', 'DESC');
        return $this->db->get()->result();
    }

    public function restore($id)
    {
        $this->db->where('id', $id);
        return $this->db->update('assets', [
            'is_deleted' => 0,
            'deleted_at' => NULL
        ]);
    }

    public function bulk_restore($ids)
    {
        if (empty($ids))
            return false;
        $this->db->where_in('id', $ids);
        return $this->db->update('assets', [
            'is_deleted' => 0,
            'deleted_at' => NULL
        ]);
    }

    public function bulk_permanent_delete($ids)
    {
        if (empty($ids))
            return false;
        $this->db->where_in('id', $ids);
        return $this->db->delete('assets');
    }

    public function permanent_delete($id)
    {
        $this->db->where('id', $id);
        return $this->db->delete('assets');
    }

    public function bulk_trash($ids)
    {
        if (empty($ids))
            return false;
        $this->db->where_in('id', $ids);
        return $this->db->update('assets', [
            'is_deleted' => 1,
            'deleted_at' => date('Y-m-d H:i:s')
        ]);
    }

    public function get_stats_summary()
    {
        $stats = [];
        $stats['total_items'] = $this->db->where('is_deleted', 0)->count_all_results('assets');
        $stats['working'] = $this->db->where('is_deleted', 0)->where('asset_condition', 'Working')->count_all_results('assets');
        $stats['non_working'] = $this->db->where('is_deleted', 0)->where_in('asset_condition', ['Faulty', 'Scrap'])->count_all_results('assets');
        $stats['trashed'] = $this->db->where('is_deleted', 1)->count_all_results('assets');

        // Category breakdown
        $this->db->select('categories.name, COUNT(assets.id) as count');
        $this->db->from('categories');
        $this->db->join('assets', 'assets.category_id = categories.id AND assets.is_deleted = 0', 'left');
        $this->db->group_by('categories.id');
        $stats['categories'] = $this->db->get()->result();

        return $stats;
    }

    public function return_asset($id)
    {
        $this->db->where('id', $id);
        return $this->db->update('assets', [
            'status' => 'In Stock',
            'college_id' => NULL,
            'return_date' => NULL
        ]);
    }

    public function extend_loan($id, $new_date)
    {
        $this->db->where('id', $id);
        return $this->db->update('assets', ['return_date' => $new_date]);
    }

    public function partial_return($id, $qty_to_return)
    {
        $asset = $this->db->get_where('assets', ['id' => $id])->row_array();
        if (!$asset || $qty_to_return >= $asset['quantity'] || $qty_to_return <= 0) {
            return false; // Should use full return for full qty
        }

        $this->db->trans_start();

        // 1. Reduce quantity of allocated item
        $new_allocated_qty = $asset['quantity'] - $qty_to_return;
        $this->db->where('id', $id);
        $this->db->update('assets', ['quantity' => $new_allocated_qty]);

        // 2. Create new In Stock item
        $returned_asset = $asset;
        unset($returned_asset['id']);
        unset($returned_asset['created_at']);
        unset($returned_asset['updated_at']);

        $returned_asset['quantity'] = $qty_to_return;
        $returned_asset['status'] = 'In Stock';
        $returned_asset['college_id'] = NULL;
        $returned_asset['return_date'] = NULL;

        // Generate new tag slightly different to avoid conflict if unique constraint exists
        // Though for split items we typically want to track them.
        // Let's append a suffix if it doesn't have one, or change the suffix
        $suffix = substr(strtoupper(uniqid()), -4);
        $returned_asset['asset_tag'] = $asset['asset_tag'] . "-RET-" . $suffix;

        // Link to parent
        $returned_asset['parent_id'] = $asset['parent_id'] ? $asset['parent_id'] : $asset['id'];

        $this->db->insert('assets', $returned_asset);

        $this->db->trans_complete();
        return $this->db->trans_status();
    }

    public function get_recent_college_assets($college_id, $limit = 5)
    {
        $this->db->select('assets.*, categories.name as category_name');
        $this->db->from('assets');
        $this->db->join('categories', 'categories.id = assets.category_id', 'left');
        $this->db->where('college_id', $college_id);
        $this->db->order_by('assets.updated_at', 'DESC');
        $this->db->limit($limit);
        return $this->db->get()->result();
    }

    public function get_by_tag($tag)
    {
        $this->db->select('assets.*, categories.name as category_name, colleges.name as college_name');
        $this->db->from('assets');
        $this->db->join('categories', 'categories.id = assets.category_id', 'left');
        $this->db->join('colleges', 'colleges.id = assets.college_id', 'left');
        $this->db->where('assets.asset_tag', $tag);
        return $this->db->get()->row();
    }
}
