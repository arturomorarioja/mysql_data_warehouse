SET NAMES utf8mb4;

DROP DATABASE IF EXISTS customer_sharding_by_continent;
CREATE DATABASE customer_sharding_by_continent
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_0900_ai_ci;
USE customer_sharding_by_continent;

CREATE TABLE continents (
    continent_id INT PRIMARY KEY,
    continent_name VARCHAR(50) NOT NULL UNIQUE
);

CREATE TABLE customers (
    customer_id INT PRIMARY KEY AUTO_INCREMENT,
    customer_name VARCHAR(120) NOT NULL,
    country VARCHAR(80) NOT NULL,
    continent_id INT NOT NULL,
    CONSTRAINT fk_customers_continent
        FOREIGN KEY (continent_id)
        REFERENCES continents(continent_id)
);

CREATE TABLE invoices (
    invoice_id INT PRIMARY KEY AUTO_INCREMENT,
    customer_id INT NOT NULL,
    invoice_date DATE NOT NULL,
    amount DECIMAL(10,2) NOT NULL,
    CONSTRAINT fk_invoices_customer
        FOREIGN KEY (customer_id)
        REFERENCES customers(customer_id)
);


CREATE USER IF NOT EXISTS 'warehouse_reader'@'%' IDENTIFIED BY 'warehouse_reader';
GRANT SELECT ON customer_sharding_by_continent.* TO 'warehouse_reader'@'%';
