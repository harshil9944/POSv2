<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Permissions extends MY_Controller {

    public $module = 'permissions';
    public $model = 'permission';
    public $singular = 'Permission';
    public $plural = 'Permissions';
    public $edit_form = '';
    public function __construct()
    {
        parent::__construct();
        _model($this->model);
    }

    public function index() {

        _library('table');
        _helper('control');

        $results = $this->{$this->model}->search();
        $body = [];
        if($results) {
            foreach ($results as $result) {
                $body[] = [
                    $result['code'],
                    ($result['status'])?'Enabled':'Disabled',
                ];
            }
        }

        _vars('table_heading',[array('data'=>'ID','class'=>'text-center w-50p'),'Title',array('data'=>'Action','class'=>'text-center w-50p')]);

        $heading = [
            $this->singular . ' Code',
            array('data'=>'Status','class'=>'w-50p'),
        ];

        _vars('table_heading',$heading);
        _vars('table_body',$body);
        $table = _view(DATA_TABLE_PATH);

        $page = [
            'singular'      =>  $this->singular,
            'plural'        =>  $this->plural,
            'add_url'       =>  '',
            'vue_add_url'   =>  'handleAdd',
            'table'         =>  $table,
            'edit_form'     =>  ''
        ];
        _vars('page_data',$page);

        _set_additional_component(LIST_XTEMPLATE_PATH,'outside');
        _set_additional_component('system/permissions_xtemplate','outside');
        _set_additional_component('system/permissions_view','inside');

        _set_page_heading($this->plural);
        _set_layout(LIST_VIEW_PATH);

	}

	public function _action_put() {

        $obj = _input('obj');

        $obj['status'] = 1;
        $obj['added'] = sql_now_datetime();

        if(trim($obj['code'])=='') {
            _response_data('message','Blank values are not allowed');
            return false;
        }

        if($this->{$this->model}->insert($obj)) {
            _response_data('redirect',base_url($this->module));
        }else{
            _response_data('message','Something wrong. Please try again.');
        }
        return true;

    }

    public function _load_files() {
        if(_get_method()=='index') {
            _load_plugin('dt');
            //_page_script_override('permissions');
        }
    }
}
