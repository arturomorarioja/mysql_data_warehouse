DROP DATABASE IF EXISTS customer_data_warehouse;

CREATE DATABASE customer_data_warehouse
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_0900_ai_ci;

USE customer_data_warehouse;

CREATE TABLE continents (
    continent_id INT PRIMARY KEY,
    continent_name VARCHAR(50) NOT NULL UNIQUE
);

CREATE TABLE customers (
    continent_id INT NOT NULL,
    customer_id INT NOT NULL,
    customer_name VARCHAR(120) NOT NULL,
    country VARCHAR(80) NOT NULL,

    PRIMARY KEY (continent_id, customer_id),

    CONSTRAINT fk_dw_customers_continent
        FOREIGN KEY (continent_id)
        REFERENCES continents(continent_id)
);

CREATE TABLE invoices (
    continent_id INT NOT NULL,
    invoice_id INT NOT NULL,
    customer_id INT NOT NULL,
    invoice_date DATE NOT NULL,
    amount DECIMAL(10,2) NOT NULL,

    PRIMARY KEY (continent_id, invoice_id),

    CONSTRAINT fk_dw_invoices_customer
        FOREIGN KEY (continent_id, customer_id)
        REFERENCES customers(continent_id, customer_id)
);
