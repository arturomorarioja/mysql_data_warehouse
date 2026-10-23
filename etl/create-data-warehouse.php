<?php
declare(strict_types=1);

require_once __DIR__ . '/config.php';
require_once __DIR__ . '/Database.php';
require_once __DIR__ . '/SourceShard.php';
require_once __DIR__ . '/Warehouse.php';

try {
    if (!extension_loaded('pdo_mysql')) {
        throw new RuntimeException(
            'The PHP pdo_mysql extension is not enabled.'
        );
    }

    // ------------------------------------------------------------
    // 1. Connect to the source shards
    // ------------------------------------------------------------

    $sources = [];

    foreach (sourceConfigs() as $config) {
        $connection = Database::connect(
            DB_HOST,
            $config['port'],
            SOURCE_DATABASE,
            SOURCE_USER,
            SOURCE_PASSWORD
        );

        $sources[] = new SourceShard(
            $config['name'],
            $connection
        );

        echo "Connected to {$config['name']} shard." . PHP_EOL;
    }

    // ------------------------------------------------------------
    // 2. Create the empty warehouse
    // ------------------------------------------------------------

    $warehouse = new Warehouse(
        __DIR__ . '/sql/create-warehouse.sql'
    );

    echo 'Warehouse schema created.' . PHP_EOL;

    // ------------------------------------------------------------
    // 3. Extract
    // ------------------------------------------------------------

    $continents = [];
    $customers = [];
    $invoices = [];

    foreach ($sources as $source) {
        $source->beginReadOnlySnapshot();
    }

    try {
        foreach ($sources as $source) {
            echo "Extracting {$source->getName()}..." . PHP_EOL;

            // continents is replicated on every shard.
            // Keep one copy and check that all shards agree.
            foreach ($source->getContinents() as $continent) {
                $continentId = (int)$continent['continent_id'];

                if (
                    isset($continents[$continentId]) &&
                    $continents[$continentId]['continent_name']
                        !== $continent['continent_name']
                ) {
                    throw new RuntimeException(
                        "Inconsistent continent {$continentId} "
                        . "on {$source->getName()}."
                    );
                }

                $continents[$continentId] = $continent;
            }

            array_push(
                $customers,
                ...$source->getCustomers()
            );

            array_push(
                $invoices,
                ...$source->getInvoices()
            );
        }

        ksort($continents);

        // --------------------------------------------------------
        // 4. Load
        // --------------------------------------------------------

        $warehouse->beginLoad();

        try {
            $warehouse->insertContinents(
                array_values($continents)
            );

            $warehouse->insertCustomers($customers);
            $warehouse->insertInvoices($invoices);

            $warehouse->commitLoad();
        } catch (Throwable $e) {
            $warehouse->rollbackLoad();
            throw $e;
        }
    } finally {
        foreach ($sources as $source) {
            $source->endReadOnlySnapshot();
        }
    }

    // ------------------------------------------------------------
    // 5. Verify / report
    // ------------------------------------------------------------

    $counts = $warehouse->getCounts();

    echo PHP_EOL . 'Warehouse contents:' . PHP_EOL;
    echo "  Continents: {$counts['continents']}" . PHP_EOL;
    echo "  Customers:  {$counts['customers']}" . PHP_EOL;
    echo "  Invoices:   {$counts['invoices']}" . PHP_EOL;

    echo PHP_EOL . 'Customers by continent:' . PHP_EOL;

    foreach ($warehouse->getCustomersByContinent() as $row) {
        echo sprintf(
            "  %-15s %d%s",
            $row['continent_name'],
            $row['customer_count'],
            PHP_EOL
        );
    }

    echo PHP_EOL
        . 'Data warehouse created successfully.'
        . PHP_EOL;

} catch (Throwable $e) {
    fwrite(
        STDERR,
        PHP_EOL . 'ERROR: ' . $e->getMessage() . PHP_EOL
    );

    exit(1);
}
