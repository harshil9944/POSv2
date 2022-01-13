<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Groups extends MY_Controller {

    public $language = 'users/groups';
    public $module = 'users/groups';
    public $model = 'group';
    public $form_xtemplate = 'groups_form_xtemplate';
    public function __construct() {
        parent::__construct();
        _model($this->model);
        /*dump(_get_class());
        dump(_get_method());
        dump_exit($this->{$this->model});*/
    }

	public function index()
	{
        _library('table');

        $groups = $this->{$this->model}->get_list();
        $body = [];
        if($groups) {
            foreach ($groups as $group) {
                $action = _edit_link(base_url('users/groups/edit/'.$group['id'])) . _vue_delete_link('handleRemove('.$group['id'].')');
                $action_cell = [
                    'class' =>  'text-center w-110p',
                    'data'  =>  $action
                ];
                $body[] = [
                    ['data'=>$group['id'],'class'=>'text-center w-50p'],
                    $group['title'],
                    $action_cell
                ];
            }
        }

        _vars('table_heading',[array('data'=>'ID','class'=>'text-center w-50p'),'Title',array('data'=>'Action','class'=>'text-center w-70p')]);

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
            'add_url'   =>  base_url('users/groups/add'),
            'table'     =>  $table
        ];
        _vars('page_data',$page);

        _set_additional_component(LIST_XTEMPLATE_PATH,'outside');

        _set_page_heading('User Groups');
		_set_layout(LIST_VIEW_PATH);
	}

	public function add() {

        $this->_add();
	    /*_set_js_var('back_url',base_url('users/groups'),'s');
	    _set_js_var('mode','add','s');
	    _set_page_heading('New User Group');
	    _set_layout('groups_form_view');*/

    }

    public function edit($id='') {
        if(!$id) {
            _set_message('Group was not found.','warning');
            $this->_redirect('users/groups');
        }

        $group = $this->{$this->model}->single(['id'=>$id]);

        $group['permissions'] = unserialize($group['permissions']);

        _set_js_var('back_url',base_url('users/groups'),'s');
        _set_js_var('mode','edit','s');
        _set_js_var('group',$group,'j');

        $this->_edit($id);
        /*_set_page_heading('Edit User Group');
        _set_layout('groups_form_view');*/
    }

    public function _action_put() {

        $group = _input('group');

        $data = [
            'title'         =>  $group['title'],
            'is_admin'      =>  0,
            'permissions'   =>  serialize($group['permissions']),
            'default_page'  =>  $group['default_page']
        ];
        $data['rank'] = (@$group['rank'])?$group['rank']:_get_setting('default_user_group_rank',4);

        $affected_rows = $this->{$this->model}->insert($data);

        if($affected_rows) {
            _response_data('redirect',base_url('users/groups'));
            return true;
        }else{
            return false;
        }
    }

    public function _action_post() {



        $group = _input('group');

        $filter = ['id'=>$group['id']];

        $data = [
            'title'         =>  $group['title'],
            'is_admin'      =>  0,
            'permissions'   =>  serialize($group['permissions']),
            'default_page'  =>  $group['default_page']
        ];

        $affected_rows = $this->{$this->model}->update($data,$filter);

        if($affected_rows) {
            _response_data('redirect',base_url('users/groups'));
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
                _response_data('redirect', base_url('users/groups'));
                return true;
            } else {
                return false;
            }
        }else{
            _response_data('message','You cannot delete a protected group.');
            return false;
        }
    }

    public function _routes_get() {

        $id = _input('group_id');

        $result = $this->{$this->model}->get_permissions($id);
        $result = (@$result['page'])?$result['page']:[];

        sort($result);

        _response_data('routes',$result);
        return true;
    }

    public function _permissions() {

        _model($this->model);
        return $this->{$this->model}->get_permissions();
    }

    protected function _load_files() {

        if(_get_method()=='index') {

            _load_plugin(['toast','dt']);

        }
        if(_get_method()=='add' || _get_method()=='edit') {

            _model('route');
            _set_js_var('routes',$this->route->get_list(),'j');
            $this->layout = 'groups_form_view';

            _page_script_override('users/groups-form');
            //_enqueue_script('assets/pages/js/users/groups-form.js');

        }

    }
}
