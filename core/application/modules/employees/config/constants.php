<?php
$constant = 'EMPLOYEE';
$module = 'employees';
defined('EMPLOYEE_MIGRATION_PATH')          OR define('EMPLOYEE_MIGRATION_PATH', MODULES_PATH . '/'.$module.'/migrations/');
defined('EMPLOYEE_MIGRATION_TABLE')         OR define('EMPLOYEE_MIGRATION_TABLE', 'mig_'.$module);

defined('EMPLOYEE_TABLE')                   OR  define('EMPLOYEE_TABLE', 'emp_employee');
defined('EMPLOYEE_SHIFT_TABLE')             OR  define('EMPLOYEE_SHIFT_TABLE', 'emp_shift');

defined('EMPLOYEE_TO_WAREHOUSE_TABLE')      OR  define('EMPLOYEE_TO_WAREHOUSE_TABLE', 'usr_user_to_warehouse');
defined('EMPLOYEE_TO_REGISTER_TABLE')       OR  define('EMPLOYEE_TO_REGISTER_TABLE', 'usr_user_to_register');
