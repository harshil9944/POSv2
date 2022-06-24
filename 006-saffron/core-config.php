<?php
defined('PROJECT_CODE')                 OR  define('PROJECT_CODE', 'saffron');
defined('ENCRYPTION_KEY')               OR  define('ENCRYPTION_KEY', 'HbWqncMP7qs276lSLk3tywiJKvQiBtzW');

defined('CORE_APP_TITLE')   			OR define('CORE_APP_TITLE', 'Saffron Indian Cuisine');
defined('CORE_APP_URL')     			OR define('CORE_APP_URL', 'https://www.inntechfuture.com/');

defined('CORE_PRINT_NAME')  			OR define('CORE_PRINT_NAME', 'Saffron Indian Cuisine');

//Session Expiration Time in Seconds
defined('SESSION_EXPIRATION_TIME')      OR  define('SESSION_EXPIRATION_TIME', 0);

defined('SO_SOURCE_POS_ID')             OR  define('SO_SOURCE_POS_ID', 2);
defined('SO_SOURCE_WEB_ID')             OR  define('SO_SOURCE_WEB_ID', 3);

defined('ORDER_SOURCE_PAYMENT_LINK')    OR  define('ORDER_SOURCE_PAYMENT_LINK', [
    ['print_label'=>'STD','source_id'=>4,'payment_method_id'=>5],
    ['print_label'=>'DD','source_id'=>5,'payment_method_id'=>6],
    ['print_label'=>'UBER','source_id'=>6,'payment_method_id'=>7],
    ['print_label'=>'DIW','source_id'=>7,'payment_method_id'=>8],
]);

defined('IGNORE_PRINT_PAYMENT_IDS')     OR  define('IGNORE_PRINT_PAYMENT_IDS', [4]);
//For printing source name in prints
defined('PRINT_SOURCE_IDS')             OR  define('PRINT_SOURCE_IDS', [4,5,6,7]);

defined('WEB_SESSION_ID')               OR  define('WEB_SESSION_ID', 9999999);
defined('WEB_SALESPERSON_ID')           OR  define('WEB_SALESPERSON_ID', 9999999);

defined('TABLE_LIST_COL_CLASS')         OR  define('TABLE_LIST_COL_CLASS','col-md-2');
defined('ENABLE_EXT_ORDER_NO')          OR  define('ENABLE_EXT_ORDER_NO',true);
defined('ALLOW_OPEN_CASH_DRAWER')       OR  define('ALLOW_OPEN_CASH_DRAWER', true);

//Medium Spice Level is mandatory else system will crash
defined('SPICE_LEVELS')                 OR  define('SPICE_LEVELS',[
    ['id'=>'Zero','title'=>'Zero'],
    ['id'=>'Mild','title'=>'Mild'],
    ['id'=>'Medium','title'=>'Medium'],
    ['id'=>'Spicy','title'=>'Spicy'],
    ['id'=>'X-Spicy','title'=>'X-Spicy'],
]);
defined('DEFAULT_SPICE_LEVEL')          OR  define('DEFAULT_SPICE_LEVEL', 'Medium');

defined('KITCHEN_PRINTERS')             OR  define('KITCHEN_PRINTERS', [
    ['id'=>'default','value'=>'Don\'t Print'],
    ['id'=>'kitchen','value'=>'Main Kitchen'],
    ['id'=>'front','value'=>'Bar']
]);

defined('CUSTOMER_USERNAME_FIELD')      OR  define('CUSTOMER_USERNAME_FIELD', 'mobile');
defined('CUSTOMER_AUTOFILL_FIELD')      OR  define('CUSTOMER_AUTOFILL_FIELD', 'phone');

defined('OPEN_ITEM_ID')                 OR  define('OPEN_ITEM_ID', 438);
defined('ENABLE_SPLIT_ORDERS')          OR  define('ENABLE_SPLIT_ORDERS', true);

defined('NOTIFICATION_PUBLIC_KEY')      OR  define('NOTIFICATION_PUBLIC_KEY', 'BLivyLi6Ep7DfUFGak19PRbGMM6bMR88CPRcBdDFv415VsE-d_vKwfj_eKJKrWER_bnPYb6tDcYuKseyUnFsK7Q');
defined('NOTIFICATION_PRIVATE_KEY')     OR  define('NOTIFICATION_PRIVATE_KEY', '8dUPbof2NZDNmYbUx5oj69wWfl95z8jJ5sAYIWgXSEg');

//Development Settings
defined('DEV_BASE_URL')                 OR  define('DEV_BASE_URL', 'http://bombay-tadka.pos/');
defined('DEV_GLOBAL_UPLOAD_PATH')       OR  define('DEV_GLOBAL_UPLOAD_PATH', 'E:/2020/B/bombay-tadka.pos/uploads/');
defined('DEV_PRINT_SERVER_URL')         OR  define('DEV_PRINT_SERVER_URL', 'http://print-server.ci/');

defined('DEV_DB_HOSTNAME')              OR  define('DEV_DB_HOSTNAME', 'localhost');
defined('DEV_DB_USERNAME')              OR  define('DEV_DB_USERNAME', 'root');
defined('DEV_DB_PASSWORD')              OR  define('DEV_DB_PASSWORD', '');
defined('DEV_DB_SCHEMA')                OR  define('DEV_DB_SCHEMA', 'bombay_tadka_pos_db');

//Production Settings
defined('LIVE_BASE_URL')                OR  define('LIVE_BASE_URL', 'https://saffron.inntechpos.com/');
defined('LIVE_GLOBAL_UPLOAD_PATH')      OR  define('LIVE_GLOBAL_UPLOAD_PATH', '/home2/cs1usrmz/pos2020/006-saffron/uploads/');
//defined('LIVE_PRINT_SERVER_URL')        OR  define('LIVE_PRINT_SERVER_URL', 'http://192.168.1.110/');
defined('LIVE_PRINT_SERVER_URL')        OR  define('LIVE_PRINT_SERVER_URL', 'http://localhost/');

defined('LIVE_APPLICATION_DIR')         OR  define('LIVE_APPLICATION_DIR', '../core/application');
defined('LIVE_SYSTEM_DIR')              OR  define('LIVE_SYSTEM_DIR', '../core/system');
defined('LIVE_VIEW_DIR')                OR  define('LIVE_VIEW_DIR', '');

defined('LIVE_DB_HOSTNAME')             OR  define('LIVE_DB_HOSTNAME', 'localhost');
defined('LIVE_DB_USERNAME')             OR  define('LIVE_DB_USERNAME', 'cs1usrmz_pos_usr022021');
defined('LIVE_DB_PASSWORD')             OR  define('LIVE_DB_PASSWORD', '4e~?W$$!q;i&');
defined('LIVE_DB_SCHEMA')               OR  define('LIVE_DB_SCHEMA', 'cs1usrmz_pos_saffron0921');

defined('CUSTOMER_CUSTOM_FIELDS')       OR  define('CUSTOMER_CUSTOM_FIELDS', ['memberNumber','fullVaccinated']);

defined('ALLOW_GRATUITY')               OR  define('ALLOW_GRATUITY', true );
defined('GRATUITY_RATE')                OR  define('GRATUITY_RATE', 18);
defined('GRATUITY_PERSONS')             OR  define('GRATUITY_PERSONS', 7);
defined('DISPLAY_SEAT_USED_INVOICE')    OR  define('DISPLAY_SEAT_USED_INVOICE', true);

defined('PRINT_QUEUE')                  OR  define('PRINT_QUEUE', true);
defined('ALLOW_RELEASE_TABLE')          OR  define('ALLOW_RELEASE_TABLE', true);

defined('ALLOW_VOID_ITEM')              OR  define('ALLOW_VOID_ITEM', true);

defined('ALLOW_DISCOUNT_IN_SUMMARY')     OR  define('ALLOW_DISCOUNT_IN_SUMMARY', true);
defined('ALLOW_GRATUITY_IN_TOTAL_ORDERS_AMOUNT')  OR  define('ALLOW_GRATUITY_IN_TOTAL_ORDERS_AMOUNT', true);

defined('ALLOW_CUSTOMER_GROUP')         OR  define('ALLOW_CUSTOMER_GROUP', true);
defined('ALLOW_CUSTOMER_NOTES')         OR  define('ALLOW_CUSTOMER_NOTES', true);
