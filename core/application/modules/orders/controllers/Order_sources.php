<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Order_sources extends MY_Controller {

    public $module = 'orders/order_sources';
    public $model = 'order_source';
    public $singular = 'Order source';
    public $plural = 'Order sources';
    public $language = 'orders/orders';
    public $edit_form = '';
    public $form_xtemplate = 'order_sources_form_xtemplate';
    public function __construct()
    {
        parent::__construct();
        _model($this->model);
        
    }
    public function index()
	{
        
        _library('table');
        $filters = [];
        $filters['filter'] = [];
	    $orders_sources = $this->_search($filters);

        $body=[];

        if($orders_sources) {
            foreach ($orders_sources as $orders_source) {
                $action = _edit_link(base_url('orders/order_sources/edit/'.$orders_source['id'])) . _vue_delete_link('handleRemove('.$orders_source['id'].')');
                $action_cell = [
                    'class' =>  'text-center w-110p',
                    'data'  =>  $action
                ];
               
                $body[] = [
                    'title'		         =>	$orders_source['title'],
                    'print_label'		 =>	$orders_source['print_label'],
                    $action_cell
                ];
            }
        }

        $heading = [
            'TITLE',
            'PRINT LABEL',
            ['data'=>'Action','class'=>'text-center w-110p']
        ];
        _vars('table_heading',$heading);
        _vars('table_body',$body);
        $table = _view(DATA_TABLE_PATH);



        $page = [
            'singular'  =>  $this->singular,
            'plural'    =>  $this->plural,
            'add_url'   =>  base_url('orders/order_sources/add'),
            'table'         =>  $table,

        ];
        _vars('page_data',$page);

        _set_additional_component(LIST_XTEMPLATE_PATH,'outside');

        _set_page_heading($this->plural);
        _set_layout_type('wide');
        _set_layout(LIST_VIEW_PATH);
	}

    

    public function add() {

        $this->_add();

        /*_set_js_var('back_url',base_url('areas'),'s');
        _set_js_var('mode','add','s');
	    _set_page_heading('Add areas');
	    _set_layout('areas_form_view');*/

    }

    public function edit($id) {


        $orders_source = $this->{$this->model}->single(['id'=>$id]);

        $this->_exclude_keys($orders_source);
        $this->_sql_to_vue($orders_source);

        _set_js_var('order_source',$orders_source,'j');

        $this->_edit($id);

        /*_set_js_var('back_url',base_url('users'),'s');
        _set_js_var('mode','edit','s');
        _set_js_var('user',$user,'j');*/

      //  _set_page_heading('Edit User');
        //_set_layout('areas_form_view');

    }

    public function _action_put() {

        $obj = _input('source');
        $this->_prep_obj($obj);
        $obj['added'] = sql_now_datetime();
        $affected_rows = $this->{$this->model}->insert($obj);
        if($affected_rows) {
            _response_data('redirect',base_url('orders/order_sources'));
            return true;
        }else{
            return false;
        }

    }

    public function _action_post() {
        $obj = _input('source');
        $this->_prep_obj($obj);
        $filter = ['id'=>$obj['id']];
        $affected_rows = $this->{$this->model}->update($obj,$filter);
        if($affected_rows) {
            _response_data('redirect',base_url('orders/order_sources'));
            return true;
        }else{
            return false;
        }
    }

    public function _action_delete() {

        _model('order_source');

        $ignore_list = [1];

        $id = _input('id');

        if(!in_array($id,$ignore_list)) {
            $filter = ['id' => $id];

            $affected_rows = $this->{$this->model}->delete($filter);

            if ($affected_rows) {
                _response_data('redirect', base_url('orders/order_sources'));
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
   

    public function _install() {
        return true;
    }

    public function _uninstall() {
        return true;
    }

    protected function _load_files() {

	    if(_get_method()=='index') {

            _load_plugin(['dt']);

        }

	    if(_get_method()=='add' || _get_method()=='edit') {

           
            _page_script_override('orders/order_sources-form');

            $this->layout = 'order_sources_form_view';

        }

    }
}
