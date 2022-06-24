<?php
$module = 'contacts';
defined('CONTACT_MIGRATION_PATH')                   OR define('CONTACT_MIGRATION_PATH', MODULES_PATH . '/'.$module.'/migrations/');
defined('CONTACT_MIGRATION_TABLE')                  OR define('CONTACT_MIGRATION_TABLE', 'mig_'.$module);

defined('CONTACT_SALUTATION_TABLE')                 OR  define('CONTACT_SALUTATION_TABLE', 'con_salutation');

defined('CONTACT_CUSTOMER_TABLE')                   OR  define('CONTACT_CUSTOMER_TABLE', 'con_customer');
defined('CONTACT_CUSTOMER_ADDRESS_TABLE')           OR  define('CONTACT_CUSTOMER_ADDRESS_TABLE', 'con_customer_address');
defined('CONTACT_CUSTOMER_GROUPS_TABLE')            OR  define('CONTACT_CUSTOMER_GROUPS_TABLE', 'con_customer_groups');

defined('CONTACT_VENDOR_TABLE')                     OR  define('CONTACT_VENDOR_TABLE', 'con_vendor');
defined('CONTACT_VENDOR_ADDRESS_TABLE')             OR  define('CONTACT_VENDOR_ADDRESS_TABLE', 'con_vendor_address');
defined('CONTACT_VENDOR_ADDITIONAL_CONTACT_TABLE')  OR  define('CONTACT_VENDOR_ADDITIONAL_CONTACT_TABLE', 'con_ven');

defined('COUNTRY_TABLE')                            OR  define('COUNTRY_TABLE', 'sys_country');
defined('CITY_TABLE')                               OR  define('CITY_TABLE', 'sys_city');
defined('USER_FORGOT_TABLE')                        OR  define('USER_FORGOT_TABLE', 'usr_forgot');
