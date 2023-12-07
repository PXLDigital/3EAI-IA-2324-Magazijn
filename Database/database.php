<?php
$servername = "jouw_servernaam";
$username = "jouw_gebruikersnaam";
$password = "jouw_wachtwoord";
$dbname_rek = "RekDatabase";
$dbname_order = "OrderDatabase";

// Functie om een rek toe te voegen
function voegRekToe($rekID, $rekNaam) {
    global $servername, $username, $password, $dbname_rek;

    $conn = new mysqli($servername, $username, $password, $dbname_rek);

    if ($conn->connect_error) {
        die("Verbinding mislukt: " . $conn->connect_error);
    }

    $sql = "INSERT INTO Rek (RekID, RekNaam) VALUES ('$rekID', '$rekNaam')";

    if ($conn->query($sql) === TRUE) {
        echo "Rek succesvol toegevoegd.";
    } else {
        echo "Fout bij het toevoegen van het rek: " . $conn->error;
    }

    $conn->close();
}

// Functie om een product toe te voegen
function voegProductToe($productID, $productNaam, $rekID, $Stock, $MinVoorraad ) {
    global $servername, $username, $password, $dbname_rek;

    $connOrder = new mysqli($servername, $username, $password, $dbname_order);
    $connRek = new mysqli($servername, $username, $password, $dbname_rek);

    if ($conn->connect_error) {
        die("Verbinding mislukt: " . $conn->connect_error);
    }

    // Controleer of het maximale aantal producten is bereikt
    $checkMaxSql = "SELECT AantalProducten, MaxAantalProducten FROM Rek WHERE RekID = '$rekID'";
    $result = $conn->query($checkMaxSql);
    $row = $result->fetch_assoc();
    $aantalProducten = $row["AantalProducten"];
    $maxAantalProducten = $row["MaxAantalProducten"];

    if ($aantalProducten >= $maxAantalProducten) {
        // Het rek is vol, zoek een ander rek om het product toe te voegen
        $anderRekSql = "SELECT RekID FROM Rek WHERE AantalProducten < MaxAantalProducten LIMIT 1";
        $resultAnderRek = $conn->query($anderRekSql);

        if ($resultAnderRek->num_rows > 0) {
            $rowAnderRek = $resultAnderRek->fetch_assoc();
            $nieuwRekID = $rowAnderRek["RekID"];

            // Voeg het product toe aan het andere rek
            $sql = "INSERT INTO Product (ProductID, ProductNaam, RekID, Stock, IsLeeg) VALUES ('$productID', '$productNaam', '$nieuwRekID', '$Stock', FALSE)";
            $conn->query($sql);

            // Werk het aantal producten in het nieuwe rek bij
            $updateSqlNieuwRek = "UPDATE Rek SET AantalProducten = AantalProducten + 1, IsLeeg = FALSE WHERE RekID = '$nieuwRekID'";
            $conn->query($updateSqlNieuwRek);

            echo "Product succesvol toegevoegd aan Rek $nieuwRekID omdat Rek $rekID vol is.";
        } else {
            echo "Fout: Alle rekken zijn vol.";
        }
    } else {
        // Voeg het product toe aan het huidige rek
        $sql = "INSERT INTO Product (ProductID, ProductNaam, RekID, Stock, IsLeeg) VALUES ('$productID', '$productNaam', '$rekID', '$Stock', FALSE)";
        $conn->query($sql);

        // Werk het aantal producten in het huidige rek bij
        $updateSqlHuidigRek = "UPDATE Rek SET AantalProducten = AantalProducten + 1, IsLeeg = FALSE WHERE RekID = '$rekID'";
        $conn->query($updateSqlHuidigRek);

        echo "Product succesvol toegevoegd aan Rek $rekID.";
    }

    $conn->close();
}
// Functie om een order toe te voegen
function plaatsOrder($orderID, $rekID, $productID, $hoeveelheid) {
    global $servername, $username, $password, $dbname_order, $dbname_rek;

    $connOrder = new mysqli($servername, $username, $password, $dbname_order);
    $connRek = new mysqli($servername, $username, $password, $dbname_rek);

    if ($connOrder->connect_error || $connRek->connect_error) {
        die("Verbinding mislukt: " . $connOrder->connect_error . " / " . $connRek->connect_error);
    }

    // Haal de productnaam op
    $getProductNameSql = "SELECT ProductNaam FROM RekDatabase.Product WHERE ProductID = '$productID'";
    $resultProductName = $connOrder->query($getProductNameSql);
    $rowProductName = $resultProductName->fetch_assoc();
    $productNaam = $rowProductName["ProductNaam"];

    // Voeg de order toe
    $sqlOrder = "INSERT INTO OrderTabel (OrderID, RekID, ProductID, ProductNaam, Hoeveelheid) VALUES ('$orderID', '$rekID', '$productID', '$productNaam', '$hoeveelheid')";
    if ($connOrder->query($sqlOrder) === TRUE) {
        echo "Order succesvol geplaatst.";

        // Werk de hoeveelheid van het product bij in de Product-tabel
        $updateProductSql = "UPDATE RekDatabase.Product SET Stock = Stock - '$hoeveelheid' WHERE ProductID = '$productID'";
        $connRek->query($updateProductSql);

        // Controleer of het product leeg is en verwijder het indien nodig uit het rek
        $checkEmptySql = "SELECT Stock FROM RekDatabase.Product WHERE ProductID = '$productID'";
        $resultEmpty = $connRek->query($checkEmptySql);
        $rowEmpty = $resultEmpty->fetch_assoc();
        $hoeveelheidNaOrder = $rowEmpty["Stock"];

        if ($hoeveelheidNaOrder <= $MinVoorraad) {
            // Controleer of het rek nu leeg is
            $checkEmptyRekSql = "SELECT AantalProducten FROM RekDatabase.Rek WHERE RekID = '$rekID'";
            $resultEmptyRek = $connRek->query($checkEmptyRekSql);
            $rowEmptyRek = $resultEmptyRek->fetch_assoc();
            $aantalProductenRek = $rowEmptyRek["AantalProducten"];

            $additionalQuantity = $maxAantalProducten - $aantalProductenRek;

            if ($additionalQuantity > 0) {
                // Add additional products to reach the maximum capacity
                $updateStockSql = "UPDATE RekDatabase.Product SET Stock = Stock + '$additionalQuantity' WHERE ProductID = '$productID'";
                $connRek->query($updateStockSql);
    
                // Update AantalProducten in Rek table
                $updateAantalSql = "UPDATE RekDatabase.Rek SET AantalProducten = AantalProducten + '$additionalQuantity', IsLeeg = FALSE WHERE RekID = '$rekID'";
                $connRek->query($updateAantalSql);

                // Update Stock in Product table
                $updateStockSqlProduct = "UPDATE RekDatabase.Product SET Stock = Stock - '$hoeveelheid' WHERE ProductID = '$productID'";
                $connRek->query($updateStockSqlProduct);
    
                echo "Added $additionalQuantity products to Rek $rekID to reach maximum capacity.";
            }

            if ($aantalProductenRek <= 0) {
                // Het rek is leeg, markeer als leeg
                $updateLeegRekSql = "UPDATE RekDatabase.Rek SET IsLeeg = TRUE WHERE RekID = '$rekID'";
                $connRek->query($updateLeegRekSql);
            }
        }
    } else {
        echo "Fout bij het plaatsen van de order: " . $connOrder->error;
    }

    $connOrder->close();
    $connRek->close();
}

