<?php
defined('BASEPATH') OR exit('No direct script access allowed');
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type, Origin, Authorization, X-Auth-Token, X-Requested-With, cache-control, x-api-key, id, method, module");
header("Access-Control-Allow-Methods: PUT, POST, GET, OPTIONS, DELETE");

class Action extends API_Controller {

	public function __construct() {
		parent::__construct();
	}

	public function index() {

        $request_method = _get_request_method();
        if(_input_server('HTTP_METHOD_OVERRIDE')) {
            $method_override = _input_server('HTTP_METHOD_OVERRIDE');
            $allowed_methods = ['get','post','put','delete'];
            if(in_array($method_override,$allowed_methods)) {
                $request_method = $method_override;
            }
        }

        if(!$this->module) {
            _response_data('message','Module was not passed.');
            $this->_error_response();
        }

        if($this->method) {
            $module_method = '_' . $this->method . '_' . $request_method;
        }else{
            $module_method = '_action_' . $request_method;
        }

        $module_array = explode('/',$this->module);

        $this->load->module($this->module);

        $class = (isset($module_array[1]) && $module_array[1])?$module_array[1]:$module_array[0];

        if(method_exists($this->{$class},$module_method)) {
            if($this->{$class}->{$module_method}()) {
                $this->_ok_response();
            }else{
                $this->_error_response();
            }
        }else{
            _response_data('message','Method does not exist.');
            $this->_error_response();
        }

    }

    public function __destruct() {
        parent::__destruct();
    }

}
