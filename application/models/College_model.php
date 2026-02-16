<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class College_model extends CI_Model
{
    public function get_all()
    {
        return $this->db->get('colleges')->result();
    }

    public function insert($data)
    {
        return $this->db->insert('colleges', $data);
    }

    public function update($id, $data)
    {
        $this->db->where('id', $id);
        return $this->db->update('colleges', $data);
    }

    public function delete($id)
    {
        $this->db->where('id', $id);
        return $this->db->delete('colleges');
    }

    public function get_by_id($id)
    {
        return $this->db->get_where('colleges', ['id' => $id])->row();
    }
}
