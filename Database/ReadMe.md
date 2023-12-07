# Database 

Er zijn twee databases, een magazijn database en een order database. De magazijn database houdt bij welke en hoeveel producten er in de rekken zijn gestockeerd. Deze wordt geupdated als er een order in de orderdatabase wordt geplaatst. Als de producten onder minimum aantal van producten gaat zullen er automatisch nieuwe producten worden toegevoegd. 

## Inhoud
1. [MagazijnDatabase](#magazijndatabase)
2. [OrderDatabase](#orderdatabase)
3. [php](#php)
4. [Databaseconfiguratie](#databaseconfiguratie)
5. [Functies](#functies)
    - [voegRekToe](#functie-om-een-rek-toe-te-voegen)
    - [voegProductToe](#functie-om-een-product-toe-te-voegen)
    - [plaatsOrder](#functie-om-een-order-toe-te-voegen)

<br/>
<br/>
<br/>

# MagazijnDatabase en OrderDatabase


## MagazijnDatabase

1. **Databasecreatie**: De code begint met het aanmaken van de database met de naam `MagazijnDatabase`. Als de database al bestaat, wordt deze overgeslagen.

    ```sql
    CREATE DATABASE IF NOT EXISTS MagazijnDatabase;
    USE MagazijnDatabase;
    ```

2. **Rekken Tabel**: Er wordt een tabel `Rek` gemaakt om informatie over de rekken op te slaan, waaronder ID, naam, aantal producten, maximale capaciteit en of het rek leeg is.

    ```sql
    CREATE TABLE IF NOT EXISTS Rek (
        RekID INT PRIMARY KEY,
        RekName VARCHAR(255) NOT NULL,
        AantalProducten INT DEFAULT 0,
        MaxAantalProducten INT DEFAULT 10,
        IsEmpty BOOLEAN DEFAULT TRUE
    );
    ```

3. **Producten Tabel**: Er wordt een tabel `Product` aangemaakt om informatie over de producten op te slaan, waaronder ID, naam, rek-ID, voorraad, minimumvoorraad en of het product leeg is. Er is ook een externe sleutel (FOREIGN KEY) naar de `Rek`-tabel.

    ```sql
    CREATE TABLE Product (
        ProductID INT PRIMARY KEY,
        ProductNaam VARCHAR(255),
        RekID INT,
        Stock INT DEFAULT 0,
        MinVoorraad INT DEFAULT 5,
        IsEmpty BOOLEAN DEFAULT TRUE,
        FOREIGN KEY (RekID) REFERENCES Rek(RekID)
    );
    ```

4. **Procedure VoegProductToe**: Een opgeslagen procedure wordt gemaakt om het toevoegen van producten aan de database te vergemakkelijken.

    ```sql
    DELIMITER //
    CREATE PROCEDURE VoegProductToe(
        IN p_ProductID INT,
        IN p_ProductNaam VARCHAR(255),
        IN p_RekID INT,
        IN p_Stock INT
    )
    ```

## Orderdatabase

1. **Databasecreatie voor Orders**: Een aparte database genaamd `OrderDatabase` wordt aangemaakt voor het beheren van orders.

    ```sql
    CREATE DATABASE IF NOT EXISTS OrderDatabase;
    USE OrderDatabase;
    ```

2. **Ordertabel**: Er wordt een tabel `OrderTabel` gemaakt om informatie over de orders op te slaan, waaronder order-ID, rek-ID, product-ID, productnaam en hoeveelheid. Er zijn externe sleutels naar zowel de `Rek`- als de `Product`-tabel.

    ```sql
    CREATE TABLE OrderTabel (
        OrderID INT PRIMARY KEY,
        RekID INT,
        ProductID INT,
        ProductNaam VARCHAR(255),
        Hoeveelheid INT,
        FOREIGN KEY (RekID) REFERENCES Rek(RekID),
        FOREIGN KEY (ProductID) REFERENCES Product(ProductID)
    );


<br/>
<br/>
<br/>

# php

## Databaseconfiguratie

De code maakt gebruik van vijf variabelen voor het configureren van de databaseverbinding:

- `$servername`: De naam van de server.
- `$username`: De gebruikersnaam voor de databaseverbinding.
- `$password`: Het wachtwoord voor de databaseverbinding.
- `$dbname_rek`: De naam van de database voor rek gegevens.
- `$dbname_order`: De naam van de database voor order gegevens.


## Functies

### Functie om een rek toe te voegen

- **Naam**: `voegRekToe`
- **Parameters**: `$rekID` (ID van het rek), `$rekNaam` (naam van het rek)
- **Functionaliteit**: Voegt een nieuw rek toe aan de database met opgegeven ID en naam.

### Functie om een product toe te voegen

- **Naam**: `voegProductToe`
- **Parameters**: `$productID` (ID van het product), `$productNaam` (naam van het product), `$rekID` (ID van het rek), `$Stock` (voorraad van het product), `$MinVoorraad` (minimumvoorraad)
- **Functionaliteit**: Voegt een nieuw product toe aan het opgegeven rek. Beheert automatisch het toevoegen van producten aan andere rekken als het huidige rek vol is.

### Functie om een order toe te voegen

- **Naam**: `plaatsOrder`
- **Parameters**: `$orderID` (ID van de order), `$rekID` (ID van het rek), `$productID` (ID van het product), `$hoeveelheid` (hoeveelheid van het product in de order)
- **Functionaliteit**: Plaatst een nieuwe order in de database. Werkt de voorraad van het product bij en besteld het product automatisch als de voorraad onder de minimumvoorraad komt.

## Auteurs
- **[Bo Mengels](https://github.com/12003586)** - _CONTRIBUTOR_ - 




