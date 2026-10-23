SET NAMES utf8mb4;
USE customer_sharding_by_continent;

INSERT INTO continents (continent_id, continent_name)
VALUES
    (1, 'Europe'),
    (2, 'North America'),
    (3, 'Asia');

INSERT INTO customers (customer_name, country, continent_id)
VALUES
    ('Sakura Digital KK', 'Japan', 3),
    ('Lion City Labs Pte Ltd', 'Singapore', 3),
    ('Bharat Insights Pvt Ltd', 'India', 3),
    ('Han River Technologies Co Ltd', 'South Korea', 3);

INSERT INTO invoices (customer_id, invoice_date, amount)
VALUES
    (1, '2026-01-18', 1320.00),
    (1, '2026-03-14', 920.80),
    (2, '2026-02-26', 1685.25),
    (2, '2026-05-08', 740.00),
    (3, '2026-01-31', 2280.50),
    (3, '2026-06-27', 1195.35),
    (4, '2026-04-17', 1540.00),
    (4, '2026-07-13', 2010.65);
