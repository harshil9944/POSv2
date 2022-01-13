<?php
$module = 'printers';
defined('PRINTERS_MIGRATION_PATH')  OR  define('PRINTERS_MIGRATION_PATH', MODULES_PATH . '/'.$module.'/migrations/');
defined('PRINTERS_MIGRATION_TABLE') OR  define('PRINTERS_MIGRATION_TABLE', 'mig_'.$module);

defined('PRINTERS_TABLE')           OR  define('PRINTERS_TABLE', 'sys_printer');
defined('TEMPLATES_TABLE')           OR  define('TEMPLATES_TABLE', 'sys_print_template');

