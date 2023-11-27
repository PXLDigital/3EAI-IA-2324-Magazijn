-- xWY%Es2$Wv*YhW
-- C:\Users\bomen\Documenten\School\PXL\3EAI\IndustrialAutomation\Industry4_0\test.sql

-- Create the database
CREATE DATABASE IF NOT EXISTS StoreDatabase;
USE StoreDatabase;

-- Create the racks table
CREATE TABLE IF NOT EXISTS Racks (
    RackID INT PRIMARY KEY,
    RackName VARCHAR(255) NOT NULL
);

SELECT RackID FROM Racks;

-- Create a stored procedure to add multiple racks
DELIMITER //
CREATE PROCEDURE AddRacks(IN numberOfRacks INT)
BEGIN
    DECLARE counter INT DEFAULT 1;

    WHILE counter <= numberOfRacks DO
        INSERT INTO Racks (RackID, RackName) VALUES (CONCAT('Rack', counter));
        SET counter = counter + 1;
    END WHILE;
END //
DELIMITER ;

-- Add 3 racks
CALL AddRacks(3);

-- Create the products table
CREATE TABLE IF NOT EXISTS Products (
    ProductID INT PRIMARY KEY,
    ProductName VARCHAR(255) NOT NULL,
    RackID INT,
    FOREIGN KEY (RackID) REFERENCES Racks(RackID)
);

-- Create a stored procedure to add a new product
DELIMITER //
CREATE PROCEDURE AddProduct(IN newProductName VARCHAR(255), IN newRackID INT)
BEGIN
    INSERT INTO Products (ProductName, RackID) VALUES (newProductName, newRackID);
END //
DELIMITER ;



-- Insert sample data into the Products table
INSERT INTO Products (ProductID, ProductName, RackID) VALUES
(101, 'Staal', 1),
(102, 'Laptop', 1),
(103, 'T-shirt', 2),
(104, 'Jeans', 2),
(105, 'Cookware Set', 3),
(106, 'Table Lamp', 3);



/*
-- Add a new product
CALL AddProduct('Running Shoes', 4);
*/