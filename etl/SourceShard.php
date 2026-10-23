<?php
declare(strict_types=1);

final class SourceShard
{
    public function __construct(
        private string $name,
        private PDO $connection
    ) {
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function beginReadOnlySnapshot(): void
    {
        $this->connection->exec(
            'SET TRANSACTION ISOLATION LEVEL REPEATABLE READ'
        );

        $this->connection->exec(
            'START TRANSACTION READ ONLY'
        );
    }

    public function endReadOnlySnapshot(): void
    {
        if ($this->connection->inTransaction()) {
            // No source data is changed, so rollback simply ends the snapshot.
            $this->connection->rollBack();
        }
    }

    public function getContinents(): array
    {
        return $this->connection
            ->query(
                'SELECT continent_id, continent_name
                 FROM continents
                 ORDER BY continent_id'
            )
            ->fetchAll();
    }

    public function getCustomers(): array
    {
        return $this->connection
            ->query(
                'SELECT
                    continent_id,
                    customer_id,
                    customer_name,
                    country
                 FROM customers
                 ORDER BY customer_id'
            )
            ->fetchAll();
    }

    public function getInvoices(): array
    {
        /*
         * continent_id is not stored in invoices.
         * It is obtained from the owning customer for the warehouse key.
         */
        return $this->connection
            ->query(
                'SELECT
                    c.continent_id,
                    i.invoice_id,
                    i.customer_id,
                    i.invoice_date,
                    i.amount
                 FROM invoices i
                 INNER JOIN customers c
                    ON c.customer_id = i.customer_id
                 ORDER BY i.invoice_id'
            )
            ->fetchAll();
    }
}
