<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Stats_model extends CI_Model
{

    public function get_counts()
    {
        return array(
            'total_assets' => $this->db->count_all('assets'),
            'total_users' => $this->db->count_all('users'),
            'total_categories' => $this->db->count_all('categories'),
            'in_stock' => $this->db->where('status', 'In Stock')->count_all_results('assets'),
            'deployed' => $this->db->where('status', 'Deployed')->count_all_results('assets')
        );
    }

    public function get_assets_by_category()
    {
        $this->db->select('categories.name as category, COUNT(assets.id) as count');
        $this->db->from('categories');
        $this->db->join('assets', 'assets.category_id = categories.id', 'left');
        $this->db->group_by('categories.id');
        return $this->db->get()->result();
    }

    public function get_recent_assets($limit = 5)
    {
        $this->db->select('assets.*, categories.name as category_name');
        $this->db->from('assets');
        $this->db->join('categories', 'categories.id = assets.category_id', 'left');
        $this->db->order_by('assets.created_at', 'DESC');
        $this->db->limit($limit);
        return $this->db->get()->result();
    }

    public function get_loans_due_soon($days = 7)
    {
        $this->db->select('assets.*, colleges.name as college_name, colleges.code as college_code');
        $this->db->from('assets');
        $this->db->join('colleges', 'colleges.id = assets.college_id', 'left');
        $this->db->where('return_date >=', date('Y-m-d'));
        $this->db->where('return_date <=', date('Y-m-d', strtotime("+$days days")));
        $this->db->where('status', 'On Loan');
        $this->db->order_by('return_date', 'ASC');
        return $this->db->get()->result();
    }

    public function get_low_stock($threshold = 5)
    {
        $this->db->select('assets.*, categories.name as category_name');
        $this->db->from('assets');
        $this->db->join('categories', 'categories.id = assets.category_id', 'left');
        $this->db->where('assets.quantity <=', $threshold);
        $this->db->where('assets.status', 'In Stock');
        $this->db->order_by('assets.quantity', 'ASC');
        return $this->db->get()->result();
    }

    public function get_college_stats($college_id)
    {
        // Total Assets
        $total = $this->db->where('college_id', $college_id)->count_all_results('assets');

        // Good Condition (New or Used (Good))
        $this->db->where('college_id', $college_id);
        $this->db->group_start();
        $this->db->where('asset_condition', 'New');
        $this->db->or_where('asset_condition', 'Used (Good)');
        $this->db->group_end();
        $good = $this->db->count_all_results('assets');

        // Due soon (On Loan)
        $due = $this->db->where('college_id', $college_id)
            ->where('status', 'On Loan')
            ->count_all_results('assets');

        return [
            'total_assets' => $total,
            'good_condition' => $good,
            'due_soon' => $due
        ];
    }

    public function get_college_category_distribution($college_id)
    {
        $this->db->select('categories.name as category, COUNT(assets.id) as count');
        $this->db->from('categories');
        $this->db->join('assets', 'assets.category_id = categories.id AND assets.college_id = ' . $this->db->escape($college_id), 'left');
        $this->db->group_by('categories.id');
        return $this->db->get()->result();
    }

    // New Analytics Methods for Admin Dashboard

    public function get_issue_analytics($filters = [])
    {
        // 1. Issues by College
        $this->db->select('colleges.name as college_name, COUNT(asset_issues.id) as issue_count');
        $this->db->from('colleges');
        $this->db->join('assets', 'assets.college_id = colleges.id', 'left');
        $this->db->join('asset_issues', 'asset_issues.asset_id = assets.id', 'left');

        // Apply Filters
        $this->_apply_analytics_filters($filters);

        $this->db->group_by('colleges.id');
        $this->db->order_by('issue_count', 'DESC');
        $this->db->limit(10);
        $issues_by_college = $this->db->get()->result();

        // 2. Issues by Product (Asset Name/Model) - Top 5
        $this->db->select('assets.name as product_name, COUNT(asset_issues.id) as issue_count');
        $this->db->from('asset_issues');
        $this->db->join('assets', 'assets.id = asset_issues.asset_id');

        $this->_apply_analytics_filters($filters);

        $this->db->group_by('assets.name'); // Group by name to catch similar products
        $this->db->order_by('issue_count', 'DESC');
        $this->db->limit(5);
        $issues_by_product = $this->db->get()->result();

        // 3. Issues by Status
        $this->db->select('status, COUNT(*) as count');
        $this->db->from('asset_issues');
        if (isset($filters['college_id']) && !empty($filters['college_id'])) {
            $this->db->join('assets', 'assets.id = asset_issues.asset_id');
            $this->db->where('assets.college_id', $filters['college_id']);
        }
        // Date filters for status count too
        if (!empty($filters['start_date'])) {
            $this->db->where('asset_issues.created_at >=', $filters['start_date']);
        }
        if (!empty($filters['end_date'])) {
            $this->db->where('asset_issues.created_at <=', $filters['end_date'] . ' 23:59:59');
        }
        $this->db->group_by('status');
        $issues_by_status = $this->db->get()->result();

        // 4. Trend Over Time (Last 30 days or selected range)
        $date_format = '%Y-%m-%d'; // MySQL Format
        $this->db->select("DATE_FORMAT(asset_issues.created_at, '$date_format') as date, COUNT(*) as count");
        $this->db->from('asset_issues');
        if (isset($filters['college_id']) && !empty($filters['college_id'])) {
            $this->db->join('assets', 'assets.id = asset_issues.asset_id');
            $this->db->where('assets.college_id', $filters['college_id']);
        }
        $this->_apply_analytics_filters($filters, false); // False to skip join if already joined
        $this->db->group_by('date');
        $this->db->order_by('date', 'ASC');
        $issue_trend = $this->db->get()->result();

        return [
            'by_college' => $issues_by_college,
            'by_product' => $issues_by_product,
            'by_status' => $issues_by_status,
            'trend' => $issue_trend
        ];
    }

    private function _apply_analytics_filters($filters, $join_assets = true)
    {
        if ($join_assets && (isset($filters['college_id']) || isset($filters['product_id']))) {
            // Ensure assets table is joined if not already joined in main query
            // This is context dependent, so care is needed. 
            // For simplify, assumption: 'assets' table is available or we join it.
            // In 'Issues by College' query above, assets IS joined.
            // In 'Issues by Product', assets IS joined.
        }

        if (!empty($filters['college_id'])) {
            $this->db->where('assets.college_id', $filters['college_id']);
        }

        if (!empty($filters['status'])) {
            $this->db->where('asset_issues.status', $filters['status']);
        }

        if (!empty($filters['start_date'])) {
            $this->db->where('asset_issues.created_at >=', $filters['start_date']);
        }

        if (!empty($filters['end_date'])) {
            $this->db->where('asset_issues.created_at <=', $filters['end_date'] . ' 23:59:59');
        }

        // Exclude soft deleted if not specifically requested (or handled elsewhere)
        // Usually analytics should probably include deleted unless specified otherwise?
        // Let's exclude deleted by default for accurate "active" issue tracking
        $this->db->where('asset_issues.is_deleted', 0);
    }
}
