<?php
$module = 'orders';
defined('ORDER_MIGRATION_PATH')         OR  define('ORDER_MIGRATION_PATH', MODULES_PATH . '/'.$module.'/migrations/');
defined('ORDER_MIGRATION_TABLE')        OR  define('ORDER_MIGRATION_TABLE', 'mig_'.$module);

defined('ORDER_TABLE')                  OR  define('ORDER_TABLE', 'ord_order');
defined('ORDER_ITEM_TABLE')             OR  define('ORDER_ITEM_TABLE', 'ord_order_item');
defined('ORDER_ITEM_ADDON_TABLE')       OR  define('ORDER_ITEM_ADDON_TABLE', 'ord_order_item_addon');
defined('ORDER_ITEM_NOTES_TABLE')       OR  define('ORDER_ITEM_NOTES_TABLE', 'ord_order_item_note');
defined('ORDER_ADDRESS_TABLE')          OR  define('ORDER_ADDRESS_TABLE', 'ord_address');

//Split
defined('ORDER_SPLIT_TABLE')            OR  define('ORDER_SPLIT_TABLE', 'ord_split');
defined('ORDER_SPLIT_ITEM_TABLE')       OR  define('ORDER_SPLIT_ITEM_TABLE', 'ord_split_item');
defined('ORDER_SPLIT_PAYMENT_TABLE')    OR  define('ORDER_SPLIT_PAYMENT_TABLE', 'ord_split_payment');

defined('ORDER_PAYMENT_TABLE')          OR  define('ORDER_PAYMENT_TABLE', 'ord_payment');
defined('ORDER_PAYMENT_DESC_TABLE')     OR  define('ORDER_PAYMENT_DESC_TABLE', 'ord_payment_description');
defined( 'ORDER_CLOVER_PAYMENT' ) OR define( 'ORDER_CLOVER_PAYMENT', 'ord_clover_payment' );
defined( 'ORDER_CLOVER_REFUND_PAYMENT' ) OR define( 'ORDER_CLOVER_REFUND_PAYMENT', 'ord_clover_refund_payment' );

defined('ORDER_SOURCE_TABLE')           OR  define('ORDER_SOURCE_TABLE', 'sys_order_source');

defined('ORDER_SOURCE_PANEL_ID')        OR  define('ORDER_SOURCE_PANEL_ID', 1);

defined('ORDER_SPICE_LEVELS')           OR  define('ORDER_SPICE_LEVELS', "ENUM('mild','medium','hot')");
defined('ORDER_SPICE_LEVEL_DEFAULT')    OR  define('ORDER_SPICE_LEVEL_DEFAULT', "medium");

defined('ORDER_PROMOTION_TABLE')        OR  define('ORDER_PROMOTION_TABLE', 'ord_promotion');

defined('ORDER_PAYMENT_REFUND_TABLE')   OR  define('ORDER_PAYMENT_REFUND_TABLE', 'ord_payment_refund');
defined('ORDER_PRINT_QUEUE_TABLE')      OR  define('ORDER_PRINT_QUEUE_TABLE', 'ord_print_queue');
defined('WEB_SESSION_TABLE')            OR  define('WEB_SESSION_TABLE', 'web_session');

defined('CONTACT_CUSTOMER_TABLE')                   OR  define('CONTACT_CUSTOMER_TABLE', 'con_customer');
defined('WEB_PAYPAL_PAYMENT_METHOD_ID') OR  define('WEB_PAYPAL_PAYMENT_METHOD_ID', 4);
defined('SO_SOURCE_WEB_ID')             OR  define('SO_SOURCE_WEB_ID', 3);
defined('WEB_SESSION_ID')               OR  define('WEB_SESSION_ID', 9999999);
defined('WEB_SALESPERSON_ID')           OR  define('WEB_SALESPERSON_ID', 9999999);

defined('ORDER_REF')                    OR  define('ORDER_REF','ord');
defined('ITEM_TABLE')           OR  define('ITEM_TABLE', 'itm_item');
defined('ITEM_CATEGORY_TABLE')  OR  define('ITEM_CATEGORY_TABLE', 'itm_category');