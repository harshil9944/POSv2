<?php
$constant = 'POS';
$module = 'pos';
defined('POS_MIGRATION_PATH')    OR define('POS_MIGRATION_PATH', MODULES_PATH . '/'.$module.'/migrations/');
defined('POS_MIGRATION_TABLE')   OR define('POS_MIGRATION_TABLE', 'mig_'.$module);

defined('SYS_PAYMENT_METHOD_TABLE')     OR  define('SYS_PAYMENT_METHOD_TABLE', 'sys_payment_method');

defined('ORDER_TABLE')                  OR  define('ORDER_TABLE', 'ord_order');
defined('ORDER_ITEM_TABLE')             OR  define('ORDER_ITEM_TABLE', 'ord_order_item');

defined('ORDER_PAYMENT_TABLE')          OR  define('ORDER_PAYMENT_TABLE', 'ord_payment');

defined('ORDER_SOURCE_TABLE')           OR  define('ORDER_SOURCE_TABLE', 'sys_order_source');

defined('POS_SESSION_TABLE')            OR  define('POS_SESSION_TABLE', 'pos_session');

defined('POS_THUMB_WIDTH')              OR  define('POS_THUMB_WIDTH', 250);
defined('POS_THUMB_HEIGHT')             OR  define('POS_THUMB_HEIGHT', 250);

defined('POS_DETAIL_WIDTH')             OR  define('POS_DETAIL_WIDTH', 500);
defined('POS_DETAIL_HEIGHT')            OR  define('POS_DETAIL_HEIGHT', 500);

defined('POS_CASH_PAYMENT_METHOD_ID')   OR  define('POS_CASH_PAYMENT_METHOD_ID', 1);
defined('POS_CARD_PAYMENT_METHOD_ID')   OR  define('POS_CARD_PAYMENT_METHOD_ID', 3);

defined('ORDER_REF')                    OR  define('ORDER_REF','ord');

defined('POS_IGNORE_PAYMENT_IDS')       OR  define('POS_IGNORE_PAYMENT_IDS',[4]);
