<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Unauthorised extends MY_Controller {

    public $module = 'unauthorised';
    public $model = '';
    public $singular = 'Unauthorised';
    public $plural = 'Unauthorised';
    public $edit_form = '';
    public function __construct()
    {
        parent::__construct();
    }

    public function index() {

        _set_page_heading('Access is ' . $this->plural);
        _set_layout('system/unauthorised_view');

	}

}
