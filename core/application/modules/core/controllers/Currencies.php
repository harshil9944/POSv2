<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Currencies extends MY_Controller {

    public $module = 'currencies';
    public $model = 'currency';
    public $singular = 'Currency';
    public $plural = 'Currencies';
    public $edit_form = '';
    public function __construct()
    {
        parent::__construct();
        _model($this->model);
    }

    public function _select_data_get() {

        $params = [];
        $params['include_select'] = false;
        $result = $this->_get_select_data($params);

        _response_data('currencies',$result);
        return true;

    }

    public function _get_select_data($params=[]) {
        $include_select = isset($params['include_select'])?$params['include_select']:true;
        $fields = isset($params['fields'])?$params['fields']:[];

        $result = $this->{$this->model}->search();
        if($result) {
            $result = get_select_array($result,'id','title',$include_select,'','Select '.$this->singular,$fields);
            return $result;
        }else{
            return [];
        }

    }

}
