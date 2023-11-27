<?php
$servername = "localhost";
$username = "root"; // default username for localhost
$password = "";     // default password for localhost
$dbname = "parts_order_system";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$contact = $_POST['contact'];

// Prepare and bind
$stmt = $conn->prepare("INSERT INTO contacts (contact_info) VALUES (?)");
$stmt->bind_param("s", $contact);

$stmt->execute();

$stmt->close();
$conn->close();

// Redirect to the main page of the website
header("Location: main_page.php");
exit();
?>
