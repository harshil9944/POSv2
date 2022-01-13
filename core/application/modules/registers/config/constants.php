<?php
$constant = 'REGISTER';
$module = 'registers';
defined('REGISTER_MIGRATION_PATH')     OR define('REGISTER_MIGRATION_PATH', MODULES_PATH . '/'.$module.'/migrations/');
defined('REGISTER_MIGRATION_TABLE')    OR define('REGISTER_MIGRATION_TABLE', 'mig_'.$module);

defined('REGISTER_TABLE')           OR  define('REGISTER_TABLE', 'sys_register');
