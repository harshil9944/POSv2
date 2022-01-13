<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Areas extends MY_Controller {

    public $module = 'areas';
    public $model = 'area';
    public $singular = 'Area';
    public $plural = 'Areas';
    public $language = 'areas/areas';
    public $edit_form = '';
    public $form_xtemplate = 'areas_form_xtemplate';

    public function __construct()
    {
        parent::__construct();
        _model($this->model);
        $params = [
            'migration_path' => AREAS_MIGRATION_PATH,
            'migration_table' => AREAS_MIGRATION_TABLE
        ];
        $this->init_migration($params);
    }
    public function index()
	{
        _library('table');
        $filters = [];
        $filters['filter'] = [];
	    $areas = $this->_search($filters);
        $body=[];
        if($areas) {
            foreach ($areas as $area) {
                $action = _edit_link(base_url('areas/edit/'.$area['id'])) . _vue_delete_link('handleRemove('.$area['id'].')');
                $action_cell = [
                    'class' =>  'text-center w-110p',
                    'data'  =>  $action
                ];
                $body[] = [
                    'title'		    =>	$area['title'],
                    'description'	=>	$area['description'],
                    'sort_order'	=>	$area['sort_order'],
                    $action_cell
                ];
            }
        }
        $heading = [
            'TITLE',
            'DESCRIPTION',
            'SORT ORDER',
            ['data'=>'Action','class'=>'text-center w-100p']
        ];

        _vars('table_heading',$heading);
        _vars('table_body',$body);
        $table = _view(DATA_TABLE_PATH);

        $page = [
            'singular'  =>  $this->singular,
            'plural'    =>  $this->plural,
            'add_url'   =>  base_url('areas/add'),
            'table'     =>  $table
        ];
        _vars('page_data',$page);

        _set_additional_component(LIST_XTEMPLATE_PATH,'outside');

        _set_page_heading($this->plural);
        _set_layout_type('wide');
        _set_layout(LIST_VIEW_PATH);
	}

    public function _populate_get() {
        _model('areas/area_table','table');
        $params = [];
        $params['filter'] = [];
        $params['exclude'] = true;
        $params['convert'] = true;
        $params['orders'] = [['order_by'=>'sort_order','order'=>'ASC']];
        $areas = $this->_search($params);
        $tables = _get_module('areas/tables','_search',$params);
        if($tables) {
            $temp = [];
            foreach ($tables as $table) {

                $table['durationSince'] = '';

                $temp[] = $table;
            }
            $tables = $temp;
        }
        _response_data('areas',$areas);
        _response_data('tables',$tables);
        return true;
    }

    public function _get_menu() {
        $menus = [];
        $areas = [];
        $areas[] = [
            'name'	    =>  'Areas',
            'class'     =>  '',
            'group'     =>  '',
            'icon'      =>  'basket-loaded',
            'path'      =>  'areas',
            'module'    =>  'areas',
            'children'  =>  []
        ];
        $areas[] = [
            'name'	    =>  'Tables',
            'class'     =>  '',
            'group'     =>  '',
            'icon'      =>  'basket-loaded',
            'path'      =>  'areas/tables',
            'module'    =>  'areas/tables',
            'children'  =>  []
        ];
        $menus[] = array(
            'id'        => 'menu-areas',
            'class'     => '',
            'icon'      => 'si si-bar-chart',
            'group'     => 'module',
            'name'      => 'Areas',
            'path'      => 'areas',
            'module'    => 'areas',
            'priority'  => 5,
            'children'  => $areas
        );
        return $menus;
    }

    public function add() {
        $this->_add();
    }
    public function edit($id) {
        $area = $this->{$this->model}->single(['id'=>$id]);
        $this->_exclude_keys($area);
        $this->_sql_to_vue($area);
        _set_js_var('area',$area,'j');
        $this->_edit($id);
    }

    public function _action_put() {
        _model('area');
        $obj = _input('area');
        $this->_prep_obj($obj);
        $obj['added'] = sql_now_datetime();
        $affected_rows = $this->{$this->model}->insert($obj);
        if($affected_rows) {
            _response_data('redirect',base_url('areas'));
            return true;
        }else{
            return false;
        }
    }

    public function _action_post() {
        _model('area');
        $obj = _input('area');
        $this->_prep_obj($obj);
        $filter = ['id'=>$obj['id']];
        $affected_rows = $this->{$this->model}->update($obj,$filter);
        if($affected_rows) {
            _response_data('redirect',base_url('areas'));
            return true;
        }else{
            return false;
        }
    }

    public function _action_delete() {
        _model('area');
        $ignore_list = [1];
        $id = _input('id');
        if(!in_array($id,$ignore_list)) {
            $filter = ['id' => $id];
            $affected_rows = $this->{$this->model}->delete($filter);
            if ($affected_rows) {
                _response_data('redirect', base_url('areas'));
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
          //  _load_plugin(['vue_multiselect','moment','datepicker']);
            _page_script_override('areas/areas-form');
            $this->layout = 'areas_form_view';
        }
    }
}
