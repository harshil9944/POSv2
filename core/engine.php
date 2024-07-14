<?php
define('ENVIRONMENT', 'development');
require_once 'core-config.php';
require_once '../core/default-constants.php';
$key = 'LIVE';
switch (ENVIRONMENT)
{
    case 'development':
        error_reporting(-1);
        ini_set('display_errors', 1);
        $key = 'DEV';
        break;

    case 'testing':
    case 'production':
        ini_set('display_errors', 1);
        if (version_compare(PHP_VERSION, '5.3', '>='))
        {
            error_reporting(E_ALL & ~E_NOTICE & ~E_DEPRECATED & ~E_STRICT & ~E_USER_NOTICE & ~E_USER_DEPRECATED);
        }
        else
        {
            error_reporting(E_ALL & ~E_NOTICE & ~E_STRICT & ~E_USER_NOTICE);
        }
        $key = 'LIVE';
        break;

    default:
        header('HTTP/1.1 503 Service Unavailable.', TRUE, 503);
        echo 'The application environment is not set correctly.';
        exit(1); // EXIT_ERROR
}
$system_path = constant($key.'_SYSTEM_DIR');
$application_folder = constant($key.'_APPLICATION_DIR');
$view_folder = constant($key.'_VIEW_DIR');
if (defined('STDIN'))
{
    chdir(dirname(__FILE__));
}

if (($_temp = realpath($system_path)) !== FALSE)
{
    $system_path = $_temp.DIRECTORY_SEPARATOR;
}
else
{
    // Ensure there's a trailing slash
    $system_path = strtr(
            rtrim($system_path, '/\\'),
            '/\\',
            DIRECTORY_SEPARATOR.DIRECTORY_SEPARATOR
        ).DIRECTORY_SEPARATOR;
}

// Is the system path correct?
if ( ! is_dir($system_path))
{
    header('HTTP/1.1 503 Service Unavailable.', TRUE, 503);
    echo 'Your system folder path does not appear to be set correctly. Please open the following file and correct this: '.pathinfo(__FILE__, PATHINFO_BASENAME);
    exit(3); // EXIT_CONFIG
}
define('SELF', pathinfo(__FILE__, PATHINFO_BASENAME));
define('BASEPATH', $system_path);
define('FCPATH', dirname(__FILE__).DIRECTORY_SEPARATOR);
define('SYSDIR', basename(BASEPATH));
require_once '../core/master-config.php';
if (is_dir($application_folder))
{
    if (($_temp = realpath($application_folder)) !== FALSE)
    {
        $application_folder = $_temp;
    }
    else
    {
        $application_folder = strtr(
            rtrim($application_folder, '/\\'),
            '/\\',
            DIRECTORY_SEPARATOR.DIRECTORY_SEPARATOR
        );
    }
}
elseif (is_dir(BASEPATH.$application_folder.DIRECTORY_SEPARATOR))
{
    $application_folder = BASEPATH.strtr(
            trim($application_folder, '/\\'),
            '/\\',
            DIRECTORY_SEPARATOR.DIRECTORY_SEPARATOR
        );
}
else
{
    header('HTTP/1.1 503 Service Unavailable.', TRUE, 503);
    echo 'Your application folder path does not appear to be set correctly. Please open the following file and correct this: '.SELF;
    exit(3); // EXIT_CONFIG
}
define('APPPATH', $application_folder.DIRECTORY_SEPARATOR);
if ( ! isset($view_folder[0]) && is_dir(APPPATH.'views'.DIRECTORY_SEPARATOR))
{
    $view_folder = APPPATH.'views';
}
elseif (is_dir($view_folder))
{
    if (($_temp = realpath($view_folder)) !== FALSE)
    {
        $view_folder = $_temp;
    }
    else
    {
        $view_folder = strtr(
            rtrim($view_folder, '/\\'),
            '/\\',
            DIRECTORY_SEPARATOR.DIRECTORY_SEPARATOR
        );
    }
}
elseif (is_dir(APPPATH.$view_folder.DIRECTORY_SEPARATOR))
{
    $view_folder = APPPATH.strtr(
            trim($view_folder, '/\\'),
            '/\\',
            DIRECTORY_SEPARATOR.DIRECTORY_SEPARATOR
        );
}
else
{
    header('HTTP/1.1 503 Service Unavailable.', TRUE, 503);
    echo 'Your view folder path does not appear to be set correctly. Please open the following file and correct this: '.SELF;
    exit(3); // EXIT_CONFIG
}

if(!file_exists(CACHE_PATH)) {
    mkdir(CACHE_PATH,0777,true);
}

define('VIEWPATH', $view_folder.DIRECTORY_SEPARATOR);
require_once BASEPATH.'core/CodeIgniter.php';
