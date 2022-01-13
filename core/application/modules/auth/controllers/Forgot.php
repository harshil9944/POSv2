<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Forgot extends MY_Controller {

    public $language = 'auth/auth';
    public function __construct()
    {
        $this->check_authentication = false;
        parent::__construct();
        _language('auth');
    }

	public function index()
	{
	    _set_template('login');
	    _set_page_title('Forgot Password');
		_set_layout('forgot_view');
	}

	public function _action_post() {

        $this->view = false;
        _model('auth_model','auth_m');

        $email = _input('email');

        $user = $this->auth_m->get_forgot_user($email);

        if($user) {
            $user_id = $user['id'];
            $code = $this->auth_m->generate_forgot($user_id);

            //TODO Email sending pending
            _response_data('message',_line('text_forgot_email_success'));
            return true;
        }else{
            _response_data('message',_line('error_forgot_not_found'));
            return false;
        }

    }

    function _load_files() {

    }
}
