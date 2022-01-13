<?php
$config['dir_separator'] = '\\';
$config['global_upload_path'] = DEV_GLOBAL_UPLOAD_PATH;
$config['global_upload_cache_path'] = $config['global_upload_path'] . 'cache/';

$base_url = DEV_BASE_URL;

$config['global_upload_url'] = $base_url . 'uploads/';
$config['global_upload_cache_url'] = $base_url . 'uploads/cache/';

//Timezone
$config['default_timezone'] = DEV_DEFAULT_TIMEZONE;
$config['default_timezone_value'] = DEV_DEFAULT_TIMEZONE_VALUE;

$config['print_server_url'] = DEV_PRINT_SERVER_URL;
