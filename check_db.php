<?php
$mysqli = new mysqli("127.0.0.1", "root", "", "inventory_system", 3308);
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}
$queries = [
    "ALTER TABLE assets ADD COLUMN is_deleted TINYINT(1) DEFAULT 0",
    "ALTER TABLE assets ADD COLUMN deleted_at DATETIME DEFAULT NULL"
];

foreach ($queries as $query) {
    if ($mysqli->query($query)) {
        echo "Successfully executed: $query\n";
    } else {
        echo "Error executing $query: " . $mysqli->error . "\n";
    }
}
$mysqli->close();
?>