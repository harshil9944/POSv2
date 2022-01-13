<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Transfers extends MY_Controller {

    public $module = 'transfers';
    public $model = 'transfer';
    public $singular = 'Transfer';
    public $plural = 'Transfers';
    public $edit_form = '';
    public function __construct()
    {
        parent::__construct();
        _model($this->model);
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
                    $result['id'],
                    $result['id'],
                    $result['id'],
                    $result['id'],
                    $result['id'],
                    $result['id'],
                    $action_cell
                ];
            }
        }

        _vars('table_heading',[array('data'=>'ID','class'=>'text-center w-50p'),'Title',array('data'=>'Action','class'=>'text-center w-50p')]);

        $heading = [
            array('data'=>'ID','class'=>'text-center w-50p'),
            $this->singular . ' Name',
            'Organisation ID',
            'Email',
            'Phone',
            'Status',
            array('data'=>'Action','class'=>'text-center w-50p')
        ];

        _vars('table_heading',$heading);
        _vars('table_body',$body);
        $table = _view(DATA_TABLE_PATH);

        $page = [
            'singular'      =>  $this->singular,
            'plural'        =>  $this->plural,
            'add_url'       =>  base_url('items/transfers/add'),
            'vue_add_url'   =>  '',
            'table'         =>  $table,
            'edit_form'     =>  ''
        ];
        _vars('page_data',$page);
        _set_layout_type('wide');
        _set_page_heading($this->plural);
        _set_layout(LIST_VIEW_PATH);

    }

    public function add() {
        _helper('control');
        _set_js_var('back_url',base_url($this->module),'s');
        _set_js_var('mode','add','s');
        _set_page_heading('New ' . $this->singular);
        _set_page_title('New ' . $this->singular);
        _set_layout('transfers_form_view');

    }

    /*public function edit($id) {

        _set_js_var('back_url',base_url($this->module),'s');
        _set_js_var('mode','edit','s');
        _set_js_var('id',$id,'s');
        _set_page_heading('Edit ' . $this->singular);
        _set_layout_type('wide');
        _set_layout('transfers_form_view');

    }*/

    public function _populate_get() {

        $statuses = get_status_array();

        _response_data('statuses',$statuses);
        return true;

    }

    public function _action_put() {
        $obj = _input('obj');
        $this->_prep_obj($obj);
        $meta = $obj['meta'];
        $transfers = (isset($meta['transfers']) && is_array($meta['transfers']))?$meta['transfers']:[];
        $obj['status'] = 1;
        $obj['added'] = sql_now_datetime();
        unset($obj['meta']);
        unset($obj['id']);
        unset($meta['transfers']);

        if($this->{$this->model}->insert($obj)) {
            $id = $this->{$this->model}->insert_id();
            $this->{$this->model}->insert_meta($meta,$id);
            $this->{$this->model}->update_cluster_links($transfers,$id);
            _response_data('redirect',base_url($this->module));
        }else{
            _response_data('message','Something wrong. Please try again.');
        }
        return true;
    }

    public function _action_post() {

        $obj = _input('obj');
        $this->_prep_obj($obj);
        $meta = $obj['meta'];
        $transfers = (isset($meta['transfers']) && is_array($meta['transfers']))?$meta['transfers']:[];
        $id = $obj['id'];
        unset($obj['id']);
        unset($obj['meta']);
        unset($meta['transfers']);

        $filter=[
            'id'    =>  $id
        ];

        if($this->{$this->model}->update($obj,$filter)) {
            $this->{$this->model}->update_meta($meta,$filter['id']);
            $this->{$this->model}->update_cluster_links($transfers,$filter['id']);
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

    }

    public function _single_get() {
        _model($this->model);

        $id = _input('id');
        $filter = ['id'=>$id];
        $result = $this->{$this->model}->single($filter);

        if($result) {

            $meta = $this->{$this->model}->get_meta($result['id']);

            $links = $this->{$this->model}->get_cluster_links($result['id']);

            if ($links) {
                $clusters_array = [];
                foreach ($links as $link) {
                    $clusters_array[] = $link['cluster_id'];
                }

                //$clusters_array = explode(',', $clusters_array);
                $clusters = _get_module('clusters', '_get_by_id', ['id' => $clusters_array]);
                $temp = [];
                if ($clusters) {
                    foreach ($clusters as $single) {
                        $temp[] = [
                            'id' => $single['id'],
                            'value' => $single['meta']['name']
                        ];
                    }
                    $meta['clusters'] = $temp;
                }
            }

            $result['meta'] = $meta;
            _response_data('obj',$result);

        }else{
            _set_message('The requested details could not be found.','warning');
            _response_data('redirect',base_url($this->module));
        }
        return true;
    }

    public function _select_data_get() {

        $params = [];
        $params['include_select'] = false;
        $tours = $this->_get_select_data($params);

        _response_data('tours',$tours);
        return true;

    }

    public function _get_select_data($params=[]) {
        $include_select = isset($params['include_select'])?$params['include_select']:true;

        $tours = $this->{$this->model}->get_active_list();
        if($tours) {
            $tours = get_select_array($tours,'id','title',$include_select,'','Select '.$this->singular);
            return $tours;
        }else{
            return [];
        }

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
            _page_script_override('transfers/transfers-form');
        }

    }
}
