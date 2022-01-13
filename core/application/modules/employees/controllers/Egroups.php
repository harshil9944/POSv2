<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Egroups extends MY_Controller {

    public $language = 'employees/egroups';
    public $module = 'employees/egroups';
    public $singular = 'Employee Group';
    public $plural = 'Employee Groups';
    public $model = 'employee_group';
    public $form_xtemplate = 'egroups_form_xtemplate';
    public function __construct() {
        parent::__construct();
        _model($this->model);
        //dump_exit($this->model);
    }

	public function index()
	{
        _library('table');

        $filters = [];
        $filters['filter'] = [];

        $groups = $this->{$this->model}->get_list($filters);
        $body = [];
        if($groups) {
            foreach ($groups as $group) {
                $action = _edit_link(base_url('employees/egroups/edit/'.$group['id'])) . _vue_delete_link('handleRemove('.$group['id'].')');
                $action_cell = [
                    'class' =>  'text-center',
                    'data'  =>  $action
                ];
                $body[] = [
                    ['data'=>$group['id'],'class'=>'text-center w-50p'],
                    $group['title'],
                    $action_cell
                ];
            }
        }

        _vars('table_heading',[array('data'=>'ID','class'=>'text-center w-50p'),'Title',array('data'=>'Action','class'=>'text-center w-110p')]);

        $heading = [
            array('data'=>'ID','class'=>'text-center w-70p'),
            'Title',
            array('data'=>'Action','class'=>'text-center w-110p')
        ];

        _vars('table_heading',$heading);
        _vars('table_body',$body);
        $table = _view(DATA_TABLE_PATH);

        $page = [
            'singular'  =>  'Group',
            'plural'    =>  'Groups',
            'add_url'   =>  base_url('employees/egroups/add'),
            'table'     =>  $table
        ];
        _vars('page_data',$page);

        _set_additional_component(LIST_XTEMPLATE_PATH,'outside');

        _set_page_heading($this->plural);
		_set_layout(LIST_VIEW_PATH);
	}

	public function add() {

        $this->_add();
	    /*_set_js_var('back_url',base_url('employees/egroups'),'s');
	    _set_js_var('mode','add','s');
	    _set_page_heading('New User Group');
	    _set_layout('groups_form_view');*/

    }

    public function edit($id='') {
        if(!$id) {
            _set_message('Group was not found.','warning');
            $this->_redirect('employees/egroups');
        }

        $group = $this->{$this->model}->single(['id'=>$id]);

        $group['permissions'] = (@$group['permissions'])?unserialize($group['permissions']):[];
        $group['subPermissions'] = (@$group['sub_permissions'])?unserialize($group['sub_permissions']):[];
        unset($group['sub_permissions']);

        _set_js_var('back_url',base_url('employees/egroups'),'s');
        _set_js_var('mode','edit','s');
        _set_js_var('group',$group,'j');

        $this->_edit($id);
        /*_set_page_heading('Edit User Group');
        _set_layout('groups_form_view');*/
    }

    public function _action_put() {

        $group = _input('group');

        $group['permissions'] = (@$group['permissions'])?$group['permissions']:[];
        $group['sub_permissions'] = (@$group['subPermissions'])?$group['subPermissions']:[];
        unset($group['subPermissions']);

        $data = [
            'title'             =>  $group['title'],
            'is_admin'          =>  0,
            'permissions'       =>  serialize($group['permissions']),
            'sub_permissions'   =>  serialize($group['sub_permissions']),
            'default_page'      =>  $group['default_page']
        ];
        $data['rank'] = (@$group['rank'])?$group['rank']:_get_setting('default_egroup_rank',5);

        $affected_rows = $this->{$this->model}->insert($data);

        if($affected_rows) {
            $id = $this->{$this->model}->insert_id();
            $this->{$this->model}->allowed_groups[] = $id;
            _set_setting('allowed_employee_groups',$this->{$this->model}->allowed_groups);
            _response_data('redirect',base_url('employees/egroups'));
            return true;
        }else{
            return false;
        }
    }

    public function _action_post() {

        $group = _input('group');

        $filter = ['id'=>$group['id']];

        $group['permissions'] = (@$group['permissions'])?$group['permissions']:[];
        $group['sub_permissions'] = (@$group['subPermissions'])?$group['subPermissions']:[];
        unset($group['subPermissions']);

        $data = [
            'title'             =>  $group['title'],
            'is_admin'          =>  0,
            'permissions'       =>  serialize($group['permissions']),
            'sub_permissions'   =>  serialize($group['sub_permissions']),
            'default_page'      =>  $group['default_page']
        ];

        $affected_rows = $this->{$this->model}->update($data,$filter);

        if($affected_rows) {
            _response_data('redirect',base_url('employees/egroups'));
            return true;
        }else{
            return false;
        }
    }

    public function _action_delete() {

        $ignore_list = [1];

        $id = _input('id');

        if(!in_array($id,$ignore_list)) {
            $filter = ['id' => $id];

            $affected_rows = $this->{$this->model}->delete($filter);

            if ($affected_rows) {
                $allowed_groups = $this->{$this->model}->allowed_groups;
                $this->{$this->model}->allowed_groups = array_diff($allowed_groups,[$id]);
                _set_setting('allowed_employee_groups',$this->{$this->model}->allowed_groups);
                _response_data('redirect', base_url('employees/egroups'));
                return true;
            } else {
                return false;
            }
        }else{
            _response_data('message','You cannot delete a protected group.');
            return false;
        }
    }

    protected function _load_files() {

        if(_get_method()=='index') {

            _load_plugin(['toast','dt']);

        }
        if(_get_method()=='add' || _get_method()=='edit') {

            _model(['route','permission']);
            _set_js_var('routes',$this->route->get_list(),'j');

            $this->permission->order_by('code');
            $sub_permissions = $this->permission->search(['status'=>1]);
            _set_js_var('subPermissions',$sub_permissions,'j');

            $this->layout = 'egroups_form_view';

            _page_script_override('employees/egroups-form');
            //_enqueue_script('assets/pages/js/employees/egroups-form.js');

        }

    }
}
