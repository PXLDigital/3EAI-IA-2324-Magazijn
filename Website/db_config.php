<?php
define('DB_SERVER', '192.168.1.220');
define('DB_USERNAME', 'Nathan');
define('DB_PASSWORD', 'Nathan');
define('DB_NAME', 'MagazijnDatabase');
define('DB_PORT', 3306); // Usually, this should be 3306 for MySQL

// Create connection
$conn = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME, DB_PORT);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
