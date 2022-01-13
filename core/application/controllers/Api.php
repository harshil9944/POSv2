<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type, Origin, Authorization, X-Auth-Token, cache-control, x-api-key, id");
header("Access-Control-Allow-Methods: PUT, POST, GET, OPTIONS, DELETE");

defined('BASEPATH') OR exit('No direct script access allowed');

// This can be removed if you use __autoload() in config.php OR use Modular Extensions
/** @noinspection PhpIncludeInspection */
require APPPATH . 'libraries/REST_Controller.php';

/**
 * This is an example of a few basic user interaction methods you could use
 * all done with a hardcoded array
 *
 * @package         CodeIgniter
 * @subpackage      Rest Server
 * @category        Controller
 * @author          Phil Sturgeon, Chris Kacerguis
 * @license         MIT
 * @link            https://github.com/chriskacerguis/codeigniter-restserver
 */
class Api extends REST_Controller {

    public $module;
    public $method;
    public $class;
    public $module_method;
    function __construct()
    {
        // Construct the parent class
        parent::__construct();
        if(_get_request_method()=='options') {
            $this->set_response('GET,POST,HEAD', REST_Controller::HTTP_OK);
            die();
        }
        $this->module = _input_server('HTTP_MODULE');
        $this->method = _input_server('HTTP_METHOD');
        $this->module = ($this->module)?$this->module:_input('module');
        $this->method = ($this->method)?$this->method:_input('method');

        if(!$this->module) {
            $message = array(
                'status'    => 'error',
                'message'   => 'Module was not passed.'
            );
            $this->set_response($message, REST_Controller::HTTP_BAD_REQUEST);
            return false;
        }

        $api_key_variable = _get_config('rest_key_name');
        $key_name = 'HTTP_' . strtoupper(str_replace('-', '_', $api_key_variable));
        _vars('key',_input_server($key_name));
        _vars('user_id',_input_server("HTTP_ID"));
    }

    public function login_post() {

        $login_module = _get_config('api_login_module');
        $login_method = _get_config('api_login_method');

        $email = _input('email');
        $password = _input('password');

        if($email && $password) {

            $response = _get_module($login_module, $login_method, ['email'=>$email,'password'=>$password]);
            $response_code = $this->_get_response_code($response['type']);
            unset($response['type']);
            $this->set_response($response,$response_code);

        } else {
            $this->_send_bad_request('Email or Password not supplied');
        }

    }

    public function user_put() {

        $user_module = _get_config('api_user_module');

        $required_vars = array('email','password','full_name');
        $error = [];
        foreach ($required_vars as $var) {
            if(!_validate_post($var)) {
                $error['error_'.$var] = 1;
            }
        }

        //Validate Email
        if(!$error) {

            $user = _get_module($user_module,'_find',['filter'=>['email'=>_input('email')]]);

            if($user) {
                $error['error_email_duplicate'] = 1;
            }
        }

        if(!$error) {
            $data = [];
            foreach ($required_vars as $var) {
                $data[$var] = _input($var);
            }

            unset($data['full_name']);

            $data['first_name'] = _input('full_name');

            $params = [];
            $params['data'] = $data;
            $result = _get_module($user_module,'_api_register',$params);

            if($result) {

                $message = [
                    'status' => 'ok',
                    'message' => 'Registration was successful.'
                ];

                $this->set_response($message, REST_Controller::HTTP_CREATED); // CREATED (201) being the HTTP response code
            } else {
                $data = [
                    'status'    =>  'error',
                    'data'      =>  $error
                ];
                $this->response($data, REST_Controller::HTTP_BAD_REQUEST); // BAD_REQUEST (400) being the HTTP response code
            }

        }else{
            $data = [
                'status'    =>  'error',
                'data'      =>  $error
            ];
            $this->response($data, REST_Controller::HTTP_BAD_REQUEST); // BAD_REQUEST (400) being the HTTP response code
        }
    }

    public function user_get() {

        $user_module = _get_config('api_user_module');
        $user_method = '_single';

        $id = _input_server("HTTP_ID");

        if($id) {
            $ignore_fields = ['deleted','gender','image','dob','theme','mobile','status','group_id','last_name','default_page','password'];
            $response = _get_module($user_module, $user_method, ['id' => $id, 'ignore_fields' => $ignore_fields]);
            if($response['status']=='ok') {
                $response['obj']['fullName'] = $response['obj']['firstName'];
                unset($response['obj']['firstName']);
            }
            $response_code = $this->_get_response_code($response['type']);
            unset($response['type']);
            $this->set_response($response, $response_code);
        }else{
            $this->_send_bad_request('User was not found.');
        }

    }

    public function validate_post() {

        $message = array(
            'status'    =>  'ok',
            'message'   =>  'Valid Login'
        );

        $this->set_response($message, REST_Controller::HTTP_CREATED);

    }

    public function request_get() {
        return $this->_request();
    }

    public function request_put() {
        return $this->_request();
    }

    public function request_post() {
        return $this->_request();
    }

    public function request_delete() {
        return $this->_request();
    }

    private function _request() {

        $request_method = _get_request_method();

        $module_array = explode('/',$this->module);

        $this->class = (isset($module_array[1]) && $module_array[1])?$module_array[1]:$module_array[0];

        if($this->method) {
            $this->module_method = '_' . $this->method . '_' . $request_method;
        }else{
            $this->module_method = '_action_' . $request_method;
        }

        //TODO Add allowed methods filter here

        $this->load->module($this->module);

        if(method_exists($this->{$this->class},$this->module_method)) {
            if($response=$this->{$this->class}->{$this->module_method}()) {
                $response_code = $this->_get_response_code($response['type']);
                unset($response['type']);
                $this->set_response($response,$response_code);
            }else{
                $message = array(
                    'status'    => 'error',
                    'message'   => 'Error running Function'
                );
                $this->set_response($message, REST_Controller::HTTP_BAD_REQUEST);
            }
        }else{

            $message = array(
                'status'    => 'error',
                'message'   => 'Method not found'
            );
            $this->set_response($message, REST_Controller::HTTP_BAD_REQUEST);
        }
        return false;
    }

    private function _send_bad_request($error_msg) {

        $message = array(
            'status'    => 'error',
            'message'   => $error_msg
        );
        $this->set_response($message, REST_Controller::HTTP_BAD_REQUEST);

    }

    public function _get_response_code($type) {
        if($type!=null) {
            return constant("REST_Controller::$type");
        }
        return REST_Controller::HTTP_INTERNAL_SERVER_ERROR;
    }
}
