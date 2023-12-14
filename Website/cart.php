<?php
session_start();

include 'db_config.php'; // Include your database configuration file

// Check if the cart is empty
if (empty($_SESSION["cart"])) {
    echo "Your cart is empty. <a href='webshop.php'>Go back to the shop</a>.";
    exit;
}

$cartItems = implode(",", array_keys($_SESSION["cart"]));
$sql = "SELECT ProductID, ProductNaam, Stock FROM product WHERE ProductID IN ($cartItems)";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Your Shopping Cart</title>
</head>
<body>
    <h1>Your Shopping Cart</h1>

    <?php if ($result->num_rows > 0): ?>
        <form action="validate_order.php" method="post">
            <table>
                <tr>
                    <th>Product ID</th>
                    <th>Name</th>
                    <th>Quantity</th>
                </tr>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $row["ProductID"]; ?></td>
                        <td><?php echo $row["ProductNaam"]; ?></td>
                        <td><?php echo $_SESSION["cart"][$row["ProductID"]]; ?></td>
                    </tr>
                <?php endwhile; ?>
            </table> <!-- Close the table tag outside the while loop -->
            <input type="submit" value="Validate Order">
        </form>
    <?php else: ?>
        <p>Your cart is empty.</p>
    <?php endif; ?>

    <nav>
        <!-- Navigation Links -->
    </nav>

    <?php $conn->close(); ?>
</body>
</html>