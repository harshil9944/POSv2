<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Profile extends MY_Controller {

    public $language = 'users/profile';
    public $module = 'users/profile';
	public function index()
	{
	    _model(array('user','group'));
        _language('users');

        //dump_exit(_line('text_first_name'));

        _set_page_heading('Profile');
        _set_layout('profile_view');
	}

	protected function _load_files() {

	    $slug = _uri_string();
        if($slug==$this->module . '/index') {

            _load_plugin(['dt']);

        }

    }
}
