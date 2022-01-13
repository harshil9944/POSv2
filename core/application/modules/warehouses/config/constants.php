<?php
$constant = 'WAREHOUSE';
$module = 'warehouses';
defined($constant.'_MIGRATION_PATH')     OR define($constant.'_MIGRATION_PATH', MODULES_PATH . '/'.$module.'/migrations/');
defined($constant.'_MIGRATION_TABLE')    OR define($constant.'_MIGRATION_TABLE', 'mig_'.$module);

defined('WAREHOUSE_TABLE')           OR  define('WAREHOUSE_TABLE', 'sys_warehouse');
defined('WAREHOUSE_SHELF_TABLE')     OR  define('WAREHOUSE_SHELF_TABLE', 'sys_wh_shelf');
defined('WAREHOUSE_BATCH_TABLE')     OR  define('WAREHOUSE_BATCH_TABLE', 'sys_wh_batch');
