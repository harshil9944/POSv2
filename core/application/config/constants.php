<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| Display Debug backtrace
|--------------------------------------------------------------------------
|
| If set to TRUE, a backtrace will be displayed along with php errors. If
| error_reporting is disabled, the backtrace will not display, regardless
| of this setting
|
*/
defined('SHOW_DEBUG_BACKTRACE') OR define('SHOW_DEBUG_BACKTRACE', TRUE);

/*
|--------------------------------------------------------------------------
| File and Directory Modes
|--------------------------------------------------------------------------
|
| These prefs are used when checking and setting modes when working
| with the file system.  The defaults are fine on servers with proper
| security, but you may wish (or even need) to change the values in
| certain environments (Apache running a separate process for each
| user, PHP under CGI with Apache suEXEC, etc.).  Octal values should
| always be used to set the mode correctly.
|
*/
defined('FILE_READ_MODE')  OR define('FILE_READ_MODE', 0644);
defined('FILE_WRITE_MODE') OR define('FILE_WRITE_MODE', 0666);
defined('DIR_READ_MODE')   OR define('DIR_READ_MODE', 0755);
defined('DIR_WRITE_MODE')  OR define('DIR_WRITE_MODE', 0755);

/*
|--------------------------------------------------------------------------
| File Stream Modes
|--------------------------------------------------------------------------
|
| These modes are used when working with fopen()/popen()
|
*/
defined('FOPEN_READ')                           OR define('FOPEN_READ', 'rb');
defined('FOPEN_READ_WRITE')                     OR define('FOPEN_READ_WRITE', 'r+b');
defined('FOPEN_WRITE_CREATE_DESTRUCTIVE')       OR define('FOPEN_WRITE_CREATE_DESTRUCTIVE', 'wb'); // truncates existing file data, use with care
defined('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE')  OR define('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE', 'w+b'); // truncates existing file data, use with care
defined('FOPEN_WRITE_CREATE')                   OR define('FOPEN_WRITE_CREATE', 'ab');
defined('FOPEN_READ_WRITE_CREATE')              OR define('FOPEN_READ_WRITE_CREATE', 'a+b');
defined('FOPEN_WRITE_CREATE_STRICT')            OR define('FOPEN_WRITE_CREATE_STRICT', 'xb');
defined('FOPEN_READ_WRITE_CREATE_STRICT')       OR define('FOPEN_READ_WRITE_CREATE_STRICT', 'x+b');

/*
|--------------------------------------------------------------------------
| Exit Status Codes
|--------------------------------------------------------------------------
|
| Used to indicate the conditions under which the script is exit()ing.
| While there is no universal standard for error codes, there are some
| broad conventions.  Three such conventions are mentioned below, for
| those who wish to make use of them.  The CodeIgniter defaults were
| chosen for the least overlap with these conventions, while still
| leaving room for others to be defined in future versions and user
| applications.
|
| The three main conventions used for determining exit status codes
| are as follows:
|
|    Standard C/C++ Library (stdlibc):
|       http://www.gnu.org/software/libc/manual/html_node/Exit-Status.html
|       (This link also contains other GNU-specific conventions)
|    BSD sysexits.h:
|       http://www.gsp.com/cgi-bin/man.cgi?section=3&topic=sysexits
|    Bash scripting:
|       http://tldp.org/LDP/abs/html/exitcodes.html
|
*/
defined('EXIT_SUCCESS')        OR define('EXIT_SUCCESS', 0); // no errors
defined('EXIT_ERROR')          OR define('EXIT_ERROR', 1); // generic error
defined('EXIT_CONFIG')         OR define('EXIT_CONFIG', 3); // configuration error
defined('EXIT_UNKNOWN_FILE')   OR define('EXIT_UNKNOWN_FILE', 4); // file not found
defined('EXIT_UNKNOWN_CLASS')  OR define('EXIT_UNKNOWN_CLASS', 5); // unknown class
defined('EXIT_UNKNOWN_METHOD') OR define('EXIT_UNKNOWN_METHOD', 6); // unknown class member
defined('EXIT_USER_INPUT')     OR define('EXIT_USER_INPUT', 7); // invalid user input
defined('EXIT_DATABASE')       OR define('EXIT_DATABASE', 8); // database error
defined('EXIT__AUTO_MIN')      OR define('EXIT__AUTO_MIN', 9); // lowest automatically-assigned error code
defined('EXIT__AUTO_MAX')      OR define('EXIT__AUTO_MAX', 125); // highest automatically-assigned error code

defined('MODULES_PATH')    OR define('MODULES_PATH', APPPATH.'modules');

//COMPONENTS
defined('LIST_VIEW_PATH')    OR define('LIST_VIEW_PATH', 'components/list_view');
defined('LIST_XTEMPLATE_PATH')    OR define('LIST_XTEMPLATE_PATH', 'components/list_xtemplate');
defined('FORM_VIEW_PATH')    OR define('FORM_VIEW_PATH', 'components/form_view');
defined('BASIC_TABLE_PATH')    OR define('BASIC_TABLE_PATH', 'components/basic_table');
defined('DATA_TABLE_PATH')    OR define('DATA_TABLE_PATH', 'components/data_table');

//SCRIPT PATHS
defined('REMOVE_PAGE_SCRIPTS_PATH')    OR define('REMOVE_PAGE_SCRIPTS_PATH', '../core/assets/');
defined('PAGE_SCRIPTS_PATH')    OR define('PAGE_SCRIPTS_PATH', '../core/assets/assets/pages/js');
defined('PAGE_STYLES_PATH')    OR define('PAGE_STYLES_PATH', '../core/assets/assets/pages/css');

//DEFAULT TEMPLATE
defined('DEFAULT_TEMPLATE')    OR define('DEFAULT_TEMPLATE', 'brahma');

//TABLES
//SYSTEM TABLES
defined('SETTING_TABLE')    OR define('SETTING_TABLE', 'sys_setting');
defined('AJAX_KEY_TABLE')   OR define('AJAX_KEY_TABLE', 'sys_key');
defined('MODULE_TABLE')     OR define('MODULE_TABLE', 'sys_module');
defined('SESSION_TABLE')    OR define('SESSION_TABLE', 'sys_session');
defined('ROUTE_TABLE')      OR define('ROUTE_TABLE', 'sys_route');
defined('PERMISSION_TABLE') OR define('PERMISSION_TABLE', 'sys_permission');
defined('REFERENCE_TABLE')  OR define('REFERENCE_TABLE', 'sys_ref');

//SYSTEM ROUTES
defined('LOGIN_ROUTE')        OR define('LOGIN_ROUTE', 'auth/login');
defined('LOGOUT_ROUTE')        OR define('LOGOUT_ROUTE', 'auth/logout');
defined('FORGOT_ROUTE')        OR define('FORGOT_ROUTE', 'auth/forgot');

defined('TEXT_COLUMNS')       OR define('TEXT_COLUMNS', 9);
defined('LABEL_COLUMNS')        OR define('LABEL_COLUMNS', 3);
