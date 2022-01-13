<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Countries extends MY_Controller {

    public $module = 'countries';
    public $model = 'country';
    public $singular = 'Country';
    public $plural = 'Countries';
    public $edit_form = '';
    public function __construct()
    {
        parent::__construct();
        _model($this->model);
    }

    public function _select_data_get() {

        $params = [];
        $params['include_select'] = false;
        $params['status'] = 1;

        $result = $this->_get_select_data($params);

        _response_data('countries',$result);
        return true;

    }

    public function _get_select_data($params=[]) {
        $include_select = isset($params['include_select'])?$params['include_select']:true;

        $status = isset($params['status'])?$params['status']:1;

        $filter =[];// ['status'=>$status];

        $this->{$this->model}->order_by('name');
        $result = $this->{$this->model}->search($filter,500);
        if($result) {
            $result = get_select_array($result,'id','name',$include_select,null,'Select '.$this->singular);
            return $result;
        }else{
            return [];
        }

    }

}
