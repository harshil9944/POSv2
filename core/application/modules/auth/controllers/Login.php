<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends MY_Controller {

    public $language = 'auth/auth';
    public $module = 'auth/login';
    public function __construct()
    {
        parent::__construct();
        if(_logged_in()) {
            $this->view = false;
            $this->_redirect('dashboard','refresh');
        }
        _language('auth');
    }

    public function index() {
	    _set_template('login');
	    _set_page_title('Login');
		_set_layout('login_view');
	}

    public function _action_post() {

        _model('auth_model','auth_m');

        $email = _input('email',true);
        $password = _input('password',true);

        $user = $this->auth_m->login($email,$password);

        if($user) {
            if($user['panel_login']) {

                $warehouses = _get_module('employees','_warehouses',['user_id'=>$user['id']]);

                $user_warehouses = [];
                if($warehouses) {
                    foreach ($warehouses as $warehouse) {
                        $user_warehouses[] = [
                            'id'    =>  $warehouse['warehouse_id'],
                            'type'  =>  '',
                        ];
                    }
                }
                $register = _get_module('employees','_get_register',['user_id'=>$user['id']]);
                _set_session('logged_in', true);
                _set_session('user_id', $user['id']);
                _set_session('register_id',(@$register['register_id'])?$register['register_id']:1);
                _set_session('group_id', $user['group_id']);
                _set_session('rank', $user['rank']);
                _set_session('warehouses',$user_warehouses);
                _set_session('short_name', substr($user['first_name'], 0, 1) . '. ' . $user['last_name']);
                _set_session('name', $user['first_name'] . ' ' . $user['last_name']);
                _set_session('initial', strtoupper(substr($user['first_name'], 0, 1) . substr($user['last_name'], 0, 1)));
                _set_session('userEmail', $user['email']);

                $redirect = $this->auth_m->get_redirect($user);
                if (!$redirect) {
                    $redirect = base_url('dashboard');
                }

                _response_data('redirect', $redirect);
            }else{
                _response_data('message','Invalid Email or Password or Login Disabled.');
                return false;
            }

            return true;
        }else{
            _response_data('message','Invalid Email or Password.');
            return false;
        }
    }

	function _load_files() {

    }
}
