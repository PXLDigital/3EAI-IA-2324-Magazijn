<?php
session_start();

// Database credentials
$servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "parts_order_system";

// Create database connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the cart is already set in the session, if not, initialize it as an array
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

// Assuming your form sends part_id and quantity
$part_id = $_POST['part_id'] ?? null;
$quantity = $_POST['quantity'] ?? 0;

// Initialize variables for part name and price
$part_name = 'Unknown';
$part_price = 0;

// Fetch part name and price from the database
if ($part_id !== null) {
    $stmt = $conn->prepare("SELECT name, price FROM parts WHERE id = ?");
    $stmt->bind_param("i", $part_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        $part_name = $row['name'];
        $part_price = $row['price'];
    }

    $stmt->close();
}

    // ...

// Check if the part ID is valid and quantity is greater than 0
if ($part_id !== null && $quantity > 0) {
    // Ensure that the cart item is an array. If not, initialize it.
    if (!isset($_SESSION['cart'][$part_id]) || !is_array($_SESSION['cart'][$part_id])) {
        $_SESSION['cart'][$part_id] = array('quantity' => 0, 'name' => $part_name, 'price' => $part_price);
    }

    // Now safely add or update the quantity
    $_SESSION['cart'][$part_id]['quantity'] += $quantity;
} else {
    // Handle the case where part_id is not set or quantity is zero or negative
    // Redirect back to product page or show an error message
}

    // Close the database connection
    $conn->close();

    // Redirect back to the shopping page or show the cart
    header('Location: ' . $_SERVER['HTTP_REFERER']);
    exit();
    ?>
