<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cities extends MY_Controller {

    public $module = 'cities';
    public $model = 'city';
    public $singular = 'City';
    public $plural = 'cities';
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
        $params['state_id'] = _input('state_id');
        $params['status'] = 1;

        $result = $this->_get_select_data($params);

        _response_data('cities',$result);
        return true;

    }

    public function _get_select_data($params=[]) {
        $include_select = isset($params['include_select'])?$params['include_select']:true;

        $country_id = isset($params['country_id'])?$params['country_id']:'';
        $state_id = isset($params['state_id'])?$params['state_id']:'';
        $status = isset($params['status'])?$params['status']:1;

        $filter = ['country_id'=>$country_id,'status'=>$status,'state_id'=>$state_id];

        $this->{$this->model}->order_by('name');
        $result = $this->{$this->model}->search($filter,500);
        if($result) {
            $result = get_select_array($result,'id','name',$include_select,null,'Select '.$this->singular);
            return $result;
        }else{
            return [];
        }

    }
    public function _select_cities_get() {
        $result = $this->{$this->model}->search();
        $rows = [];
        if($result){
            foreach ($result as $c){
                $rows[]=[
                    'id' => $c['id'],
                    'name'=>  $c['name'],
                ];
            }
        }
        _response_data('cities',$rows);
        return true;
    }
    public function _select_city_get() {
        $city_id = _input('city_id');
        $result = $this->{$this->model}->single(['id'=>$city_id]);
        $this->_sql_to_vue($result);
        _response_data('city',$result);
        return true;
    }

}
