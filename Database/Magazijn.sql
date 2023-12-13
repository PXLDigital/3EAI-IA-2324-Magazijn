-- Create the database
CREATE DATABASE IF NOT EXISTS MagazijnDatabase;
USE MagazijnDatabase;

-- Create the racks table
CREATE TABLE IF NOT EXISTS Rek (
    RekID INT PRIMARY KEY,
    RekName VARCHAR(255) NOT NULL ,
    AantalProducten INT DEFAULT 0,
    MaxAantalProducten INT DEFAULT 20,
    IsEmpty BOOLEAN DEFAULT TRUE
);

CREATE TABLE Product (
    ProductID INT PRIMARY KEY,
    ProductNaam VARCHAR(255),
    RekID INT,
    Stock INT DEFAULT 0,
    MinimaleVoorraad INT DEFAULT 5, -- Minimale voorraadniveau
    IsEmpty BOOLEAN DEFAULT TRUE,
    FOREIGN KEY (RekID) REFERENCES Rek(RekID)
);

-- Creëer de tabel voor orders
CREATE TABLE OrderTabel (
    OrderID INT PRIMARY KEY,
    UserID INT,
    ProductID INT,
    ProductNaam VARCHAR(255),
    Quantity INT,
    TotalAmount INT,
    FOREIGN KEY (ProductID) REFERENCES Product(ProductID)
);
