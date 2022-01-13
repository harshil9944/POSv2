<?php
class API_Controller extends MX_Controller {

    public $module;
    public $method;
	function __construct () {
        $this->autoload = [
            'config'    =>  ['sys_config','app_config'],
            'helper'    =>  ['system','ajax','url'],
            'libraries' =>  ['database','session']
        ];
        parent::__construct();
        $this->module = _input_server('HTTP_MODULE');
        $this->method = _input_server('HTTP_METHOD');
        $this->module = ($this->module)?$this->module:_input('module');
        $this->method = ($this->method)?$this->method:_input('method');
        /*if($this->module) {
            if (!$this->_validate_key()) {
                die('Illegal Request');
            }
        }*/
	}

	private function _validate_key() {
	    if(_is_ajax_request()) {

            $ignore_controllers = (_get_config('ignore_route_controllers'))?_get_config('ignore_route_controllers'):[];
            $ignore_methods = (_get_config('ignore_route_methods'))?_get_config('ignore_route_methods'):[];

            $controller = explode('/',$this->module);
            $controller = (isset($controller[0]))?$controller[0]:'';

            if(in_array($this->module,$ignore_methods)) {
                return true;
            }

            if(in_array($controller,$ignore_controllers)) {
                return true;
            }

            $key = _input_server('HTTP_KEY');
            $user_id = _input_server('HTTP_ID');

            if ($key) {
                if($key==_get_ajax_key($user_id)) {
                    return true;
                }
                return false;
            } else {
                return false;
            }
        }
	    return false;
    }

    public function _sql_to_vue(&$obj,$keys=[],$array=false) {
        if(!$keys) {
            $keys = $this->{$this->model}->keys;
        }
        if($array) {
            $temp=[];
            foreach ($obj as $value) {
                foreach ($keys as $vue => $sql) {
                    change_array_key($sql, $vue, $value);
                }
                $temp[] = $value;
            }
            $obj = $temp;
        }else{
            foreach ($keys as $vue => $sql) {
                change_array_key($sql, $vue, $obj);
            }
        }
    }

    public function _vue_to_sql(&$obj,$keys=[],$array=false) {
        if(!$keys) {
            $keys = $this->{$this->model}->keys;
        }
        if($array) {
            $temp=[];
            foreach ($obj as $value) {
                foreach ($keys as $vue => $sql) {
                    change_array_key($vue,$sql,$value);
                }
                $temp[] = $value;
            }
            $obj = $temp;
        }else{
            foreach ($keys as $vue => $sql) {
                change_array_key($vue,$sql,$obj);
            }
        }
    }

    public function _exclude_keys(&$obj,$keys=[],$array=false) {
        if(!$keys) {
            $keys = $this->{$this->model}->exclude_keys;
        }
        if($array) {
            $temp=[];
            foreach ($obj as $value) {
                foreach ($keys as $key) {
                    if (isset($value[$key])) {
                        unset($value[$key]);
                    }
                }
                $temp[] = $value;
            }
            $obj = $temp;
        }else{
            foreach ($keys as $key) {
                unset($obj[$key]);
            }
        }
    }

    public function _find($params=[]) {
        $exclude = false;
        $convert = false;
        if(isset($params['exclude'])) {
            if(is_array($params['exclude'])) {
                $exclude = $params['exclude'];
            }elseif ($params['exclude']===true) {
                $exclude = $this->{$this->model}->exclude_keys;
            }
        }
        if(isset($params['convert'])) {
            if(is_array($params['convert'])) {
                $convert = $params['convert'];
            }elseif ($params['convert']===true) {
                $convert = $this->{$this->model}->keys;
            }
        }

        $result = $this->{$this->model}->single($params['filter']);
        if($result) {
            if ($exclude) {
                $this->_exclude_keys($result, $exclude);
            }
            if ($convert) {
                $this->_sql_to_vue($result, $convert);
            }
        }
        return $result;
    }

    public function _search($params=[]) {

        $filter = $params['filter'];
        $limit = (isset($params['limit']) && is_int($params['limit']))?$params['limit']:_get_setting('global_limit',50);
        $offset = (isset($params['offset']) && is_int($params['offset']))?$params['offset']:0;
        $orders = (isset($params['orders']) && is_array($params['orders']))?$params['orders']:[];
        $and_likes = (isset($params['and_likes']) && is_array($params['and_likes']))?$params['and_likes']:[];
        $or_likes = (isset($params['or_likes']) && is_array($params['or_likes']))?$params['or_likes']:[];
        $exclude = false;
        $convert = false;
        if(isset($params['exclude'])) {
            if(is_array($params['exclude'])) {
                $exclude = $params['exclude'];
            }elseif ($params['exclude']===true) {
                $exclude = $this->{$this->model}->exclude_keys;
            }
        }
        if(isset($params['convert'])) {
            if(is_array($params['convert'])) {
                $convert = $params['convert'];
            }elseif ($params['convert']===true) {
                $convert = $this->{$this->model}->keys;
            }
        }
        if($and_likes) {
            foreach ($and_likes as $field=>$value) {
                $this->{$this->model}->like($field,$value);
            }
        }
        if($or_likes) {
            foreach ($or_likes as $field=>$value) {
                $this->{$this->model}->or_like($field,$value);
            }
        }
        if($orders) {
            foreach ($orders as $order) {
                $this->{$this->model}->order_by($order['order_by'],$order['order']);
            }
        }
        $records = $this->{$this->model}->search($filter,$limit,$offset);
        if($records) {
            $temp = [];
            foreach ($records as $single) {
                if($exclude) {
                    $this->_exclude_keys($single, $exclude);
                }
                if($convert) {
                    $this->_sql_to_vue($single, $convert);
                }
                $temp[] = $single;
            }
            $records = $temp;
        }
        return $records;

    }

    public function _search_count($params=[]) {

        /*$filter = $params['filter'];
        $this->{$this->model}->select('COUNT(*) as total');
        $count = $this->{$this->model}->single($filter);
        return $count['total'];*/
        $result = $this->_search($params);
        return ($result)?count($result):0;

    }

    public function _insert($params=[]) {
        $insert = $params['data'];
        if($this->{$this->model}->insert($insert)) {
            return $this->{$this->model}->insert_id();
        }else{
            return false;
        }
    }

    public function _update($params=[]) {
        $update = $params['data'];
        $filter = $params['filter'];
        return $this->{$this->model}->update($update,$filter);
    }

    public function _soft_remove($params=[]) {
        $update = ['deleted'=>1,'deleted_at'=>sql_now_datetime()];
        $filter = ['id'=>$params['id']];
        return $this->{$this->model}->update($update,$filter);
    }

    public function _remove($params=[]) {
        $id = $params['id'];
        $filter = ['id'=>$id];
        return $this->{$this->model}->delete($filter);
    }

	function __destruct()
    {
        parent::__destruct();
    }

    function _options_response() {
        $this->output
            ->set_status_header(200)
            ->set_content_type('application/json', 'utf-8')
            ->set_output('GET,POST,HEAD')
            ->_display();
        die();
    }
    function _response($code) {

        $ajax_data = _get_response_data();
        $this->output
            ->set_status_header($code)
            ->set_content_type('application/json', 'utf-8')
            ->set_output(json_encode($ajax_data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES))
            ->_display();
        die();
    }
    function _ok_response() {
        _response_data('status','ok');
        $this->_response(200);
    }
    function _error_response() {
        _response_data('status','error');
        $this->_response(200);
    }
    function _invalid_request_response() {
        _response_data('status','error');
        _response_data('message','Invalid Request!');
        $this->_response(400);
    }
}
