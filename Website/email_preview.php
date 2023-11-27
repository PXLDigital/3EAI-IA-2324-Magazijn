<?php
// Start the session
session_start();

// Check if the order details are stored in the session
if (isset($_SESSION['order_details'])) {
    $orderDetails = $_SESSION['order_details']; // Assuming this is an array with all the details
} else {
    // If there are no order details in the session, handle this case appropriately
    echo "No order details available.";
    exit;
}

// Email content
$to = $orderDetails['email']; // The recipient's email address
$subject = "Your Order Summary";
$message = "Here are the details of your order:";

// Start output buffering to capture the following HTML
ob_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Email Preview</title>
    <!-- Styles to make it look like an email -->
</head>
<body>
    <p><strong>To:</strong> <?php echo htmlspecialchars($to); ?></p>
    <p><strong>Subject:</strong> <?php echo htmlspecialchars($subject); ?></p>
    <div>
        <!-- Output the order details -->
        <?php echo nl2br(htmlspecialchars($message)); ?>
        <!-- Add more HTML to format the message nicely -->
    </div>
</body>
</html>

<?php
// End the output buffering and get the contents
$htmlOutput = ob_get_clean();

echo $htmlOutput;
?>
