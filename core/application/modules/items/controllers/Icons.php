<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Icons extends MY_Controller {

    public $module = 'items/icons';
    public $model = 'item_icon';
    public $singular = 'Icon';
    public $plural = 'Icons';
    public $language = 'items/items';
    public $edit_form = '';
    public $form_xtemplate = 'icons_form_xtemplate';
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
	    $icons = $this->_search($filters);
        $body=[];
        if($icons) {
            foreach ($icons as $icon) {
                $action = _edit_link(base_url('items/icons/edit/'.$icon['id'])) . _vue_delete_link('handleRemove(\''.$icon['id'].'\')');
                $action_cell = [
                    'class' =>  'text-center w-110p',
                    'data'  =>  $action
                ];
                $body[] = [
                    'icon'		    =>	'<i class="'.$icon['title'].' fa-2x"></i>',
                    'title'	        =>	$icon['title'],
                    $action_cell
                ];
            }
        }
        $heading = [
            'Icon',
            'Code',
            ['data'=>'Action','class'=>'text-center w-100p']
        ];
        _vars('table_heading',$heading);
        _vars('table_body',$body);
        $table = _view(DATA_TABLE_PATH);
        $page = [
            'singular'  =>  $this->singular,
            'plural'    =>  $this->plural,
            'add_url'   =>  base_url('items/icons/add'),
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
        $icon = $this->{$this->model}->single(['id'=>$id]);
        $this->_exclude_keys($icon);
        $this->_sql_to_vue($icon);
        _set_js_var('icon',$icon,'j');
        $this->_edit($id);
    }

    public function _action_put() {
        _model('items/item_icon','item_icon');
        $obj = _input('icon');
        $this->_prep_obj($obj);
        $obj['added'] = sql_now_datetime();
        $affected_rows = $this->{$this->model}->insert($obj);
        if($affected_rows) {
            _response_data('redirect',base_url('items/icons'));
            return true;
        }else{
            return false;
        }
    }

    public function _action_post() {
        _model('items/item_icon','item_icon');
        $obj = _input('icon');
        $this->_prep_obj($obj);
        $filter = ['id'=>$obj['id']];
        $affected_rows = $this->{$this->model}->update($obj,$filter);
        if($affected_rows) {
            _response_data('redirect',base_url('items/icons'));
            return true;
        }else{
            return false;
        }
    }

    public function _action_delete() {
        _model('items/item_icon','item_icon');
        $ignore_list = [];
        $id = _input('id');
        if(!in_array($id,$ignore_list)) {
            $filter = ['id' => $id];
            $affected_rows = $this->{$this->model}->delete($filter);

            if ($affected_rows) {
                _response_data('redirect', base_url('items/icons'));
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
            _page_script_override('items/icons-form');
            $this->layout = 'icons_form_view';
        }
    }
}



