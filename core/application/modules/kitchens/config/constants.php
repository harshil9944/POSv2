<?php
$module = 'kitchens';
defined('KITCHENS_MIGRATION_PATH')  OR  define('KITCHENS_MIGRATION_PATH', MODULES_PATH . '/'.$module.'/migrations/');
defined('KITCHENS_MIGRATION_TABLE') OR  define('KITCHENS_MIGRATION_TABLE', 'mig_'.$module);

defined('KITCHENS_TABLE')           OR  define('KITCHENS_TABLE', 'sys_kitchen');


