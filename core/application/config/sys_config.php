<?php
$config['app_version'] = CORE_VERSION;
$config['app_title'] = CORE_APP_TITLE;

$config['permission_exclusion_pages'] = array('login','logout','forgot','retrieve','unauthorised','action');

$config['message_types'] = array(
    'success' => array('icon'=>'zmdi-check-circle','class'=>'alert-success','title'=>'Success!'),
    'warning' => array('icon'=>'zmdi-help','class'=>'alert-warning','title'=>'Warning!'),
    'info' => array('icon'=>'zmdi-info','class'=>'alert-info','title'=>'Info!'),
    'error' => array('icon'=>'zmdi-bug','class'=>'alert-danger','title'=>'Error!')
);

$config['ignore_route_controllers'] = ['api','auth','action','core','wapi'];

$config['ignore_route_methods'] = [];

$config['allowed_image_file_types'] = array('gif','jpg','png','jpeg');

//Modules Location
$config['modules_locations'] = array(
    APPPATH.'modules/' => '../modules/',
);

$config['migration_enabled'] = TRUE;
$config['migration_type'] = 'sequential';

//PDF files default storage path
$config['pdf_path'] = './uploads/pdf/';

//Default Theme
$config['theme'] = 'default';

//Super Admin Groups
$config['admin_groups'] = [1,3];

$config['api_login_module'] = 'customers/customers_api';
$config['api_user_module'] = 'customers/customers_api';
$config['api_login_method'] = '_api_login';

$config['kitchen_printers'] = KITCHEN_PRINTERS;
