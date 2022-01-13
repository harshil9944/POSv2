<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Outlets extends MY_Controller {

    public $module = 'outlets';
    public $model = 'outlet';
    public $singular = 'Outlet';
    public $plural = 'Outlets';
    public $language = 'outlets/outlets';
    public $edit_form = '';
    public $form_xtemplate = 'outlets_form_xtemplate';
    public function __construct()
    {
        parent::__construct();
        _model($this->model);
        $params = [
            'migration_path' => OUTLET_MIGRATION_PATH,
            'migration_table' => OUTLET_MIGRATION_TABLE
        ];
        $this->init_migration($params);
    }
    public function index()	{

        _library('table');

        $results = $this->{$this->model}->get_list();

        $body = [];
        if($results) {
            foreach ($results as $result) {
                $action = _edit_link(base_url($this->module.'/edit/'.$result['id'])) . _vue_delete_link('handleRemove('.$result['id'].')');
                $action_cell = [
                    'class' =>  'text-center',
                    'data'  =>  $action
                ];
                $body[] = [
                    $result['title'],
                    ($result['status'])?'Active':'Inactive',
                    $action_cell
                ];
            }
        }

        $heading = [
            $this->singular . ' Name',
            'Status',
            ['data'=>'Action','class'=>'text-center no-sort w-100p']
        ];

        _vars('table_heading',$heading);
        _vars('table_body',$body);
        $table = _view(DATA_TABLE_PATH);

        $page = [
            'singular'      =>  $this->singular,
            'plural'        =>  $this->plural,
            'add_url'       =>  base_url($this->module.'/add'),
            'vue_add_url'   =>  '',
            'table'         =>  $table,
            'edit_form'     =>  ''
        ];
        _vars('page_data',$page);
        _set_additional_component(LIST_XTEMPLATE_PATH,'outside');
        _set_layout_type('wide');
        _set_page_heading($this->plural);
        _set_layout(LIST_VIEW_PATH);

    }

    public function add() {
        _helper('control');
        $this->_add();
        /*_set_js_var('back_url',base_url($this->module),'s');
        _set_js_var('mode','add','s');
        _set_page_heading('New ' . $this->singular);
        _set_page_title('New ' . $this->singular);
        _set_layout('outlets_form_view');*/

    }

    public function edit($id) {
        _helper('control');
        $this->_edit($id);
        /*_set_js_var('back_url',base_url($this->module),'s');
        _set_js_var('mode','edit','s');
        _set_js_var('id',$id,'s');
        _set_page_heading('Edit ' . $this->singular);
        _set_layout_type('wide');
        _set_layout('outlets_form_view');*/

    }

    public function _get_menu() {

        $menus = [];

        $outlets = [];

        $menus[] = array(
            'id'        => 'menu-outlets',
            'class'     => '',
            'icon'      => 'si si-basket-loaded',
            'group'     => 'settings',
            'name'      => 'Outlets',
            'path'      => 'outlets',
            'module'    => 'outlets',
            'priority'  => 4,
            'children'  => $outlets
        );

        return $menus;

    }

    public function _populate_get() {

        $params['include_select'] = true;
        $countries = _get_module('core/countries','_get_select_data',$params);
        _response_data('countries',$countries);

        $vendors = _get_module('contacts/vendors','_get_select_data',$params);
        _response_data('vendors',$vendors);

        $customers = _get_module('contacts/customers','_get_select_data',$params);
        _response_data('customers',$customers);

        $wh_number = _get_ref('otl',3,3);
        _response_data('code',$wh_number);

        return true;

    }

    public function _action_put() {

        $obj = _input('obj');
        $this->_prep_obj($obj);

        $outlet = $obj['outlet_table'];
        $outlet['status'] = 1;
        $outlet['created_by'] = _get_user_id();
        $outlet['added'] = sql_now_datetime();

        if($this->{$this->model}->insert($outlet)) {

            _update_ref('wh');

            _response_data('redirect',base_url($this->module));

        }else{
            _response_data('message','Something wrong. Please try again.');
        }
        return true;
    }

    public function _action_post() {

        $obj = _input('obj');
        $this->_prep_obj($obj);

        $outlet = $obj['outlet_table'];

        $outlet_id = $outlet['id'];
        unset($outlet['id']);

        $filter=[
            'id'    =>  $outlet_id
        ];

        if($this->{$this->model}->update($outlet,$filter)) {

            _response_data('redirect',base_url($this->module));

        }else{
            _response_data('message','Something wrong. Please try again.');
        }
        return true;
    }

    public function _action_delete() {

        $ignore_list = [];

        $id = _input('id');

        if(!in_array($id,$ignore_list)) {

            $filter = ['id' => $id];

            $result = $this->{$this->model}->single($filter);

            if($result) {

                $affected_rows = $this->{$this->model}->delete($filter);

                if ($affected_rows) {
                    _response_data('redirect', base_url($this->module));
                    return true;
                } else {
                    return false;
                }

            }else{
                _response_data('message','You cannot delete a protected size.');
                return false;
            }
        }else{
            _response_data('message','You cannot delete a protected size.');
            return false;
        }
    }

    private function _prep_obj(&$obj) {

        $outlet_keys = $this->{$this->model}->keys;

        $obj['outlet_table'] = [];
        foreach ($outlet_keys as $old => $new) {
            change_array_key($old,$new,$obj);
            if(isset($obj[$new])) {
                $obj['outlet_table'][$new] = $obj[$new];
                unset($obj[$new]);
            }
        }
        $obj['outlet_table']['type'] = 'outlet';

    }

    public function _single_get() {

        $id = _input('id');
        $filter = ['id'=>$id];
        $result = $this->{$this->model}->single($filter);
        $exclude_fields = $this->{$this->model}->exclude_keys;

        if($result) {

            $outlet_keys = $this->{$this->model}->keys;

            $result = filter_array_keys($result,$exclude_fields);
            foreach ($outlet_keys as $new=>$old) {
                change_array_key($old,$new,$result);
            }
            _response_data('obj',$result);

        }else{
            _set_message('The requested details could not be found.','warning');
            _response_data('redirect',base_url($this->module));
        }
        return true;

    }

    public function _single($params=[]) {

        $id = $params['id'];
        $filter = ['id'=>$id];
        $result = $this->{$this->model}->single($filter);
        //$exclude_fields = $this->{$this->model}->exclude_keys;

        return $result;

    }

    public function _select_data_get() {

        $params = [];
        $params['include_select'] = false;
        $params['status'] = 1;

        $result = $this->_get_select_data($params);

        _response_data('outlets',$result);
        return true;

    }

    public function _get_select_data($params=[]) {
        $include_select = isset($params['include_select'])?$params['include_select']:true;

        $status = isset($params['status'])?$params['status']:1;

        $filter = ['status'=>$status,'type'=>'outlet'];

        $this->{$this->model}->order_by('title');
        $result = $this->{$this->model}->search($filter);
        if($result) {
            $result = get_select_array($result,'id','title',$include_select,'','Select '.$this->singular);
            return $result;
        }else{
            return [];
        }

    }

    public function _get_active_list($params=[]) {

        return $this->{$this->model}->search(['status'=>1]);

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
            _load_plugin(['vue_multiselect']);
            $this->layout = 'outlets_form_view';
            _page_script_override('outlets/outlets-form');
        }

    }
}
