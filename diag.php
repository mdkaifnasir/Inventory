<?php
// Load CodeIgniter framework if needed, but here we can just do a direct DB check
$host = '127.0.0.1';
$port = '3308';
$user = 'root';
$pass = ''; // Based on application/config/database.php
$db = 'inventory_system';

$conn = new mysqli($host, $user, $pass, $db, $port);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

echo "Database connected successfully.\n\n";

$sql = "SELECT is_deleted, COUNT(*) as count FROM assets GROUP BY is_deleted";
$result = $conn->query($sql);

echo "Asset Counts by is_deleted status:\n";
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $status = ($row["is_deleted"] === null) ? "NULL" : $row["is_deleted"];
        echo "Status: " . $status . " | Count: " . $row["count"] . "\n";
    }
} else {
    echo "0 results in assets table\n";
}

$sql2 = "SELECT id, name, is_deleted, deleted_at FROM assets LIMIT 10";
$result2 = $conn->query($sql2);
echo "\nSample Records:\n";
while ($row = $result2->fetch_assoc()) {
    echo "ID: " . $row['id'] . " | Name: " . $row['name'] . " | is_deleted: " . ($row['is_deleted'] ?? 'NULL') . " | deleted_at: " . ($row['deleted_at'] ?? 'NULL') . "\n";
}

$conn->close();
?>