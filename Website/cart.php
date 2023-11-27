<?php
session_start();

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

// Function to calculate total price
function calculateTotal($cart) {
    $total = 0;
    foreach ($cart as $item) {
        $total += $item['price'] * $item['quantity'];
    }
    return $total;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Shopping Cart</title>
</head>
<body>
    <h1>Your Shopping Cart</h1>

    <?php if (empty($_SESSION['cart'])): ?>
        <p>Your cart is empty.</p>
    <?php else: ?>
        <table>
            <tr>
                <th>Part</th>
                <th>Quantity</th>
                <th>Price</th>
                <th>Subtotal</th>
            </tr>

            <?php foreach ($_SESSION['cart'] as $item): ?>
            <tr>
                <td><?php echo $item['name']; ?></td>
                <td><?php echo $item['quantity']; ?></td>
                <td>$<?php echo $item['price']; ?></td>
                <td>$<?php echo $item['quantity'] * $item['price']; ?></td>
            </tr>
            <?php endforeach; ?>

            <tr>
                <td colspan="3" align="right">Total:</td>
                <td>$<?php echo calculateTotal($_SESSION['cart']); ?></td>
            </tr>
        </table>
    <?php endif; ?>
</body>
</html>

<!-- Existing cart display code -->

<form action="summary.php" method="POST">
    <h3>Select Pickup Time</h3>
    <label for="pickup_date">Pickup Date:</label>
    <input type="date" id="pickup_date" name="pickup_date" required>

    <label for="pickup_slot">Pickup Time Slot:</label>
    <select id="pickup_slot" name="pickup_slot" required>
        <option value="9am-12pm">9 AM - 12 PM</option>
        <option value="12pm-3pm">12 PM - 3 PM</option>
        <option value="3pm-6pm">3 PM - 6 PM</option>
        <!-- Add more slots as needed -->
    </select>

    <button type="submit">Validate Order</button>
</form>

