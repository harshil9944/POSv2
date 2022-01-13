<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Logout extends MY_Controller {

    public $language = 'auth/auth';
    public $module = 'auth/logout';
	public function index()
	{
	    $this->view = false;
        _set_session('logged_in',false);
        _set_session('user_id','');
        _set_session('group_id','');
        _set_session('name','');
        _set_session('initial','');

        _destroy_session();
        redirect(LOGIN_ROUTE);
	}

}
