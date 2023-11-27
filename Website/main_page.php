<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Main Page</title>
    <a href="cart.php">View Cart</a>
    <a href="email_preview.php" target="_blank">View Order Details</a>

    <style>
        /* Add your CSS styling here */
        .about-section {
            margin: 20px;
            padding: 20px;
            background-color: #f2f2f2; /* Light grey background */
            border: 1px solid #ddd; /* Light grey border */
        }
        </style>
</head>
<body>
    <h1>Welcome to the Main Page</h1>

    <!-- Existing About Section -->
    <!-- ... -->

    <!-- Web Shop Section -->
    <div class="web-shop">
        <h2>Our Parts</h2>
        <?php
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "parts_order_system";

        // Create connection
        $conn = new mysqli($servername, $username, $password, $dbname);

        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        $sql = "SELECT id, name, image_url, stock, price FROM parts";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            // Output data of each row
            while($row = $result->fetch_assoc()) {
                echo "<div class='part-item'>";
                echo "<h3>" . $row["name"]. "</h3>";
                echo "<img src='" . $row["image_url"] . "' alt='" . $row["name"] . "' style='width:100px;height:100px;'>";
                echo "<p>Stock: " . $row["stock"] . "</p>";
                echo "<p>Price: $" . $row["price"] . "</p>";
                echo "<form action='add_to_cart.php' method='POST'>";
                echo "<input type='number' name='quantity' min='1' max='" . $row["stock"] . "' value='1'>";
                echo "<input type='hidden' name='part_id' value='" . $row["id"] . "'>";
                echo "<button type='submit'>Add to Cart</button>";
                echo "</form>";
                echo "</div>";
            }
        } else {
            echo "0 results";
        }
        $conn->close();
        ?>
    </div>

</body>
</html>
