<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Category_model extends CI_Model
{

    public function get_all()
    {
        return $this->db->get('categories')->result();
    }

    public function get_all_with_counts()
    {
        $this->db->select('categories.*, COUNT(assets.id) as total_items');
        $this->db->from('categories');
        $this->db->join('assets', 'assets.category_id = categories.id', 'left');
        $this->db->group_by('categories.id');
        return $this->db->get()->result();
    }

    public function get_by_id($id)
    {
        return $this->db->get_where('categories', ['id' => $id])->row();
    }

    public function insert($data)
    {
        if ($this->db->insert('categories', $data)) {
            return $this->db->insert_id();
        }
        return false;
    }

    public function update($id, $data)
    {
        $this->db->where('id', $id);
        return $this->db->update('categories', $data);
    }

    public function delete($id)
    {
        $this->db->where('id', $id);
        return $this->db->delete('categories');
    }
}
