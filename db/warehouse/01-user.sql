SET NAMES utf8mb4;

CREATE USER IF NOT EXISTS 'warehouse_writer'@'%' IDENTIFIED BY 'warehouse_writer';

GRANT ALL PRIVILEGES
ON customer_data_warehouse.*
TO 'warehouse_writer'@'%';
