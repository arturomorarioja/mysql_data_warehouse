USE customer_sharding_by_continent;

-- The complete continents reference table exists on every shard.
SELECT * FROM continents ORDER BY continent_id;

-- This shard contains only European customers.
SELECT
    c.customer_id,
    c.customer_name,
    c.country,
    co.continent_name
FROM customers c
JOIN continents co ON co.continent_id = c.continent_id
ORDER BY c.customer_id;

-- Invoices remain local to the customers stored on this shard.
SELECT
    i.invoice_id,
    c.customer_name,
    i.invoice_date,
    i.amount
FROM invoices i
JOIN customers c ON c.customer_id = i.customer_id
ORDER BY i.invoice_id;

-- Total invoiced amount on this shard.
SELECT SUM(amount) AS europe_total
FROM invoices;
