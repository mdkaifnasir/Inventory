<?php
define('BASEPATH', 'dummy');
include 'index.php'; // Bootstrap CodeIgniter

$CI =& get_instance();
$categories = $CI->db->get('categories')->result();

echo "Existing Categories:\n";
foreach ($categories as $cat) {
    echo "ID: " . $cat->id . " - Name: " . $cat->name . "\n";
}
