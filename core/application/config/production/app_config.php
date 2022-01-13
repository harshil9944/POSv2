<?php
$config['dir_separator'] = '/';
$config['global_upload_path'] = LIVE_GLOBAL_UPLOAD_PATH;
$config['global_upload_cache_path'] = $config['global_upload_path'] . 'cache/';

$base_url = LIVE_BASE_URL;

$config['global_upload_url'] = $base_url . 'uploads/';
$config['global_upload_cache_url'] = $base_url . 'uploads/cache/';

//Timezone
$config['default_timezone'] = LIVE_DEFAULT_TIMEZONE;
$config['default_timezone_value'] = LIVE_DEFAULT_TIMEZONE_VALUE;

$config['print_server_url'] = LIVE_PRINT_SERVER_URL;
