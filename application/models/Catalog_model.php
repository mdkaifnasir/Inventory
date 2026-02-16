<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Catalog_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->_check_table();
    }

    private function _check_table()
    {
        if (!$this->db->table_exists('catalog_items')) {
            $this->load->dbforge();
            $fields = [
                'id' => [
                    'type' => 'INT',
                    'constraint' => 11,
                    'unsigned' => TRUE,
                    'auto_increment' => TRUE
                ],
                'category_name' => [
                    'type' => 'VARCHAR',
                    'constraint' => 100
                ],
                'sub_category' => [
                    'type' => 'VARCHAR',
                    'constraint' => 100
                ],
                'item_name' => [
                    'type' => 'VARCHAR',
                    'constraint' => 100
                ],
                'created_at' => [
                    'type' => 'DATETIME',
                    'null' => TRUE
                ]
            ];
            $this->dbforge->add_field($fields);
            $this->dbforge->add_key('id', TRUE);
            $this->dbforge->create_table('catalog_items');

            // Seed defaults immediately upon creation
            $this->seed_defaults();
        }
    }

    public function seed_defaults()
    {
        // Check if already seeded with new structure (simple check: if 'Processing & Core Components' exists as category)
        $exists = $this->db->get_where('catalog_items', ['category_name' => 'Processing & Core Components'])->row();
        if ($exists) {
            return;
        }

        // New Flat Structure (Sub-cats promoted to Categories)
        $structure = [
            'Processing & Core Components' => [
                'CPU (Central Processing Unit)',
                'Motherboard',
                'RAM (Random Access Memory)',
                'ROM (Read Only Memory)',
                'Graphics Card (GPU)',
                'Sound Card',
                'Heat Sink',
                'Cooling Fan'
            ],
            'Storage Devices' => [
                'Hard Disk Drive (HDD)',
                'Solid State Drive (SSD)',
                'External Hard Drive',
                'USB Flash Drive (Pen Drive)',
                'Memory Card',
                'CD / DVD Drive',
                'Card Reader'
            ],
            'Input Devices' => [
                'Keyboard',
                'Mouse',
                'Scanner',
                'Webcam',
                'Microphone',
                'Joystick',
                'Barcode Scanner',
                'Touchpad',
                'Light Pen'
            ],
            'Output Devices' => [
                'Monitor',
                'Printer',
                'Speakers',
                'Headphones',
                'Projector'
            ],
            'Networking & Connectivity' => [
                'LAN Card',
                'Router',
                'Switch',
                'LAN Cable'
            ],
            'Power & Electrical' => [
                'Power Supply (SMPS)',
                'UPS (Uninterruptible Power Supply)'
            ],
            'System & Accessories' => [
                'System Unit / Cabinet'
            ]
        ];

        $data = [];
        $now = date('Y-m-d H:i:s');

        // Clear old data if re-seeding to avoid duplicates/confusion
        // ideally we would migrate, but for catalog lookup, re-seeding is fine as long as we migrate assets separately
        $this->db->truncate('catalog_items');

        foreach ($structure as $cat => $items) {
            foreach ($items as $item) {
                $data[] = [
                    'category_name' => $cat,
                    'sub_category' => null, // No longer used
                    'item_name' => $item,
                    'created_at' => $now
                ];
            }
        }

        if (!empty($data)) {
            $this->db->insert_batch('catalog_items', $data);
        }
    }

    public function get_sub_categories($category_name)
    {
        $this->db->distinct();
        $this->db->select('sub_category');
        $this->db->where('category_name', $category_name);
        $query = $this->db->get('catalog_items');
        return array_column($query->result_array(), 'sub_category');
    }

    public function get_items_by_category($category_name)
    {
        $this->db->select('item_name');
        $this->db->where('category_name', $category_name);
        $this->db->order_by('item_name', 'ASC');
        $query = $this->db->get('catalog_items');
        return array_column($query->result_array(), 'item_name');
    }

    // Returns array suitable for OptGroups: [ 'SubCat1' => ['Item1', 'Item2'], 'SubCat2' => ... ]
    public function get_grouped_items($category_name)
    {
        $this->db->select('sub_category, item_name');
        $this->db->where('category_name', $category_name);
        $this->db->order_by('sub_category', 'ASC');
        $this->db->order_by('item_name', 'ASC');
        $query = $this->db->get('catalog_items');

        $result = [];
        foreach ($query->result() as $row) {
            $result[$row->sub_category][] = $row->item_name;
        }
        return $result;
    }

    // Legacy support to return the full structure array if needed
    public function get_full_structure()
    {
        $data = $this->db->get('catalog_items')->result();
        $structure = [];

        foreach ($data as $row) {
            if (!isset($structure[$row->category_name])) {
                $structure[$row->category_name] = [];
            }
            if (!isset($structure[$row->category_name][$row->sub_category])) {
                $structure[$row->category_name][$row->sub_category] = [];
            }
            $structure[$row->category_name][$row->sub_category][] = $row->item_name;
        }

        return $structure;
    }
}
