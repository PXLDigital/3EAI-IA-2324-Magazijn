<?php
include 'db_config.php'; // Include your database configuration file

// Fetch products from the database
$sql = "SELECT ProductID, ProductNaam, RekID, Stock, MinVoorraad, IsEmpty FROM product"; // Adjust the table name and columns as per your database
$result = $conn->query($sql);
?>

<?php
session_start();

// Check if the order was just completed
if (isset($_SESSION['order_completed']) && $_SESSION['order_completed']) {
    // Unset the variable to avoid repeated tab opening
    unset($_SESSION['order_completed']);
}
?>


<!DOCTYPE html>
<html>
<head>
    <title>Web Shop</title>
</head>
<body>
    <h1>Our Products</h1>

    <?php if ($result->num_rows > 0): ?>
        <form action="add_to_cart.php" method="post">
            <table>
                <tr>
                    <th>Product ID</th>
                    <th>Name</th>
                    <th>Shelf ID</th>
                    <th>Stock</th>
                    <th>Min Stock</th>
                    <th>Is Empty</th>
                    <th>Quantity</th>
                </tr>

                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $row["ProductID"]; ?></td>
                        <td><?php echo $row["ProductNaam"]; ?></td>
                        <td><?php echo $row["RekID"]; ?></td>
                        <td><?php echo $row["Stock"]; ?></td>
                        <td><?php echo $row["MinVoorraad"]; ?></td>
                        <td><?php echo $row["IsEmpty"] ? "Yes" : "No"; ?></td>
                        <td>
                            <input type="number" name="quantity[<?php echo $row["ProductID"]; ?>]" min="1" max="<?php echo $row["Stock"]; ?>">
                        </td>
                    </tr>
                <?php endwhile; ?>
            </table>
            <input type="submit" value="Add to Cart">
        </form>
    <?php else: ?>
        <p>No products found.</p>
    <?php endif; ?>

    <nav>
        <!-- Navigation Links -->
    </nav>

    <?php $conn->close(); ?>
</body>
</html>
