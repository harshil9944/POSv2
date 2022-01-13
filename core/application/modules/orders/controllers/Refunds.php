<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Refunds extends MY_Controller {

    public $module = 'orders/refunds';
    public $model = 'payment_refund';
    public $singular = 'Refund';
    public $plural = 'Refunds';
    public $language = 'orders/payments';
    public $edit_form = '';
    public function __construct()
    {
        parent::__construct();
        _model($this->model);
    }
    public function index()
	{
        _library('table');
        _model('order');

        $filters = [];
        $filters['filter'] = [];
	    $refunds = $this->_search($filters);
        $body=[];
        
        if($refunds) {
            foreach ($refunds as $refund) {

                $order = $this->order->single(['id'=>$refund['order_id']]);
             
                $amount_cell = [
                    'class' =>  'text-right',
                    'data'  =>  custom_money_format($refund['amount'],_get_setting('currency_sign',''))
                ];
                $method_filter = ['id'=>$refund['payment_method_id']];
                $mode = _get_module('core/payment_methods','_find',['filter'=>$method_filter]);
                
                $body[] = [
                    custom_date_format($refund['added'],'d/m/Y'),
                    ($order)?$order['order_no']:'',
                    ($order)?$order['billing_name']:'',
                    ($mode)?$mode['title']:'',
                    $amount_cell
                ];
            }
        }

        $heading = [
            'DATE',
            'ORDER#',
            'CUSTOMER NAME',
            'MODE',
            ['data'=>'AMOUNT','class'=>'text-center w-100p']
        ];

        _vars('table_heading',$heading);
        _vars('table_body',$body);
        $table = _view(DATA_TABLE_PATH);

        $page = [
            'singular'  =>  $this->singular,
            'plural'    =>  $this->plural,
            'add_url'   => '',
            'table'     =>  $table
        ];
        _vars('page_data',$page);

        _set_additional_component(LIST_XTEMPLATE_PATH,'outside');

        _set_page_heading($this->plural);
        _set_layout_type('wide');
        _set_layout(LIST_VIEW_PATH);
	}

    public function add() {

        $this->_add();

    }

    public function edit($id) {

        $category = $this->{$this->model}->single(['id'=>$id]);

        $this->_exclude_keys($category);
        $this->_sql_to_vue($category);

        _set_js_var('category',$category,'j');

        $this->_edit($id);

    }

    public function _action_put() {

        _model('items/item_category','item_category');

        $obj = _input('category');
        $this->_prep_obj($obj);

        $obj['added'] = sql_now_datetime();

        $affected_rows = $this->{$this->model}->insert($obj);
        if($affected_rows) {
            _response_data('redirect',base_url('items/categories'));
            return true;
        }else{
            return false;
        }
    }

    public function _action_post() {

        _model('items/item_category','item_category');

        $obj = _input('category');
        $this->_prep_obj($obj);

        $filter = ['id'=>$obj['id']];
        $affected_rows = $this->{$this->model}->update($obj,$filter);

        if($affected_rows) {
            _response_data('redirect',base_url('items/categories'));
            return true;
        }else{
            return false;
        }
    }

    public function _action_delete() {

        _model('items/item_category','item_category');
     $ignore_list = [];


     $id = _input('id');

     if(!in_array($id,$ignore_list)) {
        $filter = ['id' => $id];

        $affected_rows = $this->{$this->model}->delete($filter);

        if ($affected_rows) {
            _response_data('redirect', base_url('items/categories'));
            return true;
        } else {
            return false;
        }
     }else{
        _response_data('message','You cannot delete a protected user.');
        return false;
      }
    }

    private function _prep_obj(&$obj) {
        $this->_vue_to_sql($obj);
    }

    protected function _load_files() {
        if(_get_method()=='index') {

            _load_plugin(['dt']);

        }

	    if(_get_method()=='add' || _get_method()=='edit') {
            _load_plugin(['vue_multiselect']);
            _helper('control');

            _page_script_override('items/categories-form');

            $this->layout = 'categories_form_view';

        }
    }
}



