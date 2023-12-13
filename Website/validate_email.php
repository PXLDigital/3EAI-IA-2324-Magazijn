<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];

    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
        header("Location: webshop.php"); // Redirect to the dashboard page
    } else {
        echo "Invalid Email. Please go back and try again.";
    }
}
?>
