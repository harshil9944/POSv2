<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Salutations extends MY_Controller {

    public $module = 'salutations';
    public $model = 'salutation';
    public $singular = 'Salutation';
    public $plural = 'Salutations';
    public $edit_form = '';
    public function __construct()
    {
        parent::__construct();
        _model($this->model);
    }

    public function index() {}

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

    //Call by Ajax
    public function _select_data_get() {

        $params = [];
        $params['include_select'] = false;
        $result = $this->_get_select_data($params);

        _response_data($this->module,$result);
        return true;

    }

    //Call by Module
    public function _get_select_data($params=[]) {
        $include_select = isset($params['include_select'])?$params['include_select']:true;

        $result = $this->{$this->model}->get_active_list();
        if($result) {
            $result = get_select_array($result,'id','title',$include_select,'0',''.$this->singular);
            return $result;
        }else{
            return [];
        }

    }

    protected function _load_files() {

    }
}
