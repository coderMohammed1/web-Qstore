CREATE DATABASE IF NOT EXISTS Qshop;
USE Qshop;

ALTER DATABASE Qshop
CHARACTER SET utf8mb4
COLLATE utf8mb4_general_ci;

-- Initialize the tables
-- Roles will be either 's' for seller or 'c' for customer
CREATE TABLE users (
    ID INT PRIMARY KEY AUTO_INCREMENT,
    First_Name VARCHAR(40) NOT NULL,
    Last_Name VARCHAR(40) NOT NULL,
    roles CHAR(1) NOT NULL, -- 's' for seller, 'c' for customer
    birthdate DATE NOT NULL,
    email VARCHAR(70) UNIQUE,
    password TEXT NOT NULL,
    token VARCHAR(500) UNIQUE,
    isactive INT DEFAULT 0
) AUTO_INCREMENT=1;

CREATE TABLE cart (
    ID INT PRIMARY KEY AUTO_INCREMENT,
    total DECIMAL(10, 2) NOT NULL CHECK (total >= 0.00),
    user_id INT NOT NULL,
    FOREIGN KEY (user_id) REFERENCES users(ID)
) AUTO_INCREMENT=1;

CREATE TABLE orders (
    ID INT PRIMARY KEY AUTO_INCREMENT,
    price DECIMAL(10,2) NOT NULL CHECK (price >= 0.00),
    odate TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    user_id INT NOT NULL,
    FOREIGN KEY (user_id) REFERENCES users(ID)
) AUTO_INCREMENT=1;

CREATE TABLE product (
    ID INT PRIMARY KEY AUTO_INCREMENT,
    p_name VARCHAR(70) NOT NULL,
    price DECIMAL(10,2) NOT NULL CHECK (price >= 0.00),
    Manfacturer VARCHAR(50),
    seller INT NOT NULL,
    description TEXT NOT NULL,
    img MEDIUMBLOB NOT NULL,
    type VARCHAR(15) NOT NULL,
    quantity INT NOT NULL DEFAULT 1 CHECK (quantity >= 0),
    FOREIGN KEY (seller) REFERENCES users(ID)
) AUTO_INCREMENT=1;

CREATE INDEX pname ON product(p_name);
CREATE INDEX man ON product(Manfacturer);
ALTER TABLE product 
CHANGE img img VARCHAR(255) NOT NULL;

-- Many-to-many relationships
CREATE TABLE cart_products (
    ID INT PRIMARY KEY AUTO_INCREMENT,
    cart INT NOT NULL,
    product INT NOT NULL,
    quantity INT NOT NULL DEFAULT 1,
    FOREIGN KEY (cart) REFERENCES cart(ID),
    FOREIGN KEY (product) REFERENCES product(ID)
) AUTO_INCREMENT=1;

CREATE TABLE Order_Product (
    order_id INT NOT NULL,
    product INT NOT NULL,
    quantity INT NOT NULL DEFAULT 1,
    FOREIGN KEY (product) REFERENCES product(ID),
    FOREIGN KEY (order_id) REFERENCES orders(ID)
) AUTO_INCREMENT=1;

CREATE TABLE customer (
    cust_id INT UNIQUE NOT NULL,
    city VARCHAR(55),
    street VARCHAR(55),
    country VARCHAR(50),
    cart INT,
    FOREIGN KEY (cust_id) REFERENCES users(ID),
    FOREIGN KEY (cart) REFERENCES cart(ID)
);

CREATE TABLE seller (
    seller_id INT UNIQUE NOT NULL,
    total_sales DECIMAL(15,3) DEFAULT 0.000,
    FOREIGN KEY (seller_id) REFERENCES users(ID)
);

CREATE TABLE mycustomers (
    ID INT UNIQUE AUTO_INCREMENT,
    cust_id INT NOT NULL,
    sid INT NOT NULL,
    FOREIGN KEY (cust_id) REFERENCES users(ID),
    FOREIGN KEY (sid) REFERENCES users(ID)
) AUTO_INCREMENT=1;
--------------------------------------------------
-- reviews table
CREATE TABLE reviews(
	ID INT PRIMARY KEY AUTO_INCREMENT,
    review TEXT NOT NULL,
    rate INT NOT NULL,
    user_id INT NOT NULL,
    product_id INT NOT NULL
);

ALTER TABLE reviews
ADD CONSTRAINT fk_revU
FOREIGN KEY (user_id) REFERENCES users(ID);

ALTER TABLE reviews
ADD CONSTRAINT fk_revP
FOREIGN KEY (product_id) REFERENCES product(ID);

--------------------------------------------

select * from product;