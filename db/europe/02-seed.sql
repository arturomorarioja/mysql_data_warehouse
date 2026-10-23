SET NAMES utf8mb4;
USE customer_sharding_by_continent;

INSERT INTO continents (continent_id, continent_name)
VALUES
    (1, 'Europe'),
    (2, 'North America'),
    (3, 'Asia');

INSERT INTO customers (customer_name, country, continent_id)
VALUES
    ('Nordic Design ApS', 'Denmark', 1),
    ('Iberia Software SL', 'Spain', 1),
    ('Rhein Analytics GmbH', 'Germany', 1),
    ('Lumière Conseil SAS', 'France', 1);

INSERT INTO invoices (customer_id, invoice_date, amount)
VALUES
    (1, '2026-01-15', 1250.00),
    (1, '2026-03-12', 890.50),
    (2, '2026-02-08', 1475.25),
    (2, '2026-05-19', 620.00),
    (3, '2026-01-28', 2140.75),
    (3, '2026-06-03', 1765.40),
    (4, '2026-04-11', 980.00),
    (4, '2026-07-22', 1325.60);
