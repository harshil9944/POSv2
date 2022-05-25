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

        _library('table');

        $filters = [];
        $filters['filter'] = [];

        $employees = $this->{$this->model}->get_list($filters);

        $body=[];

        if($employees) {
            foreach ($employees as $user) {
                $action = _edit_link(base_url('employees/edit/'.$user['id'])) . _vue_delete_link('handleRemove('.$user['id'].')');
                $action_cell = [
                    'class' =>  'text-center w-110p',
                    'data'  =>  $action
                ];

                $body[] = [
                    'id'		=>	$user['id'],
                    'name'      =>	$user['first_name'] . ' ' . $user['last_name'],
                    'email'	    =>	$user['email'],
                    $action_cell
                ];
            }
        }

        $heading = [
            'ID',
            'Name',
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
        if(!$id) {
            _set_message($this->singular . ' was not found.','warning');
            $this->_redirect($this->module);
        }
        $user = $this->{$this->model}->single(['id'=>$id]);
        $user['status'] = (int)$user['status'];
        _set_js_var('user',$user,'j');

        $this->_edit($id);
    }

    public function _action_put() {
        $user = _input('user');
        $user['added'] = sql_now_datetime();
        $affected_rows = $this->{$this->model}->insert($user);
        if($affected_rows) {
            _response_data('redirect',base_url($this->module));
            return true;
        }else{
            return false;
        }
    }

    public function _action_post() {


        $user = _input('user');

        $filter = ['id'=>$user['id']];
        unset($user['id']);
        $affected_rows = $this->{$this->model}->update($user,$filter);
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

    public function _employees_pos($params = []){
        _model('employee_shift','es');
        $session_id = $params['session_id'];
        $all_employees = $this->{$this->model}->search(['deleted'=>0,'status'=>1]);
        $employees = [];
        if($all_employees){
            foreach($all_employees as &$e){
                $employee =[
                    'name'=>$e['first_name'].' '.$e['last_name'],
                    'id'=>$e['id'],
                    'shiftOpen'=>$this->es->single(['employee_id'=>$e['id'],'session_id'=>$session_id,'close_register_id'=>null,'end_shift'=>null])?true:false
                ]; 
                $employees[] = $employee;
            }
        }
       
        return $employees;

    }

    public function _set_employee_shift_post(){
        _model('employee_shift','es');
        $employee =  _input('employee');
        $emp_id= $employee['id'];
        $code = $employee['code'];
        $session_id = $employee['sessionId'];
        $exit_emp = $this->_check_employee_login($emp_id,$code);
        if($exit_emp){
            $login = false;
            if($this->es->single(['employee_id'=>$emp_id,'session_id'=>$session_id,'close_register_id'=>null,'end_shift'=>null])){
                $login = true;
            }else{
                $data = [
                    'id'=>'',
                    'outlet_id'=>0,
                    'employee_id'=>$emp_id,
                    'session_id'=>$session_id,
                    'opening_register_id'=>$employee['openingRegisterId'],
                    'close_register_id'=>null,
                    'take_out'=>null,
                    'start_shift'=>sql_now_datetime(),
                    'end_shift'=>null,
                ];
                $this->es->insert($data);
                $login = true;
            }
           
            if($login){
                _response_data('employeeId',$emp_id);
                return true;
            };
        }else{
            _response_data('message','Invalid Employee code.');
            return false;
        }
    }

    public function _set_employee_shift_close_post(){
        _model('employee_shift','es');
        $obj = _input('obj');
        $register_id = $obj['registerId'];
        $session_id = $obj['sessionId'];
        $employee_id = $obj['employeeId'];

        if($employee_id){
            $shift = $this->es->single(['session_id'=>$session_id,'employee_id'=>$employee_id,'close_register_id'=>null,'end_shift'=>null]);
            if($shift){
                $data = ['take_out'=>$obj['takeOut'],'close_register_id'=>$register_id,'end_shift'=>sql_now_datetime()];
                if($this->es->update($data,['id'=>$shift['id']])){
                    _response_data('message','OP');
                    return true;
                };

            }
            _response_data('message','Invalid Employee or code.');
            return false;

        }
        _response_data('message','Invalid Employee or code.');
        return false;
        
    }

    public function _check_employee_login($id,$code){
        $filters = [
            'id'     => $id,
            'code'  =>  $code
        ];
        $user = $this->{$this->model}->single($filters);
        if($user) {
            return $user;
        }else{
            return false;
        }

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
        $employees[] = array(
            'name'	    =>  'Employees',
            'class'     =>  '',
            'icon'      =>  'user',
            'path'      =>  'employees',
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
            _load_plugin(['vue_multiselect']);
            $this->layout = 'employees_form_view';
            _set_js_var('statuses',get_status_array(),'j');

            _page_script_override('employees/employees-form');
        }

    }
}
