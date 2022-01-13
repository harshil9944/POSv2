<?php
$constant = 'OUTLET';
$module = 'outlets';

defined($constant.'_MIGRATION_PATH')     OR define($constant.'_MIGRATION_PATH', MODULES_PATH . '/'.$module.'/migrations/');
defined($constant.'_MIGRATION_TABLE')    OR define($constant.'_MIGRATION_TABLE', 'mig_'.$module);

defined('OUTLET_TABLE')           OR  define('OUTLET_TABLE', 'sys_warehouse');
