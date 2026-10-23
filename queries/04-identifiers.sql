USE customer_sharding_by_continent;

-- Run this query on all three shards.
-- The same local IDs exist on different physical servers.
SELECT
    c.customer_id,
    c.customer_name,
    i.invoice_id,
    i.amount
FROM customers c
JOIN invoices i ON i.customer_id = c.customer_id
ORDER BY c.customer_id, i.invoice_id;

-- This is intentional. customer_id and invoice_id are only locally unique.
-- A later consolidation exercise must reconcile these identifiers across shards.
