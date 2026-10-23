<?php
declare(strict_types=1);

final class Warehouse
{
    private PDO $connection;

    public function __construct(
        string $ddlFile
    ) {
        $serverConnection = Database::connect(
            DB_HOST,
            WAREHOUSE_PORT,
            null,
            WAREHOUSE_USER,
            WAREHOUSE_PASSWORD
        );

        $ddl = file_get_contents($ddlFile);

        if ($ddl === false) {
            throw new RuntimeException(
                "Cannot read DDL file: {$ddlFile}"
            );
        }

        $serverConnection->exec($ddl);

        $this->connection = Database::connect(
            DB_HOST,
            WAREHOUSE_PORT,
            WAREHOUSE_DATABASE,
            WAREHOUSE_USER,
            WAREHOUSE_PASSWORD
        );
    }

    public function beginLoad(): void
    {
        $this->connection->beginTransaction();
    }

    public function commitLoad(): void
    {
        $this->connection->commit();
    }

    public function rollbackLoad(): void
    {
        if ($this->connection->inTransaction()) {
            $this->connection->rollBack();
        }
    }

    public function insertContinents(array $continents): void
    {
        $statement = $this->connection->prepare(
            'INSERT INTO continents (
                continent_id,
                continent_name
             )
             VALUES (
                :continent_id,
                :continent_name
             )'
        );

        foreach ($continents as $continent) {
            $statement->execute([
                'continent_id' => $continent['continent_id'],
                'continent_name' => $continent['continent_name'],
            ]);
        }
    }

    public function insertCustomers(array $customers): void
    {
        $statement = $this->connection->prepare(
            'INSERT INTO customers (
                continent_id,
                customer_id,
                customer_name,
                country
             )
             VALUES (
                :continent_id,
                :customer_id,
                :customer_name,
                :country
             )'
        );

        foreach ($customers as $customer) {
            $statement->execute([
                'continent_id' => $customer['continent_id'],
                'customer_id' => $customer['customer_id'],
                'customer_name' => $customer['customer_name'],
                'country' => $customer['country'],
            ]);
        }
    }

    public function insertInvoices(array $invoices): void
    {
        $statement = $this->connection->prepare(
            'INSERT INTO invoices (
                continent_id,
                invoice_id,
                customer_id,
                invoice_date,
                amount
             )
             VALUES (
                :continent_id,
                :invoice_id,
                :customer_id,
                :invoice_date,
                :amount
             )'
        );

        foreach ($invoices as $invoice) {
            $statement->execute([
                'continent_id' => $invoice['continent_id'],
                'invoice_id' => $invoice['invoice_id'],
                'customer_id' => $invoice['customer_id'],
                'invoice_date' => $invoice['invoice_date'],
                'amount' => $invoice['amount'],
            ]);
        }
    }

    public function getCounts(): array
    {
        return [
            'continents' => (int)$this->connection
                ->query('SELECT COUNT(*) FROM continents')
                ->fetchColumn(),

            'customers' => (int)$this->connection
                ->query('SELECT COUNT(*) FROM customers')
                ->fetchColumn(),

            'invoices' => (int)$this->connection
                ->query('SELECT COUNT(*) FROM invoices')
                ->fetchColumn(),
        ];
    }

    public function getCustomersByContinent(): array
    {
        return $this->connection
            ->query(
                'SELECT
                    co.continent_name,
                    COUNT(*) AS customer_count
                 FROM customers c
                 INNER JOIN continents co
                    ON co.continent_id = c.continent_id
                 GROUP BY
                    co.continent_id,
                    co.continent_name
                 ORDER BY co.continent_name'
            )
            ->fetchAll();
    }
}
