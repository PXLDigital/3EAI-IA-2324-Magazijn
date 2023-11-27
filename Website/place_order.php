<?php
session_start();


// Database connection variables
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

// Check if the cart is empty
if (empty($_SESSION['cart'])) {
    echo "<p>Your cart is empty.</p>";
    exit;
}

// // Fetch user email from the session
// if (isset($_SESSION['user_email'])) {
//     $user_email = $_SESSION['user_email'];
// } else {
//     // Handle the case where the user email is not set in the session
//     // For example, redirect to a login page or give an error message
//     echo "User email is not set.";
//     exit; // Stop the script if the email is not set
// }

// Fetch cart data from the session
$cart = $_SESSION['cart'];

// Fetch pickup details from POST data
$pickup_date = $_POST['pickup_date'];
$pickup_slot = $_POST['pickup_slot'];

// Begin the order insertion process
$conn->autocommit(FALSE); // Use transaction to ensure data integrity

try {
    // Insert the main order details into your orders table
    // Assuming you have an 'orders' table with relevant fields
    $stmt = $conn->prepare("INSERT INTO orders (pickup_date, pickup_slot) VALUES (?, ?)");
    $stmt->bind_param("ss", $pickup_date, $pickup_slot);
    $stmt->execute();
    $order_id = $stmt->insert_id;
    $stmt->close();

    // Insert each cart item into an order_items table
    // Assuming you have an 'order_items' table
    $stmt = $conn->prepare("INSERT INTO order_items (order_id, part_id, quantity, price) VALUES (?, ?, ?, ?)");
    foreach ($cart as $item) {
        $stmt->bind_param("iiid", $order_id, $item['id'], $item['quantity'], $item['price']);
        $stmt->execute();
    }
    $stmt->close();

    // Commit the transaction
    $conn->commit();

    // Email content
    $to = 'your@email.com'; // Replace with your email address
    $subject = 'New Order Received';
    $message = "Order ID: $order_id\nPickup Date: $pickup_date\nPickup Slot: $pickup_slot\n\nOrder Details:\n";
    foreach ($cart as $item) {
        $message .= $item['name'] . " - Quantity: " . $item['quantity'] . ", Price: $" . $item['price'] . "\n";
    }

    // Send email
    mail($to, $subject, $message);

    // Clear the cart from the session
    unset($_SESSION['cart']);

// Generate a unique order code
$unique_order_code = uniqid('order_');

// Generate a QR code
// For simplicity, using an external API here:
$qr_code_url = 'https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=' . urlencode($unique_order_code);

// Email content
$to = $user_email;
$subject = 'Your Order Summary';
$headers = "From: webshop@example.com\r\n";
$headers .= "Content-Type: text/html; charset=UTF-8\r\n";
$message = "<html><body>";
$message .= "<p>Order ID: $order_id</p>";
$message .= "<p>Pickup Date: $pickup_date</p>";
$message .= "<p>Pickup Slot: $pickup_slot</p>";
$message .= "<p>Order Details:</p>";
$message .= "<ul>";

$total_price = 0;
foreach ($cart as $item) {
    $subtotal = $item['quantity'] * $item['price'];
    $total_price += $subtotal;
    $message .= "<li>" . htmlspecialchars($item['name']) . " - Quantity: " . htmlspecialchars($item['quantity']) . ", Price: $" . htmlspecialchars($item['price']) . ", Subtotal: $" . htmlspecialchars($subtotal) . "</li>";
}
$message .= "</ul>";
$message .= "<p>Total Price: $" . htmlspecialchars($total_price) . "</p>";
$message .= "<p>Unique Order Code: $unique_order_code</p>";
$message .= "<img src='" . htmlspecialchars($qr_code_url) . "' alt='QR Code'>";
$message .= "</body></html>";

// Send email
mail($to, $subject, $message, $headers);

// Clear the cart from the session
unset($_SESSION['cart']);

// Redirect to the orders page
header('Location: orders.php');
exit();


} catch (Exception $e) {
    // Rollback the transaction in case of error
    $conn->rollback();
    echo "Error: " . $e->getMessage();
}
?>
