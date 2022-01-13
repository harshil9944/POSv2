<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class States extends MY_Controller {

    public $module = 'states';
    public $model = 'state';
    public $singular = 'State';
    public $plural = 'States';
    public $edit_form = '';
    public function __construct()
    {
        parent::__construct();
        _model($this->model);
    }

    public function _select_data_get() {

        $params = [];
        $params['include_select'] = (_input('include_select'))?_input('include_select'):true;
        $params['country_id'] = _input('country_id');
        $params['status'] = 1;

        $result = $this->_get_select_data($params);

        _response_data('states',$result);
        return true;

    }

    public function _get_select_data($params=[]) {
        $include_select = isset($params['include_select'])?$params['include_select']:true;

        $country_id = isset($params['country_id'])?$params['country_id']:'';
        $status = isset($params['status'])?$params['status']:1;

        $filter = ['country_id'=>$country_id,'status'=>$status];

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
