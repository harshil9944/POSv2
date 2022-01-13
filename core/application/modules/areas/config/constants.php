<?php
$module = 'areas';
defined('AREAS_MIGRATION_PATH')  OR  define('AREAS_MIGRATION_PATH', MODULES_PATH . '/'.$module.'/migrations/');
defined('AREAS_MIGRATION_TABLE') OR  define('AREAS_MIGRATION_TABLE', 'mig_'.$module);

defined('AREAS_TABLE')           OR  define('AREAS_TABLE', 'ara_area');
defined('AREAS_TABLES_TABLE')    OR  define('AREAS_TABLES_TABLE', 'ara_table');
defined('AREAS_SESSION_TABLE')   OR  define('AREAS_SESSION_TABLE', 'ara_session');
defined('AREAS_RELATION_TABLE')  OR  define('AREAS_RELATION_TABLE', 'ara_relation');
