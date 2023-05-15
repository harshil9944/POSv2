<?php
$module = 'bookings';
defined('BOOKINGS_MIGRATION_PATH')  OR  define('BOOKINGS_MIGRATION_PATH', MODULES_PATH . '/'.$module.'/migrations/');
defined('BOOKINGS_MIGRATION_TABLE') OR  define('BOOKINGS_MIGRATION_TABLE', 'mig_'.$module);

defined('BOOKINGS_TABLE')           OR  define('BOOKINGS_TABLE', 'bok_booking');
defined('BOOKINGS_STATUS_TABLE')           OR  define('BOOKINGS_STATUS_TABLE', 'bok_status');
