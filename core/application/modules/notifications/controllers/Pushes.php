<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pushes extends MY_Controller {

    public $module = 'notifications/pushes';
    public $model = 'webpush';
    public $singular = 'Push';
    public $plural = 'Pushes';
    public $language = 'notifications/notifications';
    public $edit_form = '';
    public function __construct()
    {
        parent::__construct();
    }
}
