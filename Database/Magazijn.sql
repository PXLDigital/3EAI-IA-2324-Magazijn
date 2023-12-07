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


CREATE TABLE Product (
    ProductID INT PRIMARY KEY,
    ProductNaam VARCHAR(255),
    RekID INT,
    Stock INT DEFAULT 0,
    MinVoorraad INT DEFAULT 5,
    IsEmpty BOOLEAN DEFAULT TRUE,
    FOREIGN KEY (RekID) REFERENCES Rek(RekID)
);


-- Creëer de database voor orders
CREATE DATABASE IF NOT EXISTS OrderDatabase;
USE OrderDatabase;

-- Creëer de tabel voor orders
CREATE TABLE OrderTabel (
    OrderID INT PRIMARY KEY,
    RekID INT,
    ProductID INT,
    ProductNaam VARCHAR(255),
    Hoeveelheid INT,
    FOREIGN KEY (RekID) REFERENCES Rek(RekID),
    FOREIGN KEY (ProductID) REFERENCES Product(ProductID)
);



    