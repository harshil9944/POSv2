<?php
$module = 'reports';
defined('REPORT_MIGRATION_PATH')  OR  define('REPORT_MIGRATION_PATH', MODULES_PATH . '/'.$module.'/migrations/');
defined('REPORT_MIGRATION_TABLE') OR  define('REPORT_MIGRATION_TABLE', 'mig_'.$module);

defined('ORDER_TABLE')                  OR  define('ORDER_TABLE', 'ord_order');
defined('ORDER_ITEM_TABLE')             OR  define('ORDER_ITEM_TABLE', 'ord_order_item');

defined('POS_SESSION_TABLE')            OR  define('POS_SESSION_TABLE', 'pos_session');
