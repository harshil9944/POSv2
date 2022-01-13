<?php
$constant = 'WAPI';
$module = 'wapi';
defined($constant.'_MIGRATION_PATH')    OR define($constant.'_MIGRATION_PATH', MODULES_PATH . '/'.$module.'/migrations/');
defined($constant.'_MIGRATION_TABLE')   OR define($constant.'_MIGRATION_TABLE', 'mig_'.$module);

defined('ORDER_TABLE')                  OR  define('ORDER_TABLE', 'ord_order');
defined('ORDER_ITEM_TABLE')             OR  define('ORDER_ITEM_TABLE', 'ord_order_item');

defined('ORDER_PAYMENT_TABLE')          OR  define('ORDER_PAYMENT_TABLE', 'ord_payment');
defined('ORDER_PAYMENT_DESC_TABLE')     OR  define('ORDER_PAYMENT_DESC_TABLE', 'ord_payment_description');

defined('POS_SESSION_TABLE')            OR  define('POS_SESSION_TABLE', 'pos_session');
defined('WEB_SESSION_TABLE')            OR  define('WEB_SESSION_TABLE', 'web_session');

defined('POS_THUMB_WIDTH')              OR  define('POS_THUMB_WIDTH', 250);
defined('POS_THUMB_HEIGHT')             OR  define('POS_THUMB_HEIGHT', 250);

defined('POS_DETAIL_WIDTH')             OR  define('POS_DETAIL_WIDTH', 500);
defined('POS_DETAIL_HEIGHT')            OR  define('POS_DETAIL_HEIGHT', 500);

defined('POS_CASH_PAYMENT_METHOD_ID')   OR  define('POS_CASH_PAYMENT_METHOD_ID', 1);
defined('POS_CARD_PAYMENT_METHOD_ID')   OR  define('POS_CARD_PAYMENT_METHOD_ID', 3);
defined('WEB_PAYPAL_PAYMENT_METHOD_ID') OR  define('WEB_PAYPAL_PAYMENT_METHOD_ID', 4);

defined('OPEN_ITEM_ID')                 OR  define('OPEN_ITEM_ID', 195);
defined('OPEN_ITEM_CATEGORY_ID')        OR  define('OPEN_ITEM_CATEGORY_ID', 20);

defined('ORDER_REF')                    OR  define('ORDER_REF','ord');
