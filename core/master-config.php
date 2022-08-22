<?php
defined('CORE_VERSION')     OR define('CORE_VERSION', '0.1.74');

defined('COMPOSER_PATH')    OR define('COMPOSER_PATH', BASEPATH . '../vendor/autoload.php');
defined('LOG_PATH')         OR define('LOG_PATH', BASEPATH . '../projects/' . PROJECT_CODE . '/logs/');
defined('CACHE_PATH')       OR define('CACHE_PATH', BASEPATH . '../projects/' . PROJECT_CODE . '/cache/');
defined('SESSION_PATH')     OR define('SESSION_PATH', BASEPATH . '../projects/' . PROJECT_CODE . '/sessions/');
//defined('ERROR_VIEWS_PATH') OR define('ERROR_VIEWS_PATH', '../core/projects/' . PROJECT_CODE . '/errors/');
defined('ERROR_VIEWS_PATH') OR define('ERROR_VIEWS_PATH', '');
