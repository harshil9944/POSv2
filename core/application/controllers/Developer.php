<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Developer extends MY_Controller {

    public $module = 'developer';
    public function __construct()
    {
        parent::__construct();
    }

    public function index() {
        _set_layout_type('wide');
        _set_additional_component('system/developer_xtemplate','outside');
		_set_layout('system/developer_view');
    }

    public function _update_primary_print_server_post() {
        $id = _input('id');
        if($id) {
            _set_setting(BROWSER_ID_KEY,$id);
            return true;
        }
    }

}
