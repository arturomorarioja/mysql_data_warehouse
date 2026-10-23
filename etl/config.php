<?php
declare(strict_types=1);

// Host
const DB_HOST = '127.0.0.1';

// Source shard ports
const EUROPE_PORT = 19409;
const NORTH_AMERICA_PORT = 19410;
const ASIA_PORT = 19411;

// Data warehouse port
const WAREHOUSE_PORT = 19412;

// Database names
const SOURCE_DATABASE = 'customer_sharding_by_continent';
const WAREHOUSE_DATABASE = 'customer_data_warehouse';

// Source credentials: read-only
const SOURCE_USER = 'warehouse_reader';
const SOURCE_PASSWORD = 'warehouse_reader';

// Warehouse credentials
const WAREHOUSE_USER = 'warehouse_writer';
const WAREHOUSE_PASSWORD = 'warehouse_writer';

function sourceConfigs(): array
{
    return [
        [
            'name' => 'Europe',
            'port' => EUROPE_PORT,
        ],
        [
            'name' => 'North America',
            'port' => NORTH_AMERICA_PORT,
        ],
        [
            'name' => 'Asia',
            'port' => ASIA_PORT,
        ],
    ];
}
