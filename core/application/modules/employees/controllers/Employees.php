<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Employees extends MY_Controller {

    public $module = 'employees';
    public $model = 'employee';
    public $singular = 'Employee';
    public $plural = 'Employees';
    public $language = 'employees/employees';
    public $edit_form = '';
    public $form_xtemplate = 'employees_form_xtemplate';
    public function __construct()
    {
        parent::__construct();
        _model($this->model);
        $params = [
            'migration_path' => EMPLOYEE_MIGRATION_PATH,
            'migration_table' => EMPLOYEE_MIGRATION_TABLE
        ];
        $this->init_migration($params);
    }
    public function index()	{

        _model('employee_group','egroup');

        _library('table');

        $filters = [];
        $filters['filter'] = [];

        $employees = $this->{$this->model}->get_list($filters);

        $group_filters = [];
        $group_filters['filter'] = [];
        $groups = $this->egroup->get_list($group_filters);

        if($groups) {
            $groups = get_index_id_array($groups,'id');
        }

        $body=[];

        if($employees) {
            foreach ($employees as $user) {
                $action = _edit_link(base_url('employees/edit/'.$user['id'])) . _vue_delete_link('handleRemove('.$user['id'].')');
                $action_cell = [
                    'class' =>  'text-center w-110p',
                    'data'  =>  $action
                ];

                $group = (isset($groups[$user['group_id']]))?$groups[$user['group_id']]['title']:'';

                $body[] = [
                    'id'		=>	$user['id'],
                    'name'      =>	$user['first_name'] . ' ' . $user['last_name'],
                    'group'	    =>	$group,
                    'email'	    =>	$user['email'],
                    $action_cell
                ];
            }
        }

        $heading = [
            'ID',
            'Name',
            'Group',
            'Email',
            ['data'=>'Action','class'=>'text-center w-100p']
        ];

        _vars('table_heading',$heading);
        _vars('table_body',$body);
        $table = _view(DATA_TABLE_PATH);

        $page = [
            'singular'  =>  $this->singular,
            'plural'    =>  $this->plural,
            'add_url'   =>  base_url('employees/add'),
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

        _model('employee_warehouse','etw');
        _model('employee_register','etr');

        if(!$id) {
            _set_message($this->singular . ' was not found.','warning');
            $this->_redirect($this->module);
        }

        $user = $this->{$this->model}->single(['id'=>$id]);

        $user['status'] = (int)$user['status'];
        $warehouses = $this->etw->search(['user_id'=>$user['id']]);
        $user['warehouse_id'] = (@$warehouses[0]['warehouse_id'])?$warehouses[0]['warehouse_id']:null;
        $registers = $this->etr->search(['user_id'=>$user['id']]);
        $user['register_id'] = (@$registers[0]['register_id'])?$registers[0]['register_id']:null;

        _set_js_var('user',$user,'j');

        $this->_edit($id);
    }

    public function _action_put() {
        _model('employee_warehouse','etw');
        _model('employee_register','etr');
        _helper('password');

        $user = _input('user');

        $data = [
            'group_id'      =>  $user['group_id'],
            'first_name'    =>  $user['first_name'],
            'last_name'     =>  $user['last_name'],
            'email'         =>  $user['email'],
            'default_page'  =>  $user['default_page'],
            'password'      =>  hash_password($user['password']),
            'added'         =>  sql_now_datetime()
        ];

        $affected_rows = $this->{$this->model}->insert($data);

        if($affected_rows) {

            $user_id = $this->{$this->model}->insert_id();

            $insert = [
                'user_id'       =>  $user_id,
                'warehouse_id'  =>  $user['warehouse_id'],
                'added'         =>  sql_now_datetime()
            ];
            $this->etw->insert($insert);
            $insert = [
                'user_id'       =>  $user_id,
                'register_id'  =>  $user['register_id'],
                'added'         =>  sql_now_datetime()
            ];
            $this->etr->insert($insert);

            _response_data('redirect',base_url($this->module));
            return true;
        }else{
            return false;
        }
    }

    public function _action_post() {

        _model('employee_warehouse','etw');
        _model('employee_register','etr');
        _helper('password');

        $user = _input('user');

        $data = [
            'group_id'      =>  $user['group_id'],
            'first_name'    =>  $user['first_name'],
            'last_name'     =>  $user['last_name'],
            'email'         =>  $user['email'],
            'default_page'  =>  $user['default_page'],

        ];
        if($user['password']) {
            $data['password'] = hash_password($user['password']);
        }

        $filter = ['id'=>$user['id']];
        $affected_rows = $this->{$this->model}->update($data,$filter);

        $update = [
            'warehouse_id'  =>  $user['warehouse_id'],
            'added'         =>  sql_now_datetime()
        ];
        $etw_filter = ['user_id'=>$user['id']];
        $this->etw->update($update,$etw_filter);
        $update = [
            'register_id'  =>  $user['register_id'],
            'added'         =>  sql_now_datetime()
        ];
        $etr_filter = ['user_id'=>$user['id']];
        $this->etr->update($update,$etr_filter);

        if($affected_rows) {
            _response_data('redirect',base_url($this->module));
            return true;
        }else{
            return false;
        }
    }

    public function _action_delete() {

        _model('user');

        $ignore_list = [1];

        $id = _input('id');

        if(!in_array($id,$ignore_list)) {
            $filter = ['id' => $id];

            $affected_rows = $this->{$this->model}->delete($filter);

            if ($affected_rows) {
                _response_data('redirect', base_url($this->module));
                return true;
            } else {
                return false;
            }
        }else{
            _response_data('message','You cannot delete a protected user.');
            return false;
        }
    }

    public function _populate_get() {
        $warehouses = _get_module('warehouses','_search',[]);
        if($warehouses) {
            $temp = [];
            foreach ($warehouses as $warehouse) {
                $warehouse['title'] = $warehouse['title'] . ' (' . ucfirst(substr($warehouse['type'],0,1)) . ')';
                $temp[] = $warehouse;
            }
            $warehouses = $temp;
        }
        _response_data('warehouses',$warehouses);

        $registers = _get_module('registers','_search',[]);

        if($registers) {
            $temp = [];
            foreach ($registers as $register) {
                $register['title'] = $register['title'];
                $temp[] = $register;
            }
            $registers = $temp;
        }

        _response_data('registers',$registers);

        return true;
    }

    public function _warehouses($params=[]) {

        _model('employee_warehouse','etw');

        $user_id = $params['user_id'];

        return $this->etw->search(['user_id'=>$user_id]);

    }
    public function _get_register($params=[]) {

        _model('employee_register','etr');
        $user_id = $params['user_id'];
        return $this->etr->single(['user_id'=>$user_id]);

    }

    public function _get_menu() {

        $menus = [];

        $menus = [];

        $employees = [];
        $employees[] = array(
            'name'	    =>  'Employees',
            'class'     =>  '',
            'icon'      =>  'user',
            'path'      =>  'employees',
            'module'    =>  'employees',
            'children'  =>  []
        );

        $employees[] = array(
            'name'	    =>  'Groups',
            'class'     =>  '',
            'icon'      =>  'people',
            'path'      =>  'employees/egroups',
            'module'    =>  'employees',
            'children'  =>  []
        );

        $menus[] = array(
            'id'        => 'menu-employees',
            'class'     => '',
            'icon'      => 'si si-basket-loaded',
            'group'     => 'settings',
            'name'      => 'Employees',
            'path'      => 'employees',
            'module'    => 'employees',
            'priority'  => 4,
            'children'  => $employees
        );

        return $menus;

    }

    public function _install() {
        return true;
    }

    public function _uninstall() {
        return true;
    }

    protected function _load_files() {

        if(_get_method() == 'index') {
            _load_plugin(['dt']);
        }

        if(_get_method()=='add' || _get_method()=='edit') {

            _model('employee_group','egroup');

            _load_plugin(['vue_multiselect']);
            $this->layout = 'employees_form_view';

            $groups = [];
            if($this->egroup->allowed_groups) {
                $filter = ['rank >='=>_get_session('rank')];
                $this->group->where_in('id', $this->egroup->allowed_groups);
                $groups = $this->group->search($filter);
            }
            _set_js_var('groups',$groups,'j');

            _set_js_var('statuses',get_status_array(),'j');

            _page_script_override('employees/employees-form');
        }

    }
}
