<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth_model extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
    }

    public function register($data)
    {
        $this->db->insert('users', $data);
        return $this->db->insert_id();
    }

    public function login($identity, $password)
    {
        $this->db->group_start();
        $this->db->where('email', $identity);
        $this->db->or_where('username', $identity);
        $this->db->group_end();

        $user = $this->db->get('users')->row();

        if ($user && password_verify($password, $user->password_hash)) {
            return $user;
        }
        return FALSE;
    }

    public function get_all_users()
    {
        $this->db->select('users.*, roles.role_name');
        $this->db->from('users');
        $this->db->join('roles', 'roles.id = users.role_id');
        $this->db->order_by('users.created_at', 'DESC');
        return $this->db->get()->result();
    }

    public function get_roles()
    {
        return $this->db->get('roles')->result();
    }

    public function get_user_by_id($id)
    {
        $this->db->select('users.*, roles.role_name');
        $this->db->from('users');
        $this->db->join('roles', 'roles.id = users.role_id');
        $this->db->where('users.id', $id);
        return $this->db->get()->row();
    }

    public function update_user($id, $data)
    {
        $this->db->where('id', $id);
        return $this->db->update('users', $data);
    }

    public function delete_user($id)
    {
        $this->db->where('id', $id);
        return $this->db->delete('users');
    }
}
