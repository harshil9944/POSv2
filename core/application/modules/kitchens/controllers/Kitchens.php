<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Kitchens extends MY_Controller {

    public $module = 'kitchens';
    public $model = 'kitchen';
    public $singular = 'Kitchen';
    public $plural = 'Kitchens';
    public $language = 'kitchens/kitchens';
    public $edit_form = '';
    public $form_xtemplate = 'kitchens_form_xtemplate';
    public function __construct()
    {
        parent::__construct();
        _model($this->model);
        $params = [
            'migration_path' => KITCHENS_MIGRATION_PATH,
            'migration_table' => KITCHENS_MIGRATION_TABLE
        ];
        $this->init_migration($params);
    }
    public function index()
	{
        _get_module('printers', 'printer');
        _get_module('printers/templates', 'template');
        _library('table');
        $filters = [];
        $filters['filter'] = [];
	    $kitchens = $this->_search($filters);
        $body=[];

        if($kitchens) {
            foreach ($kitchens as $kitchen) {
                $action = _edit_link(base_url('kitchens/edit/'.$kitchen['id'])) . _vue_delete_link('handleRemove('.$kitchen['id'].')');
                $action_cell = [
                    'class' =>  'text-center w-70p',
                    'data'  =>  $action
                ];
                $printer_id = $kitchen['printer_id'];
                $kitchen['printer_title'] = '';
                if($printer_id) {
                    $printer = $this->printer->single(['id'=>$printer_id]);
                    $kitchen['printer_title'] = (@$printer['title'])?$printer['title']:'';
                }

                $template_id = $kitchen['template_id'];
                $kitchen['template_title'] = '';
                if($template_id) {
                    $template = $this->template->single(['id'=>$template_id]);
                    $kitchen['template_title'] = (@$template['title'])?$template['title']:'';
                }

                $body[] = [
                    'title'		         =>	$kitchen['title'],
                    'printer_title'		 =>	$kitchen['printer_title'],
                    'template_title'	 =>	$kitchen['template_title'],

                    $action_cell
                ];
            }
        }

        $heading = [
            'TITLE',
            'PRINTER',
            'TEMPLATE',
            ['data'=>'Action','class'=>'text-center w-110p']
        ];
        _vars('table_heading',$heading);
        _vars('table_body',$body);
        $table = _view(DATA_TABLE_PATH);



        $page = [
            'singular'  =>  $this->singular,
            'plural'    =>  $this->plural,
            'add_url'   =>  base_url('kitchens/add'),
            'table'         =>  $table,

        ];
        _vars('page_data',$page);

        _set_additional_component(LIST_XTEMPLATE_PATH,'outside');

        _set_page_heading($this->plural);
        _set_layout_type('wide');
        _set_layout(LIST_VIEW_PATH);
	}

    public function _get_menu() {
        $menus = [];
        $kitchens = [];
        $kitchens[] = [
            'name'	    =>  'Kitchens',
            'class'     =>  '',
            'group'     =>  '',
            'icon'      =>  'basket-loaded',
            'path'      =>  'kitchens',
            'module'    =>  'kitchens',
            'children'  =>  []
        ];
        $menus[] = array(
            'id'        => 'menu-kitchens',
            'class'     => '',
            'icon'      => 'si si-handbag',
            'group'     => 'module',
            'name'      => 'Kitchens',
            'path'      => 'kitchens',
            'module'    => 'kitchens',
            'priority'  => 5,
            'children'  => $kitchens
        );
        return $menus;
    }

    public function add() {
        $this->_add();
        /*_set_js_var('back_url',base_url('areas'),'s');
        _set_js_var('mode','add','s');
	    _set_page_heading('Add areas');
	    _set_layout('areas_form_view');*/

    }

    public function edit($id) {
        $kitchen = $this->{$this->model}->single(['id'=>$id]);
        $this->_exclude_keys($kitchen);
        $this->_sql_to_vue($kitchen);
        _set_js_var('kitchen',$kitchen,'j');
        $this->_edit($id);

        /*_set_js_var('back_url',base_url('users'),'s');
        _set_js_var('mode','edit','s');
        _set_js_var('user',$user,'j');*/

      //  _set_page_heading('Edit User');
        //_set_layout('areas_form_view');

    }

    public function _action_put() {
        _model('kitchen');
        $obj = _input('kitchen');
        $this->_prep_obj($obj);
        $obj['added'] = sql_now_datetime();
        $affected_rows = $this->{$this->model}->insert($obj);
        if($affected_rows) {
            _response_data('redirect',base_url('kitchens'));
            return true;
        }else{
            return false;
        }

    }

    public function _action_post() {
        _model('kitchen');
        $obj = _input('kitchen');
        $this->_prep_obj($obj);
        $filter = ['id'=>$obj['id']];
        $affected_rows = $this->{$this->model}->update($obj,$filter);
        if($affected_rows) {
            _response_data('redirect',base_url('kitchens'));
            return true;
        }else{
            return false;
        }
    }

    public function _action_delete() {

        _model('kitchens');
        $ignore_list = [1];
        $id = _input('id');
        if(!in_array($id,$ignore_list)) {
            $filter = ['id' => $id];
            $affected_rows = $this->{$this->model}->delete($filter);
            if ($affected_rows) {
                _response_data('redirect', base_url('kitchens'));
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

            $params = [];
            $params['filter'] = ['status'=>1];
            $params['exclude'] = true;
            $params['convert'] = true;
            $printers = _get_module('printers','_search',$params);

            $printers = get_select_array($printers,'id','title',true,'','Select Printer');
            if(!$printers) {
                $printers = get_select_array([],'id','title',true,'','Select Printer');
            }
            _set_js_var('printers',$printers,'j');
            $params = [];
            $params['exclude'] = true;
            $params['convert'] = true;
            $templates = _get_module('printers/templates','_search',$params);
            $templates = ($templates)?get_select_array($templates,'id','title',true,'','Select Template'):[];
            if(!$templates) {
                $templates = get_select_array([],'id','title',true,'','Select Template');
            }
            _set_js_var('templates',$templates,'j');
            _page_script_override('kitchens/kitchens-form');
            $this->layout = 'kitchens_form_view';

        }

    }
}
