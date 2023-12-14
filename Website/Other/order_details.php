<?php
session_start();
include 'db_config.php';

$orderNumber = $_GET['orderNumber'];

// Fetch details for a specific order
$sql = "SELECT ProductID, Quantity, Price FROM order_items WHERE OrderNumber = $orderNumber"; // Adjust your query based on your database structure
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Order Details</title>
</head>
<body>
    <h1>Order Details - Order Number: <?php echo $orderNumber; ?></h1>

    <?php if ($result->num_rows > 0): ?>
        <table>
            <tr>
                <th>Product ID</th>
                <th>Quantity</th>
                <th>Price</th>
            </tr>

            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $row["ProductID"]; ?></td>
                    <td><?php echo $row["Quantity"]; ?></td>
                    <td>$<?php echo number_format($row["Price"], 2); ?></td>
                </tr>
            <?php endwhile; ?>
        </table>
    <?php else: ?>
        <p>No details found for this order.</p>
    <?php endif; ?>

    <nav>
        <!-- Navigation Links -->
    </nav>

    <?php $conn->close(); ?>
</body>
</html>
