<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Audit_model extends CI_Model
{

    public function log_action($action, $target_type = null, $target_id = null, $details = null)
    {
        $data = array(
            'user_id' => $this->session->userdata('user_id'),
            'action' => $action,
            'target_type' => $target_type,
            'target_id' => $target_id,
            'details' => $details,
            'ip_address' => $this->input->ip_address()
        );
        return $this->db->insert('audit_logs', $data);
    }

    public function get_all_logs()
    {
        $this->db->select('audit_logs.*, users.full_name as user_name, users.username');
        $this->db->from('audit_logs');
        $this->db->join('users', 'users.id = audit_logs.user_id', 'left');
        $this->db->order_by('audit_logs.created_at', 'DESC');
        return $this->db->get()->result();
    }
}
