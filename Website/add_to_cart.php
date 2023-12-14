<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!isset($_SESSION["cart"])) {
        $_SESSION["cart"] = [];
    }

    foreach ($_POST['quantity'] as $productId => $quantity) {
        if ($quantity > 0) {
            $_SESSION["cart"][$productId] = $quantity;
        }
    }

    header("Location: cart.php"); // Redirect to the cart page
}
?>
