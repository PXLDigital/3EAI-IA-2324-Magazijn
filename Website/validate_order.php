<?php
session_start();
include 'db_config.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Begin transaction
    $conn->begin_transaction();

    try {
        foreach ($_SESSION["cart"] as $productId => $quantity) {
            // Update product stock
            // Assuming you want to decrease the stock after an order
            $updateSql = "UPDATE product SET Stock = Stock + $quantity WHERE ProductID = $productId";
            $conn->query($updateSql);

            //Insert order details into `orders` table (if needed)
            // Ensure you have variables like $userId and $calculatedTotalAmount defined correctly
            $insertOrderSql = "INSERT INTO `ordertabel` (ProductID, Quantity) VALUES ($productId, $quantity)";
            $conn->query($insertOrderSql);            
        }

        // Commit transaction
        $conn->commit();

        // Clear the cart
        $_SESSION["cart"] = [];

        // Set order_completed session variable before redirecting
        $_SESSION['order_completed'] = true;

        // Redirect to webshop.php
        header("Location: webshop.php");
        exit;  // It's important to call exit() after header redirection

    } catch (Exception $e) {
        // Rollback transaction on error
        $conn->rollback();
        echo "An error occurred: " . $e->getMessage(); // Display the error message
    }

    $conn->close();
} else {
    header("Location: webshop.php"); // Redirect to webshop if accessed directly
    exit;  // Include exit here as well
}
?>
