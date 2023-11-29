<?php
include 'database.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["voegRekToe"])) {
        voegRekToe($_POST["rekID"], $_POST["rekNaam"]);
    } elseif (isset($_POST["voegProductToe"])) {
        voegProductToe($_POST["productID"], $_POST["productNaam"], $_POST["rekID"], $_POST["hoeveelheid"]);
    } elseif (isset($_POST["plaatsOrder"])) {
        plaatsOrder($_POST["orderID"], $_POST["rekID"], $_POST["productID"], $_POST["hoeveelheid"]);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rekken, Producten en Orders Toevoegen</title>
</head>
<body>
    <h1>Rekken, Producten en Orders Toevoegen</h1>

    <h2>Voeg een Rek Toe</h2>
    <form method="post" action="<?php echo $_SERVER["PHP_SELF"]; ?>">
        Rek ID: <input type="text" name="rekID" required>
        Rek Naam: <input type="text" name="rekNaam" required>
        <input type="submit" name="voegRekToe" value="Voeg Toe">
    </form>

    <h2>Voeg een Product Toe</h2>
    <form method="post" action="<?php echo $_SERVER["PHP_SELF"]; ?>">
        Product ID: <input type="text" name="productID" required>
        Product Naam: <input type="text" name="productNaam" required>
        Rek ID: <input type="text" name="rekID" required>
        Hoeveelheid: <input type="text" name="hoeveelheid" required>
        <input type="submit" name="voegProductToe" value="Voeg Toe">
    </form>

    <h2>Plaats een Order</h2>
    <form method="post" action="<?php echo $_SERVER["PHP_SELF"]; ?>">
        Order ID: <input type="text" name="orderID" required>
        Rek ID: <input type="text" name="rekID" required>
        Product ID: <input type="text" name="productID" required>
        Hoeveelheid: <
