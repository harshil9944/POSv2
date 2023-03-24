<?php
defined('TEST_ENV')                     OR  define('TEST_ENV', false);

//Development Start
defined('DEV_APPLICATION_DIR')          OR  define('DEV_APPLICATION_DIR', '../core/application');
defined('DEV_SYSTEM_DIR')               OR  define('DEV_SYSTEM_DIR', '../core/system');
defined('DEV_VIEW_DIR')                 OR  define('DEV_VIEW_DIR', '');
defined('DEV_ASSET_URL')                OR  define('DEV_ASSET_URL', 'http://assets.inntechpos2.ci/');

defined('DEV_DEFAULT_TIMEZONE')         OR  define('DEV_DEFAULT_TIMEZONE', 'Asia/Kolkata');
defined('DEV_DEFAULT_TIMEZONE_VALUE')   OR  define('DEV_DEFAULT_TIMEZONE_VALUE', '+05:30');
//Development END

//Live Start
defined('LIVE_APPLICATION_DIR')         OR  define('LIVE_APPLICATION_DIR', '../core/application');
defined('LIVE_SYSTEM_DIR')              OR  define('LIVE_SYSTEM_DIR', '../core/system');
defined('LIVE_VIEW_DIR')                OR  define('LIVE_VIEW_DIR', '');
defined('LIVE_ASSET_URL')               OR  define('LIVE_ASSET_URL', 'https://assets.v2.inntechpos.com/');

defined('LIVE_DEFAULT_TIMEZONE')        OR  define('LIVE_DEFAULT_TIMEZONE', 'America/Edmonton');
defined('LIVE_DEFAULT_TIMEZONE_VALUE')  OR  define('LIVE_DEFAULT_TIMEZONE_VALUE', '-06:00');
//Live Start

//General
defined('UPDATE_CHECK_INTERVAL')        OR  define('UPDATE_CHECK_INTERVAL', 10000);
defined('TABLET_GROUP_ID')              OR  define('TABLET_GROUP_ID', 10);
defined('ALLOW_VOID_ITEM')              OR  define('ALLOW_VOID_ITEM', false);
defined('ALLOW_RELEASE_TABLE')          OR  define('ALLOW_RELEASE_TABLE', false);
defined('ALLOW_OPEN_CASH_DRAWER')       OR  define('ALLOW_OPEN_CASH_DRAWER', false);
defined('SORT_VARIATION_BY_NAME')       OR  define('SORT_VARIATION_BY_NAME',false);
defined('ENABLE_SOURCE_SWITCH')         OR  define('ENABLE_SOURCE_SWITCH',false);
defined('START_WEB_ORDERS_WITH_SESSION')OR  define('START_WEB_ORDERS_WITH_SESSION',false);
defined('POS_LOAD_ADDON_ITEMS')         OR  define('POS_LOAD_ADDON_ITEMS', false);

//Order
defined('ORDER_METHODS')                OR  define('ORDER_METHODS', 'p,d,dine');
defined('PLAY_SOUND_ON_NEW_ORDER')      OR  define('PLAY_SOUND_ON_NEW_ORDER',false);
defined('DEFAULT_WEB_ORDER_SOUND')      OR  define('DEFAULT_WEB_ORDER_SOUND','audio1.wav');
defined('ALLOW_REFUND')                 OR  define('ALLOW_REFUND', true);
defined('ENABLE_REPEAT_ORDER')          OR  define('ENABLE_REPEAT_ORDER',true);
defined('ALLOW_CUSTOMER_GROUP')         OR  define('ALLOW_CUSTOMER_GROUP', false);
defined('ALLOW_CONVERT_CHANGE_TO_TIP')  OR  define('ALLOW_CONVERT_CHANGE_TO_TIP', false);

//Print
defined('KITCHEN_PRINTERS')             OR  define('KITCHEN_PRINTERS', [
    ['id'=>'default','value'=>"Don't Print"],
    ['id'=>'kitchen','value'=>'Main Kitchen'],
]);
defined('DEFAULT_CASHIER_PRINT')        OR  define('DEFAULT_CASHIER_PRINT',false);
defined('DEFAULT_KITCHEN_PRINT')        OR  define('DEFAULT_KITCHEN_PRINT',false);

//Gratuity
defined('ALLOW_GRATUITY')               OR  define('ALLOW_GRATUITY', false );
defined('ALLOW_GRATUITY_CHANGE')        OR  define('ALLOW_GRATUITY_CHANGE', false);
defined('GRATUITY_RATE')                OR  define('GRATUITY_RATE', 18);
defined('GRATUITY_PERSONS')             OR  define('GRATUITY_PERSONS', 7);
defined('DISPLAY_SEAT_USED_INVOICE')    OR  define('DISPLAY_SEAT_USED_INVOICE', false);

//Print Queue
defined('BROWSER_ID')                   OR  define('BROWSER_ID', null);
defined('BROWSER_ID_KEY')               OR  define('BROWSER_ID_KEY', 'unique_browser_id');
defined('PRINT_QUEUE')                  OR  define('PRINT_QUEUE', false);
defined('USE_PRINT_QUEUE_V2')           OR  define('USE_PRINT_QUEUE_V2', false);
defined('PRINT_QUEUE_WARNING_LIMIT')    OR  define('PRINT_QUEUE_WARNING_LIMIT', 10);

//CUSTOMER
defined('PICKUP_CONTACT_MANDATORY')     OR  define('PICKUP_CONTACT_MANDATORY',FALSE);
defined('DEFAULT_POS_CUSTOMER')         OR  define('DEFAULT_POS_CUSTOMER',1);
defined('CUSTOMER_CUSTOM_FIELDS')       OR  define('CUSTOMER_CUSTOM_FIELDS', []);
defined('ALLOW_CUSTOMER_NOTES')         OR  define('ALLOW_CUSTOMER_NOTES', false);

defined('DEFAULT_COUNTRY_ID')           OR  define('DEFAULT_COUNTRY_ID', 38);
defined('DEFAULT_STATE_ID')             OR  define('DEFAULT_STATE_ID', 1);
defined('DEFAULT_CITY_ID')              OR  define('DEFAULT_CITY_ID', 31);

//Summary
defined('ALLOW_DISCOUNT_IN_SUMMARY')              OR  define('ALLOW_DISCOUNT_IN_SUMMARY', false);
defined('ALLOW_GRATUITY_IN_TOTAL_ORDERS_AMOUNT')  OR  define('ALLOW_GRATUITY_IN_TOTAL_ORDERS_AMOUNT', false);
defined('ALLOW_SUMMARY_CASH_EMPLOYEE_TAKEOUT')  OR  define('ALLOW_SUMMARY_CASH_EMPLOYEE_TAKEOUT', true);
defined('DEFAULT_SUMMARY_PRINT')                    OR  define('DEFAULT_SUMMARY_PRINT', false);
defined('DEFAULT_REGISTER_PRINT')                    OR  define('DEFAULT_REGISTER_PRINT', false);
defined('ALLOW_SHIFT_PRINT')                    OR  define('ALLOW_SHIFT_PRINT', true);

//Payment
defined('ALLOW_CARD_PAYMENT_CHANGE')    OR  define('ALLOW_CARD_PAYMENT_CHANGE', false);

//Items
defined('ITEM_TYPE_VARIANT')    OR  define('ITEM_TYPE_VARIANT', 'variant');
defined('ITEM_TYPE_VARIANT_OPTIONAL')    OR  define('ITEM_TYPE_VARIANT_OPTIONAL', 'optional');

//Register or Session(Summary)

defined('SUMMARY_TYPE_REGISTER')    OR  define('SUMMARY_TYPE_REGISTER', 'register');
defined('SUMMARY_TYPE_SESSION')    OR  define('SUMMARY_TYPE_SESSION', 'session');
defined('SUMMARY_TYPE_EMPLOYEE')    OR  define('SUMMARY_TYPE_EMPLOYEE', 'employee');

defined( 'CARD_PAYMENT_ID' ) OR define( 'CARD_PAYMENT_ID', 3 );

//Clover
defined( 'ALLOW_CLOVER_PAYMENT' ) OR define( 'ALLOW_CLOVER_PAYMENT', false );
defined('ITEM_TYPES')        OR  define('ITEM_TYPES', [
        ['value'=>'food','title'=>'Food'],
        ['value'=>'liquor','title'=>'Liquor']
    ]);
defined( 'ONLINE_ORDER_PAYMENT_IDS' ) OR define( 'ONLINE_ORDER_PAYMENT_IDS', [] );
defined( 'ALLOW_ORDER_EDIT' ) OR define( 'ALLOW_ORDER_EDIT', false );
defined( 'DEFAULT_PAGINATION_LIMIT' ) OR define( 'DEFAULT_PAGINATION_LIMIT', 10 );

defined('ITEM_SPICINESS')        OR  define('ITEM_SPICINESS', [
    ['id'=>'none','value'=>'None'],
    ['id'=>'s','value'=>'Spicy'],
    ['id'=>'es','value'=>'Extra Spicy']
]);
defined( 'AVOID_DASHBOARD_CUSTOMER_ID' ) OR define( 'AVOID_DASHBOARD_CUSTOMER_ID', 1 );

//Auto Kitchen Print
defined('DEFAULT_KITCHEN_PRINT_IN_AUTO_DISCOUNT')    OR  define('DEFAULT_KITCHEN_PRINT_IN_AUTO_DISCOUNT', false);
//Promotions
defined('WEEKDAYS')    OR  define('WEEKDAYS', ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat']);
