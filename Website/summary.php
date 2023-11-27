<?php
session_start();

function calculateTotal($cart) {
    $total = 0;
    foreach ($cart as $item) {
        $total += $item['price'] * $item['quantity'];
    }
    return $total;
}

// Check if the cart is empty
if (empty($_SESSION['cart'])) {
    echo "<p>Your cart is empty.</p>";
    exit;
}

// Fetch cart data from the session
$cart = $_SESSION['cart'];

echo "<h1>Order Summary</h1>";

// Display the cart contents
echo "<table border='1'>";
echo "<tr><th>Part</th><th>Quantity</th><th>Price</th><th>Subtotal</th></tr>";

foreach ($cart as $item) {
    echo "<tr>";
    echo "<td>" . htmlspecialchars($item['name']) . "</td>";
    echo "<td>" . htmlspecialchars($item['quantity']) . "</td>";
    echo "<td>$" . htmlspecialchars($item['price']) . "</td>";
    echo "<td>$" . htmlspecialchars($item['quantity'] * $item['price']) . "</td>";
    echo "</tr>";
}

// Display total price
echo "<tr>";
echo "<td colspan='3' style='text-align: right;'>Total:</td>";
echo "<td>$" . calculateTotal($cart) . "</td>";
echo "</tr>";
echo "</table>";

// Display pickup details
echo "<h2>Pickup Details</h2>";
echo "Date: " . htmlspecialchars($_POST['pickup_date']) . "<br>";
echo "Time Slot: " . htmlspecialchars($_POST['pickup_slot']) . "<br>";

?>

<form action="place_order.php" method="POST">
    <!-- Pass along the necessary data in hidden fields -->
    <input type="hidden" name="pickup_date" value="<?php echo htmlspecialchars($_POST['pickup_date']); ?>">
    <input type="hidden" name="pickup_slot" value="<?php echo htmlspecialchars($_POST['pickup_slot']); ?>">
    <button type="submit">Place Order</button>
</form>
