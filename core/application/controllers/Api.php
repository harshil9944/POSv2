<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type, Origin, Authorization, X-Auth-Token, cache-control,x-requested-with, x-api-key, id");
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
            $this->set_response('GET,PUT,DELETE,POST,HEAD', REST_Controller::HTTP_OK);
            die();
        }

        /*$this->module = _input_server('HTTP_MODULE');
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
        }*/

        $api_key_variable = _get_config('rest_key_name');
        $key_name = 'HTTP_' . strtoupper(str_replace('-', '_', $api_key_variable));
        _vars('key',_input_server($key_name));
        _vars('user_id',_input_server("HTTP_ID"));
    }


    //Common Methods Start
    public function login_post() {

        $login_method = _get_config('api_login_method');
         $login_module = _get_config('api_order_module');

        $obj = json_decode(file_get_contents('php://input'),true);
        $email = $obj['email'];
        $password = $obj['password'];

        if($email && $password) {

            $response = _get_module($login_module, $login_method, $obj);
            $response_code = $this->_get_response_code($response['type']);
            unset($response['type']);

            $this->set_response($response,$response_code);

        } else {
            $this->_send_bad_request('Email or Password not supplied');
        }

    }

    public function print_queue_get() {

        $this->load->vars('key',rand(4567,97946));

        $set =_input('stop') ? (int)_input('stop') == 1 : false;

        $print_queue_list = _get_module('pos','_get_print_queue');
        if(!$set){
            $queueIds = isset($print_queue_list) && $print_queue_list ? array_column($print_queue_list,'order_id') :[];
            $ids = implode( ',', $queueIds );
            if($ids){
                _db_query("UPDATE ord_print_queue SET printing=1 WHERE order_id IN (" . $ids . ")");
            }
        }

        $response = [
            'status'        => 'ok',
            'queue_count'   => $print_queue_list ? count($print_queue_list) : 0,
            'queue'         => $print_queue_list ?: [],
            'orders'        => [],
            'printers'      => [],
        ];
        $response_code = 200;
        if($print_queue_list) {
            $response['orders'] = _get_module('pos', '_get_print_orders', $print_queue_list);
            $response['printers'] = ['kitchen'];
        }
        $this->set_response($response,$response_code);
    }

    public function clear_print_queue_post() {

        $this->load->vars('key',rand(4567,97946));

        $postArray = json_decode(file_get_contents('php://input'),true);
        if(isset($postArray['q']) && is_array($postArray['q'])) {
            log_message("error","QueueIds Received: ".implode(',',$postArray['q']). ". Time:".sql_now_datetime());
            $queue = $postArray['q'];
            $queueIds = [];
            if($queue) {
                foreach($queue as $q) {
                    $queueIds[] = $q['id'];
                    _get_module('pos', '_set_printed', $q['order_id']);
                }
            }
            if($queueIds) {
                _get_module('pos', '_clear_printed_queue', array_values(array_unique($queueIds)));
            }
        }
        $this->set_response(['status'=>'ok'],200);
    }

    public function validate_session_post(){

        $validate_session_method = _get_config('api_validate_session_method');
        $order_module = _get_config('api_order_module');

       $obj = json_decode(file_get_contents('php://input'),true);
       $key = $obj['key'];

       $response = _get_module($order_module, $validate_session_method, $key);
       $response_code = $this->_get_response_code($response['type']);
       unset($response['type']);

       $this->set_response($response,$response_code);
    }

    public function populate_items_get(){

        $module = _get_config('api_item_module');
        $method =  _get_config('api_populate_items_method');



        $response = _get_module($module, $method, []);
        $response_code = $this->_get_response_code($response['type']);
        unset($response['type']);
        $this->set_response($response,$response_code);
    }
    public function populate_menu_items_get(){

        $module = _get_config('api_item_module');
        $method =  _get_config('api_populate_menu_items_method');
        $response = _get_module($module, $method, []);
        $response_code = $this->_get_response_code($response['type']);
        unset($response['type']);
        $this->set_response($response,$response_code);
    }

    public function logout_post() {

        $logout_module = _get_config('api_order_module');
        $logout_method = '_api_logout';
        $data = json_decode(file_get_contents('php://input'),true);
        $key = $data['key'];
        $response = _get_module($logout_module, $logout_method, $key);
        $response_code = $this->_get_response_code($response['type']);
        unset($response['type']);

        $this->set_response($response,$response_code);
    }

    public function register_post() {


        $register_module = _get_config('api_order_module');
        $register_method = '_api_register';

        $data = json_decode(file_get_contents('php://input'),true);
        $obj = $data['obj'];

        $response = _get_module($register_module, $register_method, $obj);
        $response_code = $this->_get_response_code($response['type']);
        unset($response['type']);

        $this->set_response($response,$response_code);
    }
    public function order_post() {


        $register_module = _get_config('api_order_module');
        $register_method = '_api_order';

        $data = json_decode(file_get_contents('php://input'),true);



        $response = _get_module($register_module, $register_method, $data);

        $response_code = $this->_get_response_code($response['type']);
        unset($response['type']);

        $this->set_response($response,$response_code);
    }

    public function password_post() {

        _helper('password');

        $user_id = $this->_get_user_id();

        $required_vars = array('current_password','new_password','confirm_password');
        $error = [];
        foreach ($required_vars as $var) {
            if(!_validate_post($var)) {
                $error['error_'.$var] = 1;
            }
        }

        $current_password = _input_post('current_password');
        $new_password = _input_post('new_password');
        $confirm_password = _input_post('confirm_password');

        if(!$error) {
            $filters = [
                'id'        =>  $user_id,
                'password'  =>  hash_password($current_password)
            ];
            $user = _get_module('users/users_api','_find',['filter'=>$filters]);
            if(!$user) {
                $error['error_invalid_old_password'] = 1;
            }
        }

        if(!$error) {
            if($new_password!=$confirm_password) {
                $error['error_password_do_not_match'] = 1;
            }
        }

        if(!$error) {

            $data = [
                'password'  =>  hash_password($new_password)
            ];
            $filter = [
                'id'    =>  $user_id
            ];

            $result = _get_module('users/users_api','_update',['data'=>$data,'filter'=>$filter]);

            if($result) {
                $response = [
                    'status' => 'ok',
                    'message' => 'Your password has been updated successfully.'
                ];
                $this->set_response($response, REST_Controller::HTTP_OK);
                return true;
            }else{
                $data = [
                    'status'    =>  'error',
                    'message'   =>  'Password update failed.'
                ];
                $this->response($data, REST_Controller::HTTP_BAD_REQUEST); // BAD_REQUEST (400) being the HTTP response code
                return false;
            }

        }else{
            $errors = [];
            foreach ($error as $key=>$item) {
                $msg = $this->_get_error_msg($key);
                $errors[$key] = ($msg)?$msg:1;
            }
            $data = [
                'status'    =>  'error',
                'data'      =>  $errors
            ];
            $this->response($data, REST_Controller::HTTP_BAD_REQUEST); // BAD_REQUEST (400) being the HTTP response code
        }
    }
    private function _get_error_msg($key = null){
        return true;
    }

    public function forgot_post() {

        _helper('email');

        $forgot_module = _get_config('api_customer_module');
        $forgot_method = '_generate_forgot';

        $data = json_decode(file_get_contents('php://input'),true);
        $user = _get_module($forgot_module,'_get_forgot_user',['email'=>$data['email']]);
        if($user) {
            $code = _get_module($forgot_module,$forgot_method,['user_id'=>$user['id']]);
            //Send Email
            $details = [
                'full_name' =>  $user['first_name'] . ' ' . $user['last_name'],
                'company'   =>  CORE_APP_TITLE,
                'url'       =>  _get_config('retrieve_password_url') . "?code=$code"
            ];
            _vars('details',$details);


            $to = ['email' => $user['email']];
            $subject = "Welcome to Inntech";

            $text = $subject;

            $html_data =  _view('email_templates/retrieve_password');

            _ci_send( $to, $subject, $text, $html_data );

            $response = [
                'status' => true,
                'message' => 'An email with instruction has been sent to your registered Email ID.',
                'type' => 'HTTP_CREATED',
                'expiresIn' => 86400,
            ];

            $response_code = $this->_get_response_code($response['type']);
            unset($response['type']);

            $this->set_response($response,$response_code);

        }else{
            $this->_send_bad_request('Email was not found in our records');
        }

    }

    public function retrieve_post() {

        $required_vars = array('code','password','confirm_password');
        $error = [];
        foreach ($required_vars as $var) {
            if(!_validate_post($var)) {
                $error['error_'.$var] = 1;
            }
        }

        if(!$error) {

            $code = _input('code');
            $password = _input('password');
            $confirm_password = _input('confirm_password');

            if($password!==$confirm_password) {
                $error['error_mismatch'] = 1;
            }

        }
        if(!$error) {
            $params = [
                'code' => $code,
                'password' => $password
            ];
            $result = _get_module('users/users_api', '_process_retrieve', $params);

            if($result) {
                $response = [
                    'status' => 'ok',
                    'message' => 'Your password has been reset successfully.',
                ];
                $this->set_response($response, REST_Controller::HTTP_OK);
                return true;
            }else{
                $error_msg = _get_var('retrieve_err_msg','There was a problem while performing your reset process. Please try again.');
                $this->_send_bad_request($error_msg);
                return false;
            }
        }
        $errors = [];
        foreach ($error as $key=>$item) {
            $msg = $this->_get_error_msg($key);
            $errors[$key] = ($msg)?$msg:1;
        }
        $data = [
            'status'    =>  'error',
            'data'      =>  $errors
        ];
        $this->response($data, REST_Controller::HTTP_BAD_REQUEST); // BAD_REQUEST (400) being the HTTP response code
        return false;
    }
    //Common Methods End

    //Customer Methods Start
    public function customer_put() {

        $user_module = _get_config('api_user_module');

        $required_vars = array('email','password','phone','first_name','last_name');
        $error = [];
        foreach ($required_vars as $var) {
            if(!_validate_post($var)) {
                $error['error_'.$var] = 1;
            }
        }

        //Validate Email
        if(!$error) {

            $invalid_email = false;
            $email = _input('email');
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $error['error_email_invalid'] = 1;
                $invalid_email = true;
            }

            if(!$invalid_email) {
                $user = _get_module($user_module, '_find', ['filter' => ['email' => $email]]);

                if ($user) {
                    $error['error_email_duplicate'] = 1;
                }
            }

            $duplicate_phone = false;
            $phone = _input('phone');
            if(!$duplicate_phone) {
                $user = _get_module($user_module, '_find', ['filter' => ['phone' => $phone]]);

                if ($user) {
                    $error['error_phone_duplicate'] = 1;
                }
            }

        }

        if(!$error) {
            $data = [];
            foreach ($required_vars as $var) {
                $data[$var] = _input($var);
            }
            //Optional Variables
            $data['added']                 = sql_now_datetime();
            $data['dob']                   = null;
            $data['email_verified']        = 0;
            $data['phone_verified']        = 0;
            $data['status']                = 1;
            $data['deleted']               = 0;
            $params = [];
            $params['obj'] = $data;
            $result = _get_module($user_module,'_api_insert',$params);

            if($result) {

                $login_module = _get_config('api_login_module');
                $login_method = _get_config('api_login_method');

                $email = _input('email');
                $password = _input('password');

                $response = _get_module($login_module, $login_method, ['email'=>$email,'password'=>$password]);
                $response_code = $this->_get_response_code($response['type']);
                unset($response['type']);

                /* $token = _input('device_token');

                if($token && $response_code == 201) {

                    $module = false;
                    if($response['userType'] == 'customer') {
                        $module = 'customers/devices_api';
                    }elseif($response['userType'] == 'coach') {
                        $module = 'coaches/devices_api';
                    }
                    if($module) {
                        $user_id = $response['id'];
                        _get_module($module, '_register', ['token' => $token, 'user_id' => $user_id]);
                    }
                    $response['obj']['settings'] = [
                        'notificationDuration'  =>  _get_setting('notification_duration',30)
                    ];

                    /*if($response['userType'] == 'customer') {
                        $customer_id = $response['id'];
                        _get_module('customers/devices_api', '_register', ['token' => $token, 'customer_id' => $customer_id]);
                    }elseif($response['userType'] == 'coach') {
                        //TODO Register Coach Device
                    }
                } */

                $this->set_response($response,$response_code);

                /*$message = [
                    'status' => 'ok',
                    'message' => 'Registration was successful.'
                ];

                $this->set_response($message, REST_Controller::HTTP_CREATED); // CREATED (201) being the HTTP response code*/
            } else {
                $data = [
                    'status'    =>  'error',
                    'data'      =>  $error
                ];
                $this->response($data, REST_Controller::HTTP_BAD_REQUEST); // BAD_REQUEST (400) being the HTTP response code
            }

        }else{
            $errors = [];
            foreach ($error as $key=>$item) {
                $msg = $this->_get_error_msg($key);
                $errors[$key] = ($msg)?$msg:1;
            }
            $data = [
                'status'    =>  'error',
                'data'      =>  $errors
            ];
            $this->response($data, REST_Controller::HTTP_BAD_REQUEST); // BAD_REQUEST (400) being the HTTP response code
        }
    }

    public function customer_post() {

            $user_module = _get_config('api_user_module');
            $user_method = '_api_update';

            $id = _input_server("HTTP_ID");

            $update_data = [];
            //$update_data['first_name'] = _input_post('full_name');

            if (_input_post('timezone')) {
                $update_data['timezone'] = str_replace('+', '', _input_post('timezone'));
            }

            //Optional Variables
            $update_data['password'] = _input_post('password');
            $update_data['gender'] = _input_post('gender');
            $update_data['dob'] = _input_post('dob');
            $update_data['height'] = _input_post('height');
            $update_data['height_unit'] = _input_post('height_unit');
            $update_data['weight'] = _input_post('weight');
            $update_data['weight_unit'] = _input_post('weight_unit');
            $update_data['address'] = _input_post('address');
            $update_data['fat_loss'] = _input_post('fat_loss');
            $update_data['general_fitness'] = _input_post('general_fitness');
            $update_data['muscle_gain'] = _input_post('muscle_gain');
            $update_data['body_building'] = _input_post('body_building');
            $data['stamina'] = _input('stamina');
            $update_data['competition_prep'] = _input_post('competition_prep');
            $update_data['gym_before'] = _input_post('gym_before');
            $update_data['training_experience'] = _input_post('training_experience');
            $update_data['has_medical_condition'] = _input_post('has_medical_condition');
            $update_data['medical_condition'] = _input_post('medical_condition');
            $update_data['area_id'] = _input_post('area_id');

            $result = _get_module($user_module, $user_method, ['data' => $update_data, 'filter' => ['id' => $id]]);

            if ($result) {
                $response = [
                    'status' => 'ok',
                    'message' => 'Profile Updated Successfully',
                ];
                $this->set_response($response, REST_Controller::HTTP_OK);
            } else {
                $response = [
                    'status' => 'error',
                    'message' => 'Profile update failed',
                ];
                $this->set_response($response, REST_Controller::HTTP_BAD_REQUEST);
            }

    }

    public function customer_get() {

        $user_module = _get_config('api_user_module');
        $user_method = '_single';

        $id = _input_server("HTTP_ID");

        $ignore_fields = ['deleted', 'added', 'status', 'password','email_verified','phone_verified','dob'];
        $response = _get_module($user_module, $user_method, ['id' => $id, 'ignore_fields' => $ignore_fields]);

        $response_code = $this->_get_response_code($response['type']);
        unset($response['type']);
        $this->set_response($response, $response_code);
    }

    public function validate_post() {

        $message = array(
            'status'    =>  'ok',
            'message'   =>  'Valid Login'
        );

        $this->set_response($message, REST_Controller::HTTP_CREATED);

    }

    /*public function request_get() {
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
    }*/


    private function _get_user_id() {
        return _input_server("HTTP_ID");
    }

    private function _get_group_id() {
        $user_id = $this->_get_user_id();
        $user = _get_module('users/users_api', '_find', ['filter' => ['id' => $user_id]]);
        if (!$user) {
            return false;
        }
        return $user['group_id'];
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
    //System Methods End
}
