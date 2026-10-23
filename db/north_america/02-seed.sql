SET NAMES utf8mb4;
USE customer_sharding_by_continent;

INSERT INTO continents (continent_id, continent_name)
VALUES
    (1, 'Europe'),
    (2, 'North America'),
    (3, 'Asia');

INSERT INTO customers (customer_name, country, continent_id)
VALUES
    ('Pacific Systems Inc', 'United States', 2),
    ('Maple Data Ltd', 'Canada', 2),
    ('Monterrey Cloud SA', 'Mexico', 2),
    ('Atlantic Retail Corp', 'United States', 2);

INSERT INTO invoices (customer_id, invoice_date, amount)
VALUES
    (1, '2026-01-10', 1840.00),
    (1, '2026-03-25', 760.25),
    (2, '2026-02-14', 1125.50),
    (2, '2026-05-30', 2050.00),
    (3, '2026-01-21', 695.75),
    (3, '2026-06-18', 1580.90),
    (4, '2026-04-06', 2435.00),
    (4, '2026-07-09', 875.40);
