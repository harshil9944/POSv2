<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends MY_Controller {

    public $module = 'auth';
    public $model = 'auth_model';
    public $singular = 'Auth';
    public $plural = 'Auth';
    public $language = 'auth/auth';
    public $edit_form = '';
    public function __construct()
    {
        parent::__construct();
        _model($this->model);
    }

    public function _get_menu() {

        $menus = [];

        return $menus;

    }

    public function _install() {
        return true;
    }

    public function _uninstall() {
        return true;
    }
}
