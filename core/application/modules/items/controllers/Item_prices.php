<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Item_prices extends MY_Controller {

    public $module = 'item_prices';
    public $model = 'item_price';
    public $singular = 'Item Price';
    public $plural = 'Item Prices';
    public $list_url = '';
    public $edit_form = '';

    public function __construct()
    {
        parent::__construct();
        _model($this->model);
    }

}
