-- Create the database
CREATE DATABASE IF NOT EXISTS MagazijnDatabase;
USE MagazijnDatabase;

-- Create the racks table
CREATE TABLE IF NOT EXISTS Rek (
    RekID INT PRIMARY KEY,
    RekName VARCHAR(255) NOT NULL ,
    AantalProducten INT DEFAULT 0,
    MaxAantalProducten INT DEFAULT 10,
    IsEmpty BOOLEAN DEFAULT TRUE
);

/*
INSERT INTO Rek (RekID, RekNaam) VALUES 
(1, 'Rek A'),
(2, 'Rek B'),
(3, 'Rek C');
*/

CREATE TABLE Product (
    ProductID INT PRIMARY KEY,
    ProductNaam VARCHAR(255),
    RekID INT,
    Stock INT DEFAULT 0,
    IsEmpty BOOLEAN DEFAULT TRUE,
    FOREIGN KEY (RekID) REFERENCES Rek(RekID)
);

DELIMITER //
CREATE PROCEDURE VoegProductToe(
    IN p_ProductID INT,
    IN p_ProductNaam VARCHAR(255),
    IN p_RekID INT,
    IN p_Stock INT
)

BEGIN
    -- Voeg het product toe aan de Product-tabel
    INSERT INTO Product (ProductID, ProductNaam, RekID, Stock, IsEmpty) VALUES (p_ProductID, p_ProductNaam, p_RekID, p_Stock);

    -- Werk het aantal producten in het rek bij
    UPDATE Rek SET AantalProducten = AantalProducten + 1, IsEmpty = FALSE WHERE RekID = p_RekID;
END //
DELIMITER ;

/*
CALL VoegProductToe(106, 'Product 6', 1, 10);
CALL VoegProductToe(107, 'Product 7', 1, 15);
*/

-- Creëer de database voor orders
CREATE DATABASE IF NOT EXISTS OrderDatabase;
USE OrderDatabase;

-- Creëer de tabel voor orders
CREATE TABLE OrderTabel (
    OrderID INT PRIMARY KEY,
    RekID INT,
    ProductID INT,
    ProductNaam VARCHAR(255),
    Stock INT,
    FOREIGN KEY (RekID) REFERENCES Rek(RekID),
    FOREIGN KEY (ProductID) REFERENCES Product(ProductID)
);

/*
-- Plaats een order voor 3 stuks van Product 9 in Rek B
INSERT INTO OrderTabel (OrderID, RekID, ProductID, ProductNaam, Stock)
SELECT 1, RekID, ProductID, ProductNaam, 3
FROM Product
WHERE ProductID = 109;
*/