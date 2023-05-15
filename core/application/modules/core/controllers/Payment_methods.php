<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Payment_methods extends MY_Controller {

    public $module = 'payment_methods';
    public $model = 'payment_method';
    public $singular = 'Payment Method';
    public $plural = 'Payment Methods';
    public $edit_form = '';
    public function __construct()
    {
        parent::__construct();
        _model($this->model);
    }

    public function _single_get() {
        _model($this->model);

        $id = _input('id');
        $filter = ['id'=>$id];
        $result = $this->{$this->model}->single($filter);

        if($result) {

            _response_data('obj',$result);

        }else{

            _set_message('The requested details could not be found.','warning');
            _response_data('redirect',base_url($this->module));

        }
        return true;
    }

    public function _get_list_pos() {

        $keys = $this->{$this->model}->keys;

        $result = $this->{$this->model}->search();

        $temp = [];
        foreach ($result as $row) {
            foreach ($keys as $vue=>$sql) {
                change_array_key($sql, $vue, $row);
            }
            $temp[] = $row;
        }
        $result = $temp;

        return $result;
    }

    public function _select_data_get() {

        $params = [];
        $params['include_select'] = false;

        $payment_methods = $this->_get_select_data($params);

        _response_data('payment_methods',$payment_methods);
        return true;

    }

    public function _get_select_data($params=[]) {
        $include_select = isset($params['include_select'])?$params['include_select']:true;

        $methods = $this->{$this->model}->search();
        if($methods) {
            $methods = get_select_array($methods,'id','title',$include_select,'','Select '.$this->singular);
            return $methods;
        }else{
            return [];
        }

    }

    public function _action_put() {

        $obj = _input('obj');
        $this->_prep_obj($obj);

        $obj['added'] = sql_now_datetime();
        $obj['parent'] = NULL;
        $obj['code'] = $obj['title'];

        if($this->{$this->model}->insert($obj)) {
            $id = $this->{$this->model}->insert_id();
            $result = [
                'id'    =>  $id,
                'title' =>  $obj['title']
            ];
            _response_data('obj',$result);
        }else{
            _response_data('message','Something wrong. Please try again.');
        }
        return true;

    }

    public function _action_post() {

        $obj = _input('obj');
        $this->_prep_obj($obj);

        $id = $obj['id'];

        unset($obj['id']);

        $filter=[
            'id'    =>  $id
        ];

        if($this->{$this->model}->update($obj,$filter)) {
            $redirect = base_url($this->module);
            _response_data('redirect',$redirect);
        }else{
            _response_data('message','Something wrong. Please try again.');
        }
        return true;

    }

    public function _action_delete() {

        $id = _input('id');

        $result = $this->_delete($id);

        _response_data('message',_get_var('message'));

        return true;

    }

    protected function _delete($id) {

        $ignore_list = [];

        if(!in_array($id,$ignore_list)) {

            $filter = ['id' => $id];

            $result = $this->{$this->model}->single($filter);

            if($result) {

                $affected_rows = $this->{$this->model}->delete($filter);

                if ($affected_rows) {
                    _vars('message',$this->singular . ' has been deleted successfully');
                    return true;
                } else {
                    _vars('message','Something went wrong. Please try again');
                    return false;
                }

            }else{
                _vars('message','The requested ' . $this->singular . ' was not found');
                return false;
            }
        }else{
            _vars('message','You cannot delete a protected ' . $this->singular);
            return false;
        }

    }

    private function _prep_obj(&$obj) {
        $unit_keys = $this->{$this->model}->keys;

        foreach ($unit_keys as $old => $new) {
            change_array_key($old,$new,$obj);
        }

    }

}
