<?php
defined('NOTIFICATION_MIGRATION_PATH')    OR define('NOTIFICATION_MIGRATION_PATH', MODULES_PATH . '/notifications/migrations/');
defined('NOTIFICATION_MIGRATION_TABLE')   OR define('NOTIFICATION_MIGRATION_TABLE', 'mig_notifications');

defined('NOTIFICATION_TABLE')           OR  define('NOTIFICATION_TABLE', 'sys_notification');
defined('NOTIFICATION_WEBPUSH_TABLE')   OR  define('NOTIFICATION_WEBPUSH_TABLE', 'sys_webpush');
