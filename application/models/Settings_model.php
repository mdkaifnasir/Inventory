<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Settings_model extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
        $this->_check_table();
    }

    private function _check_table()
    {
        // Simple check to see if table exists, if not create it
        if (!$this->db->table_exists('system_settings')) {
            $this->load->dbforge();
            $this->dbforge->add_field([
                'setting_key' => [
                    'type' => 'VARCHAR',
                    'constraint' => '100',
                    'unique' => TRUE
                ],
                'setting_value' => [
                    'type' => 'TEXT',
                    'null' => TRUE
                ],
                'updated_at' => [
                    'type' => 'TIMESTAMP',
                    'default_string' => 'CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'
                ]
            ]);
            $this->dbforge->add_key('setting_key', TRUE);
            $this->dbforge->create_table('system_settings');

            // Insert defaults
            $defaults = [
                'system_name' => 'AZAM IT',
                'org_code' => 'ORG-001',
                'contact_email' => 'admin@azam.edu',
                'contact_phone' => '',
                'address' => '',
                'currency' => 'USD'
            ];

            foreach ($defaults as $k => $v) {
                $this->db->insert('system_settings', ['setting_key' => $k, 'setting_value' => $v]);
            }
        }
    }

    public function get_all_settings()
    {
        $query = $this->db->get('system_settings');
        $result = [];
        foreach ($query->result() as $row) {
            $result[$row->setting_key] = $row->setting_value;
        }
        return $result;
    }

    public function get_setting($key)
    {
        $query = $this->db->get_where('system_settings', ['setting_key' => $key]);
        $row = $query->row();
        return $row ? $row->setting_value : null;
    }

    public function update_setting($key, $value)
    {
        // Check if exists
        $exists = $this->db->where('setting_key', $key)->count_all_results('system_settings');

        if ($exists) {
            $this->db->where('setting_key', $key);
            return $this->db->update('system_settings', ['setting_value' => $value]);
        } else {
            return $this->db->insert('system_settings', ['setting_key' => $key, 'setting_value' => $value]);
        }
    }

    public function bulk_update($data)
    {
        $this->db->trans_start();
        foreach ($data as $key => $value) {
            $this->update_setting($key, $value);
        }
        $this->db->trans_complete();
        return $this->db->trans_status();
    }
}
