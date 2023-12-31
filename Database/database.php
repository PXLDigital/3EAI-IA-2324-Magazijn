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
function voegProductToe($productID, $productNaam, $rekID, $Stock, $minimaleVoorraad) {
    global $servername, $username, $password, $dbname_rek;

    $conn = new mysqli($servername, $username, $password, $dbname_rek);

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
            $sql = "INSERT INTO Product (ProductID, ProductNaam, RekID, Stock, minimaleVoorraad, IsLeeg) VALUES ('$productID', '$productNaam', '$nieuwRekID', '$Stock', '$minimaleVoorraad',  FALSE)";
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
function plaatsOrder($orderID, $rekID, $productID, $Stock) {
    global $servername, $username, $password, $dbname_order, $dbname_rek;

    $connOrder = new mysqli($servername, $username, $password, $dbname_order);
    $connRek = new mysqli($servername, $username, $password, $dbname_rek);

    if ($connOrder->connect_error || $connRek->connect_error) {
        die("Verbinding mislukt: " . $connOrder->connect_error . " / " . $connRek->connect_error);
    }

    // Haal de productnaam op
    $getProductNameSql = "SELECT ProductNaam, Stock, MinimaleVoorraad  FROM RekDatabase.Product WHERE ProductID = '$productID'";
    $resultProductName = $connOrder->query($getProductNameSql);
    $rowProductName = $resultProductName->fetch_assoc();
    $productNaam = $rowProductName["ProductNaam"];
	$huidigeVoorraad = $rowProductInfo["Stock"];
    $minimaleVoorraad = $rowProductInfo["MinimaleVoorraad"];

    // Voeg de order toe
    $sqlOrder = "INSERT INTO OrderTabel (OrderID, RekID, ProductID, ProductNaam, Stock) VALUES ('$orderID', '$rekID', '$productID', '$productNaam', '$Stock')";
    if ($connOrder->query($sqlOrder) === TRUE) {
        echo "Order succesvol geplaatst.";

        // Werk de Stock van het product bij in de Product-tabel
        $updateProductSql = "UPDATE RekDatabase.Product SET Stock = Stock - '$Stock' WHERE ProductID = '$productID'";
        $connRek->query($updateProductSql);
		
		// Controleer of de voorraad onder het minimale niveau is en toon een melding
        if (($huidigeVoorraad - $Stock) < $minimaleVoorraad) {
            echo "Let op: De voorraad van $productNaam is onder het minimale niveau gekomen!";
        }

        // Controleer of het product leeg is en verwijder het indien nodig uit het rek
        $checkEmptySql = "SELECT Stock FROM RekDatabase.Product WHERE ProductID = '$productID'";
        $resultEmpty = $connRek->query($checkEmptySql);
        $rowEmpty = $resultEmpty->fetch_assoc();
        $StockNaOrder = $rowEmpty["Stock"];

        if ($StockNaOrder <= 0) {
            // Verwijder het product uit het rek
            $deleteProductSql = "DELETE FROM RekDatabase.Product WHERE ProductID = '$productID'";
            $connRek->query($deleteProductSql);

            // Werk het aantal producten in het rek bij
            $updateAantalProductenSql = "UPDATE RekDatabase.Rek SET AantalProducten = AantalProducten - 1 WHERE RekID = '$rekID'";
            $connRek->query($updateAantalProductenSql);

            // Controleer of het rek nu leeg is
            $checkEmptyRekSql = "SELECT AantalProducten FROM RekDatabase.Rek WHERE RekID = '$rekID'";
            $resultEmptyRek = $connRek->query($checkEmptyRekSql);
            $rowEmptyRek = $resultEmptyRek->fetch_assoc();
            $aantalProductenRek = $rowEmptyRek["AantalProducten"];

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

