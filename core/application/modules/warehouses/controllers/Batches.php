<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Batches extends MY_Controller {

    public $module = 'warehouses/batches';
    public $model = 'batch';
    public $singular = 'Batch';
    public $plural = 'Batches';
    public $language = 'warehouses/batches';
    public $edit_form = '';
    public function __construct() {
        parent::__construct();
        _model($this->model);
    }
    public function index() {

    }

    public function _single_get() {

        $id = _input('id');
        $filter = ['id'=>$id];
        $result = $this->{$this->model}->single($filter);
        $exclude_fields = $this->{$this->model}->exclude_keys;

        if($result) {

            $warehouse_keys = $this->{$this->model}->keys;

            $result = filter_array_keys($result,$exclude_fields);
            foreach ($warehouse_keys as $new=>$old) {
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

        _response_data('warehouses',$result);
        return true;

    }

    public function _get_select_data($params=[]) {
        $include_select = isset($params['include_select'])?$params['include_select']:true;

        $status = isset($params['status'])?$params['status']:1;

        $filter = ['status'=>$status];

        $this->{$this->model}->order_by('title');
        $result = $this->{$this->model}->search($filter);
        if($result) {
            $result = get_select_array($result,'id','title',$include_select,'','Select '.$this->singular);
            return $result;
        }else{
            return [];
        }

    }

    protected function _load_files() {

    }
}
