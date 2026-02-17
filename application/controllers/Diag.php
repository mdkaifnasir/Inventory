<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Diag extends CI_Controller
{

    public function index()
    {
        if (!in_array($this->session->userdata('role_id'), [1])) {
            die('Access denied');
        }

        echo "<h2>Database Diagnostics</h2>";

        $q1 = $this->db->query("SELECT is_deleted, COUNT(*) as count FROM assets GROUP BY is_deleted");
        echo "<h3>Asset counts by is_deleted:</h3>";
        foreach ($q1->result_array() as $row) {
            echo "Status: " . ($row['is_deleted'] ?? 'NULL') . " | Count: " . $row['count'] . "<br>";
        }

        $q2 = $this->db->query("SELECT id, name, is_deleted, deleted_at FROM assets LIMIT 20");
        echo "<h3>Sample Records:</h3>";
        echo "<table border='1'><tr><th>ID</th><th>Name</th><th>is_deleted</th><th>deleted_at</th></tr>";
        foreach ($q2->result_array() as $row) {
            echo "<tr>";
            echo "<td>" . $row['id'] . "</td>";
            echo "<td>" . $row['name'] . "</td>";
            echo "<td>" . ($row['is_deleted'] ?? 'NULL') . "</td>";
            echo "<td>" . ($row['deleted_at'] ?? 'NULL') . "</td>";
            echo "</tr>";
        }
        echo "</table>";

        echo "<h3>Column Info:</h3>";
        $fields = $this->db->list_fields('assets');
        echo "Fields in 'assets': " . implode(', ', $fields);
    }
}
