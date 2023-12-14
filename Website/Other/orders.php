<!DOCTYPE html>
<html>
<head>
    <title>Your Orders</title>
    <!-- Add any additional head elements here -->
</head>
<body>
    <h1>Your Previous Orders</h1>

    <?php if ($result->num_rows > 0): ?>
        <table>
            <tr>
                <th>Order Number</th>
                <th>Date</th>
                <th>Total Amount</th>
            </tr>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo htmlspecialchars($row["OrderNumber"]); ?></td>
                    <td><?php echo htmlspecialchars($row["OrderDate"]); ?></td>
                    <td>$<?php echo htmlspecialchars(number_format($row["TotalAmount"], 2)); ?></td>
                </tr>
            <?php endwhile; ?>
        </table>
    <?php else: ?>
        <p>You have no previous orders.</p>
    <?php endif; ?>

    <!-- Add any additional HTML here -->
    
    <nav>
        <!-- Navigation Links -->
    </nav>
</body>
</html>
