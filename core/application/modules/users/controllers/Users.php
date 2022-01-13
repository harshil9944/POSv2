<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Users extends MY_Controller {
    public $model = 'user';
    public $module = 'users';
    public $singular = 'User';
    public $plural = 'Users';
    public $language = 'users/users';
    public $form_xtemplate = 'users_form_xtemplate';
    public function __construct()
    {
        parent::__construct();
        _model($this->model);
    }

	public function index()
	{
	    _model(array('group'));
        _library('table');

	    $users = $this->user->search();
	    $groups = $this->group->search();

	    if($groups) {
	        $groups = get_index_id_array($groups,'id');
        }

        $body=[];

        if($users) {
            foreach ($users as $user) {
                $action = _edit_link(base_url('users/edit/'.$user['id'])) . _vue_delete_link('handleRemove('.$user['id'].')');
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
            'singular'  =>  'User',
            'plural'    =>  'Users',
            'add_url'   =>  base_url('users/add'),
            'table'     =>  $table
        ];
        _vars('page_data',$page);

        _set_additional_component(LIST_XTEMPLATE_PATH,'outside');

        _set_page_heading('Users');
        _set_layout_type('wide');
        _set_layout(LIST_VIEW_PATH);
	}

	public function add() {

        $this->_add();

        /*_set_js_var('back_url',base_url('users'),'s');
        _set_js_var('mode','add','s');
	    _set_page_heading('Add User');
	    _set_layout('users_form_view');*/

    }

    public function edit($id='') {

        if(!$id) {
            _set_message('User was not found.','warning');
            $this->_redirect('users');
        }

        $user = $this->{$this->model}->single(['id'=>$id]);

        $user['status'] = (int)$user['status'];

        _set_js_var('user',$user,'j');

        $this->_edit($id);

        /*_set_js_var('back_url',base_url('users'),'s');
        _set_js_var('mode','edit','s');
        _set_js_var('user',$user,'j');

        _set_page_heading('Edit User');
        _set_layout('users_form_view');*/

    }

    public function _get_menu() {

        $menus = [];

        $users = [];
        $users[] = array(
            'name'	    =>  'Users',
            'class'     =>  '',
            'icon'      =>  'user',
            'path'      =>  'users',
            'module'    =>  'users',
            'children'  =>  []
        );

        $users[] = array(
            'name'	    =>  'Groups',
            'class'     =>  '',
            'icon'      =>  'people',
            'path'      =>  'users/groups',
            'module'    =>  'users',
            'children'  =>  []
        );

        $menus[] = array(
            'id'        => 'menu-users',
            'class'     => '',
            'icon'      => 'si si-users',
            'group'     => 'settings',
            'name'      => 'Users',
            'path'      => '',
            'module'    =>  'users',
            'priority'  => 3,
            'children'  => $users
        );

        return $menus;

    }

    public function _action_put() {
        _model('user');
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
            _response_data('redirect',base_url('users'));
            return true;
        }else{
            return false;
        }
    }

    public function _action_post() {

        _model('user');
        _helper('password');

        $user = _input('user');

        $data = [
            'group_id'      =>  $user['group_id'],
            'first_name'    =>  $user['first_name'],
            'last_name'     =>  $user['last_name'],
            'email'         =>  $user['email'],
            'default_page'  =>  $user['default_page']
        ];
        if($user['password']) {
            $data['password'] = hash_password($user['password']);
        }

        $filter = ['id'=>$user['id']];
        $affected_rows = $this->{$this->model}->update($data,$filter);

        if($affected_rows) {
            _response_data('redirect',base_url('users'));
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
                _response_data('redirect', base_url('users'));
                return true;
            } else {
                return false;
            }
        }else{
            _response_data('message','You cannot delete a protected user.');
            return false;
        }
    }

    public function _duplicate_email_get() {
        $query = _input('email');
        if($query) {
            $filter = ['email'=>$query];
            $customers = $this->{$this->model}->search($filter);
            if($customers) {
                _response_data('result',true);
            }else{
                _response_data('result',false);
            }
        }
        return true;
    }

    public function _update_password_post() {
        _model('user');
        _helper('password');

        $user = _input('user');
        $user_id = _get_session('user_id');

        $current_password = $user['currentPassword'];

        $password_check = $this->_find(['filter'=>['id'=>$user_id,'password'=>hash_password($current_password)]]);

        if($password_check) {
            $new_password = $user['newPassword'];
            $confirm_password = $user['confirmPassword'];
            if($new_password==$confirm_password) {
                $data = [];
                $data['password'] = hash_password($user['newPassword']);

                $filter = ['id'=>$user_id];
                $update_profile = $this->{$this->model}->update($data,$filter);
                _response_data('message','Password updated successfully.');
                return true;
            }else{
                _response_data('message','New password and Confirm password do not match.');
                return false;
            }

        }else{
            _response_data('message','Current password is invalid.');
            return false;
        }
    }

    protected function _load_files() {

	    if(_get_method()=='index') {

            _load_plugin(['dt']);

        }

	    if(_get_method()=='add' || _get_method()=='edit') {

            _page_script_override('users/users-form');

            /*_model('route');
            _set_js_var('routes',[],'j');*/

            _model('group');
            $filter = ['rank >='=>_get_session('rank')];
            _set_js_var('groups',$this->group->search($filter),'j');

            _set_js_var('statuses',get_status_array(),'j');

            $this->layout = 'users_form_view';

        }

    }
}
