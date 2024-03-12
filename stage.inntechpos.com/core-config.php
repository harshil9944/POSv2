<?php
defined('TEST_ENV')                     OR  define('TEST_ENV', false);

defined('PROJECT_CODE')                 OR  define('PROJECT_CODE', 'inntechpos');
defined('ENCRYPTION_KEY')               OR  define('ENCRYPTION_KEY', 'bXm9pGl5Et8QeOPofomiRpv0mJGp4Ii8');

defined('CORE_APP_TITLE')               OR  define('CORE_APP_TITLE', 'InnTechPOS Core App');
defined('CORE_APP_URL')                 OR  define('CORE_APP_URL', 'https://www.inntechfuture.com/');

defined( 'CORE_WEB_URL' ) OR define( 'CORE_WEB_URL', 'https://www.inntechfuture.com/' );

defined('CORE_PRINT_NAME')              OR  define('CORE_PRINT_NAME', 'InnTechPOS Core App');

//Session Expiration Time in Seconds
defined('SESSION_EXPIRATION_TIME')      OR  define('SESSION_EXPIRATION_TIME', 0);

defined('ORDER_METHODS') OR define('ORDER_METHODS', 'p,dine');

defined('SO_SOURCE_POS_ID')             OR  define('SO_SOURCE_POS_ID', 2);
defined('SO_SOURCE_WEB_ID')             OR  define('SO_SOURCE_WEB_ID', 3);

defined('ORDER_SOURCE_PAYMENT_LINK')    OR  define('ORDER_SOURCE_PAYMENT_LINK', [
    ['print_label'=>'STD','source_id'=>4,'payment_method_id'=>5],
    ['print_label'=>'DD','source_id'=>5,'payment_method_id'=>6],
    ['print_label'=>'UBER','source_id'=>6,'payment_method_id'=>7],
    ['print_label'=>'GIFTCARD','source_id'=>2,'payment_method_id'=>8],
    ['print_label'=>'FOODDOOR','source_id'=>7,'payment_method_id'=>9],
]);

defined('DEFAULT_CASHIER_PRINT')        OR  define('DEFAULT_CASHIER_PRINT',true);
defined('DEFAULT_KITCHEN_PRINT')        OR  define('DEFAULT_KITCHEN_PRINT',true);

defined('IGNORE_PRINT_PAYMENT_IDS')     OR  define('IGNORE_PRINT_PAYMENT_IDS', [4]);
//For printing source name in prints
defined('PRINT_SOURCE_IDS')             OR  define('PRINT_SOURCE_IDS', [4,5,6,7]);

defined('WEB_SESSION_ID')               OR  define('WEB_SESSION_ID', 9999999);
defined('WEB_SALESPERSON_ID')           OR  define('WEB_SALESPERSON_ID', 9999999);

defined('TABLE_LIST_COL_CLASS')         OR  define('TABLE_LIST_COL_CLASS','col-md-3');
defined('ENABLE_EXT_ORDER_NO')          OR  define('ENABLE_EXT_ORDER_NO',true);

//Medium Spice Level is mandatory else system will crash
defined('SPICE_LEVELS')                 OR  define('SPICE_LEVELS',[
    ['id'=>'Mild','title'=>'Mild'],
    ['id'=>'Medium','title'=>'Medium'],
    ['id'=>'Hot','title'=>'Hot'],
    ['id'=>'Indian Spicy','title'=>'Indian Spicy'],
]);
defined('DEFAULT_SPICE_LEVEL')          OR  define('DEFAULT_SPICE_LEVEL', 'Medium');

defined('KITCHEN_PRINTERS')             OR  define('KITCHEN_PRINTERS', [
    ['id'=>'default','value'=>"Don't Print"],
    ['id'=>'kitchen','value'=>'Main Kitchen'],
    ['id'=>'front','value'=>'Front']
]);

defined('CUSTOMER_USERNAME_FIELD')      OR  define('CUSTOMER_USERNAME_FIELD', 'mobile');
defined('CUSTOMER_AUTOFILL_FIELD')      OR  define('CUSTOMER_AUTOFILL_FIELD', 'phone');

defined('ENABLE_SPLIT_ORDERS')          OR  define('ENABLE_SPLIT_ORDERS', true);
defined('POS_LOAD_ADDON_ITEMS')         OR  define('POS_LOAD_ADDON_ITEMS', false);
defined('ENABLE_SOURCE_SWITCH')         OR  define('ENABLE_SOURCE_SWITCH', true);
defined('START_WEB_ORDERS_WITH_SESSION')OR  define('START_WEB_ORDERS_WITH_SESSION',true);

defined('DEFAULT_WEB_ORDER_SOUND')      OR  define('DEFAULT_WEB_ORDER_SOUND','audio2.wav');
defined('PLAY_SOUND_ON_NEW_ORDER')      OR  define('PLAY_SOUND_ON_NEW_ORDER',true);

defined('NOTIFICATION_PUBLIC_KEY')      OR  define('NOTIFICATION_PUBLIC_KEY', 'BLivyLi6Ep7DfUFGak19PRbGMM6bMR88CPRcBdDFv415VsE-d_vKwfj_eKJKrWER_bnPYb6tDcYuKseyUnFsK7Q');
defined('NOTIFICATION_PRIVATE_KEY')     OR  define('NOTIFICATION_PRIVATE_KEY', '8dUPbof2NZDNmYbUx5oj69wWfl95z8jJ5sAYIWgXSEg');

//Development Settings
defined('DEV_BASE_URL')                 OR  define('DEV_BASE_URL', 'http://inntechpos2.ci/');
defined('DEV_GLOBAL_UPLOAD_PATH')       OR  define('DEV_GLOBAL_UPLOAD_PATH', 'C:/Users/Sandip/Documents/projects/2022/I/InnTechPOS2-Master/stage.inntechpos.com/uploads/');
defined('DEV_PRINT_SERVER_URL')         OR  define('DEV_PRINT_SERVER_URL', 'http://print-server.ci/');

defined('DEV_DB_HOSTNAME')              OR  define('DEV_DB_HOSTNAME', 'localhost');
defined('DEV_DB_USERNAME')              OR  define('DEV_DB_USERNAME', 'root');
defined('DEV_DB_PASSWORD')              OR  define('DEV_DB_PASSWORD', '');
defined('DEV_DB_SCHEMA')                OR  define('DEV_DB_SCHEMA', 'inntech_pos_v2_live_db');

//Production Settings
defined('LIVE_BASE_URL')                OR  define('LIVE_BASE_URL', 'https://stage.inntechpos.com/');
defined('LIVE_GLOBAL_UPLOAD_PATH')      OR  define('LIVE_GLOBAL_UPLOAD_PATH', '/home3/divaak22/staging/stage.inntechpos.com/uploads/');
defined('LIVE_PRINT_SERVER_URL')        OR  define('LIVE_PRINT_SERVER_URL', 'http://localhost:8164/print-server.ci/');

defined('LIVE_DB_HOSTNAME')             OR  define('LIVE_DB_HOSTNAME', 'localhost');
defined('LIVE_DB_USERNAME')             OR  define('LIVE_DB_USERNAME', 'divaak22_st_inntechpos');
defined('LIVE_DB_PASSWORD')             OR  define('LIVE_DB_PASSWORD', '^!qoc[SzcIc#');
defined('LIVE_DB_SCHEMA')               OR  define('LIVE_DB_SCHEMA', 'divaak22_st_inntechpos');

defined('CUSTOMER_CUSTOM_FIELDS')       OR  define('CUSTOMER_CUSTOM_FIELDS', ['memberNumber','fullVaccinated']);

defined('ALLOW_GRATUITY')               OR  define('ALLOW_GRATUITY', true );
defined('GRATUITY_RATE')                OR  define('GRATUITY_RATE', 18);
defined('GRATUITY_PERSONS')             OR  define('GRATUITY_PERSONS', 3);
defined('DISPLAY_SEAT_USED_INVOICE')    OR  define('DISPLAY_SEAT_USED_INVOICE', true);

defined('PRINT_QUEUE')                  OR  define('PRINT_QUEUE', true);
defined('ALLOW_RELEASE_TABLE')          OR  define('ALLOW_RELEASE_TABLE', true);

defined('ALLOW_VOID_ITEM')              OR  define('ALLOW_VOID_ITEM', true);

defined('ALLOW_DISCOUNT_IN_SUMMARY')     OR  define('ALLOW_DISCOUNT_IN_SUMMARY', true);
defined('ALLOW_GRATUITY_IN_TOTAL_ORDERS_AMOUNT')  OR  define('ALLOW_GRATUITY_IN_TOTAL_ORDERS_AMOUNT', true);

defined('ALLOW_CUSTOMER_GROUP')         OR  define('ALLOW_CUSTOMER_GROUP', true);
defined('ALLOW_CUSTOMER_NOTES')         OR  define('ALLOW_CUSTOMER_NOTES', true);

defined( 'CARD_PAYMENT_ID' ) OR define( 'CARD_PAYMENT_ID', 3 );

defined( 'ALLOW_CLOVER_PAYMENT' ) OR define( 'ALLOW_CLOVER_PAYMENT', false );
defined( 'CLOVER_MERCHANT_ID' ) OR define( 'CLOVER_MERCHANT_ID', '70W8AGE61JKJ1' );
defined( 'CLOVER_ACCESS_TOKEN' ) OR define( 'CLOVER_ACCESS_TOKEN', '74c1dd7a-0697-3fa4-1816-c74eba67c2b8' );
defined( 'CLOVER_DEVICE_ID' ) OR define( 'CLOVER_DEVICE_ID', '98559e79-f32b-4c57-9848-74671af22c95' );
defined( 'CLOVER_FRIENDLY_ID' ) OR define( 'CLOVER_FRIENDLY_ID', 'Primary POS' );
defined( 'CLOVER_REMOTE_APPLICATION_ID' ) OR define( 'CLOVER_REMOTE_APPLICATION_ID', 'CLOVERDEV.655VQ41Z9CVF8' );
defined( 'CLOVER_DOMAIN' ) OR define( 'CLOVER_DOMAIN', 'https://sandbox.dev.clover.com' );
defined( 'CLOVER_PAYMENT_WAITING_MESSAGE' ) OR define( 'CLOVER_PAYMENT_WAITING_MESSAGE', 'Waiting For Payment' );
defined( 'CLOVER_TIP_PERCENTAGE' ) OR define( 'CLOVER_TIP_PERCENTAGE', true );
defined( 'CLOVER_TIP_SUGGESTIONS' ) OR define( 'CLOVER_TIP_SUGGESTIONS', [
    ['title' => 'Acceptable', 'value' => 15],
    ['title' => 'Good', 'value' => 18],
    ['title' => 'Great', 'value' => 20],
    ['title' => 'Excellent', 'value' => 25],
] );

defined('ALLOW_SUMMARY_PRINT')                    OR  define('ALLOW_SUMMARY_PRINT', true);

defined('OPEN_ITEM_ID')                 OR  define('OPEN_ITEM_ID', 195);
defined('OPEN_ITEM_CATEGORY_ID')        OR  define('OPEN_ITEM_CATEGORY_ID', 20);

defined('PICKUP_CONTACT_MANDATORY')     OR  define('PICKUP_CONTACT_MANDATORY',false);
defined('ALLOW_CONVERT_CHANGE_TO_TIP')  OR  define('ALLOW_CONVERT_CHANGE_TO_TIP', true);
defined( 'ONLINE_ORDER_PAYMENT_IDS' ) OR define( 'ONLINE_ORDER_PAYMENT_IDS', [5,6] );
defined( 'ALLOW_ORDER_EDIT' ) OR define( 'ALLOW_ORDER_EDIT', true );
defined('DEFAULT_KITCHEN_PRINT_IN_AUTO_DISCOUNT')    OR  define('DEFAULT_KITCHEN_PRINT_IN_AUTO_DISCOUNT', true);

//Admin Email
defined('DEFAULT_ADMIN_EMAIL')        OR  define('DEFAULT_ADMIN_EMAIL', 'sanju@divaasolutions.com');
