USE customer_sharding_by_continent;

SELECT * FROM continents ORDER BY continent_id;

SELECT
    c.customer_id,
    c.customer_name,
    c.country,
    co.continent_name
FROM customers c
JOIN continents co ON co.continent_id = c.continent_id
ORDER BY c.customer_id;

SELECT
    i.invoice_id,
    c.customer_name,
    i.invoice_date,
    i.amount
FROM invoices i
JOIN customers c ON c.customer_id = i.customer_id
ORDER BY i.invoice_id;

SELECT SUM(amount) AS north_america_total
FROM invoices;
