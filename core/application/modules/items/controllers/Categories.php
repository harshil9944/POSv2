<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Categories extends MY_Controller {

    public $module = 'items/categories';
    public $model = 'item_category';
    public $singular = 'Category';
    public $plural = 'Categories';
    public $language = 'items/items';
    public $edit_form = '';
    public $form_xtemplate = 'categories_form_xtemplate';
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
	    $categories = $this->_search($filters);

        $body=[];

        if($categories) {
            foreach ($categories as $category) {
                $action = _edit_link(base_url('items/categories/edit/'.$category['id'])) . _vue_delete_link('handleRemove('.$category['id'].')');
                $action_cell = [
                    'class' =>  'text-center w-110p',
                    'data'  =>  $action
                ];

                $body[] = [
                    'title'		    =>	$category['title'],
                    'sort_order'	=>	$category['sort_order'],
                    'pos_status'	=>	$category['pos_status']?'Yes':'No',
                    'web_status'	=>	$category['web_status']?'Yes':'No',
                    'app_status'	=>	$category['app_status']?'Yes':'No',
                    $action_cell
                ];
            }
        }

        $heading = [
            'TITLE',
            'SORT ORDER',
            'POS STATUS',
            'WEB STATUS',
            'APP STATUS',
            ['data'=>'Action','class'=>'text-center w-100p']
        ];

        _vars('table_heading',$heading);
        _vars('table_body',$body);
        $table = _view(DATA_TABLE_PATH);

        $page = [
            'singular'  =>  $this->singular,
            'plural'    =>  $this->plural,
            'add_url'   =>  base_url('items/categories/add'),
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
        _clear_cache('item');
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
        _clear_cache('item');
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
        _clear_cache('item');
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



