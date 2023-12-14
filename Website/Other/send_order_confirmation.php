<?php
session_start();
include 'db_config.php';

$orderNumber = $_SESSION['last_order_number']; // Assuming the last order number is stored in the session
$userEmail = $_SESSION['user_email']; // Assuming the user's email is stored in the session

// Fetch order details
$sql = "SELECT OrderNumber, OrderDate, TotalAmount FROM orders WHERE OrderNumber = $orderNumber";
$orderResult = $conn->query($sql);
$orderDetails = $orderResult->fetch_assoc();

// Fetch order items
$itemSql = "SELECT ProductID, Quantity, Price FROM order_items WHERE OrderNumber = $orderNumber";
$itemsResult = $conn->query($itemSql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Order Confirmation Email</title>
    <style>
        .email-container {
            border: 1px solid #ddd;
            padding: 20px;
            max-width: 600px;
            margin: auto;
            font-family: Arial, sans-serif;
        }
        .email-header {
            background-color: #f4f4f4;
            padding: 10px;
            text-align: center;
            border-bottom: 1px solid #ddd;
        }
        .email-body {
            padding: 15px;
        }
        .email-footer {
            background-color: #f4f4f4;
            padding: 10px;
            text-align: center;
            border-top: 1px solid #ddd;
            margin-top: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f4f4f4;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="email-header">
            <h2>Order Confirmation</h2>
        </div>
        <div class="email-body">
            <p>Dear Customer,</p>
            <p>Thank you for your order. Here are your order details:</p>
            
            <p>Order Number: <?php echo $orderDetails['OrderNumber']; ?></p>
            <p>Order Date: <?php echo $orderDetails['OrderDate']; ?></p>
            <p>Total Amount: $<?php echo number_format($orderDetails['TotalAmount'], 2); ?></p>

            <h3>Order Items:</h3>
            <table>
                <tr>
                    <th>Product ID</th>
                    <th>Quantity</th>
                    <th>Price</th>
                </tr>
                <?php while ($item = $itemsResult->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $item['ProductID']; ?></td>
                        <td><?php echo $item['Quantity']; ?></td>
                        <td>$<?php echo number_format($item['Price'], 2); ?></td>
                    </tr>
                <?php endwhile; ?>
            </table>
        </div>
        <div class="email-footer">
            <p>If you have any questions, please contact us.</p>
            <p>Email: support@example.com</p>
        </div>
    </div>
</body>
</html>
