<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Fix extends CI_Controller
{

    public function index()
    {
        error_reporting(E_ALL);
        ini_set('display_errors', 1);

        try {
            if (!in_array($this->session->userdata('role_id'), [1, 2])) {
                die('Access denied. Please login as admin.');
            }

            echo "<h2>System Sync Tool</h2>";
            echo "Current Database: " . $this->db->database . "<br>";

            // 1. Check if column exists
            $fields = $this->db->list_fields('assets');
            if (!in_array('is_deleted', $fields)) {
                echo "<p style='color:red; font-weight:bold;'>CRITICAL ERROR: Column 'is_deleted' is missing from 'assets' table!</p>";
                echo "<p>Fields found: " . implode(', ', $fields) . "</p>";
                echo "<h3><a href='" . site_url('fix/add_column') . "' style='background: #e67e22; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;'>Click Here to Add Missing Column</a></h3>";
                return;
            } else {
                echo "<p style='color:green'>Column 'is_deleted' exists.</p>";
            }

            // 2. Fix data
            $this->db->query("UPDATE assets SET is_deleted = 0 WHERE is_deleted IS NULL");
            echo "Fixed NULL values.<br>";

            // 3. Count
            $active_count = $this->db->where('is_deleted', 0)->count_all_results('assets');
            $trash_count = $this->db->where('is_deleted', 1)->count_all_results('assets');

            echo "<h3>Current Stats:</h3>";
            echo "Active Items: $active_count<br>";
            echo "Trashed Items: $trash_count<br>";
            
            echo "<br><a href='" . site_url('assets') . "'>Go to Inventory</a>";

        } catch (Exception $e) {
            echo "Caught exception: " . $e->getMessage();
        } catch (Error $e) {
            echo "Caught error: " . $e->getMessage();
        }
    }

    public function add_column()
    {
        if ($this->session->userdata('role_id') != 1 && $this->session->userdata('role_id') != 2)
            die('Unauthorized');

        echo "<h2>Migration Started...</h2>";

        try {
            // Check again before adding
            $fields = $this->db->list_fields('assets');
            if (!in_array('is_deleted', $fields)) {
                echo "Adding 'is_deleted' column... ";
                $this->db->query("ALTER TABLE assets ADD COLUMN is_deleted TINYINT(1) DEFAULT 0");
                echo "Done.<br>";
            }

            if (!in_array('deleted_at', $fields)) {
                echo "Adding 'deleted_at' column... ";
                $this->db->query("ALTER TABLE assets ADD COLUMN deleted_at DATETIME DEFAULT NULL");
                echo "Done.<br>";
            }

            echo "<h3>Success! <a href='" . site_url('fix') . "'>Click here to Verify</a></h3>";
        } catch (Exception $e) {
            echo "Migration failed: " . $e->getMessage();
        }
    }
}
