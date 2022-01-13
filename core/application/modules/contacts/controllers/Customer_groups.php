<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
class Customer_groups extends MY_Controller {

    public $module = 'contacts/customer_groups';
    public $model = 'customer_group';
    public $singular = 'Customer Group';
    public $plural = 'Customer Groups';
    public $language = 'contacts/customers';
    public $edit_form = '';
    public $form_xtemplate = 'customer_groups_form_xtemplate';
    public function __construct()
    {
        parent::__construct();
    }

    // Public methods Start

    public function index()	{
        $table = $this->{$this->model}->table;
        _library('table');
       
        $results = $this->{$this->model}->search();

        $body = [];
        if($results) {
            foreach ($results as $result) {
                $action = _edit_link(base_url($this->module.'/edit/'.$result['id'])) . _vue_delete_link('handleRemove('.$result['id'].')');
                $action_cell = [
                    'class' =>  'text-center',
                    'data'  =>  $action
                ];
                $body[] = [
                    $result['title'],
                    ($result['status'])?'<span class="text-primary">Enabled</span>':'<span class="text-muted">Disabled</span>',
                    $action_cell
                ];
            }
        }

        $heading = [
           'TITLE',
            'STATUS',
            ['data'=>'Action','class'=>'text-center no-sort w-110p']
        ];

        _vars('table_heading',$heading);
        _vars('table_body',$body);
        $table = _view(DATA_TABLE_PATH);
        $page = [
            'singular'      =>  $this->singular,
            'plural'        =>  $this->plural,
            'add_url'       =>  base_url($this->module.'/add'),
            'vue_add_url'   =>  '',
            'table'         =>  $table,
            'edit_form'     =>  '',
        ];
        _vars('page_data',$page);
        _set_additional_component(LIST_XTEMPLATE_PATH,'outside');
        _set_layout_type('wide');
        _set_page_title($this->plural);
        _set_page_heading($this->plural);
        _set_layout(LIST_VIEW_PATH);
    }

    public function add() {
        $this->_add();
    }

    public function edit($id) {
        $this->_edit($id);
    }
    public function _action_put() {
        _model('customer_address','address');
        $obj = _input('obj');
        $this->_prep_obj($obj);
        $obj['added'] = sql_now_datetime();
        if($this->{$this->model}->insert($obj)) {
            $redirect = base_url($this->module);
            _response_data('redirect',$redirect);
        }else{
            _response_data('message','Something wrong. Please try again.');
        }
        return true;
    }

    public function _action_post() {
        _model('customer_address','address');
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

  

    private function _prep_obj(&$obj) {
        $this->_vue_to_sql($obj);
    }

    public function _action_delete() {
        $id = _input('id');
        $ignore_list = [1];
   

        if(!in_array($id,$ignore_list)) {
            $filter = ['id' => $id];

            $result = $this->{$this->model}->single($filter);

            if($result) {
                $affected_rows = $this->{$this->model}->delete($filter);

                if ($affected_rows) {
                    _response_data('redirect', $this->module);
                    _response_data('message',$this->singular . ' has been deleted successfully');
                    return true;
                } else {
                    _response_data('message','Something went wrong. Please try again');
                    return false;
                }

            }else{
                _response_data('message','The requested ' . $this->singular . ' was not found');
                return false;
            }
        }else{
            _response_data('message','You do not have enough privilege to delete this ' . $this->singular);
            return false;
        }
    }

    public function _single_get() {

        $id = _input('id');
        $result = $this->_single(['id'=>$id]);

        if($result) {
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
        if($result) {
            $this->_sql_to_vue($result);
            return $result;
        }
    }

    public function _select_data_get() {

        $params = [];
        $params['include_select'] = false;
        $result = $this->_get_select_data($params);

        _response_data('customers',$result);
        return true;

    }

    public function _get_select_data($params=[]) {
        $include_select = isset($params['include_select'])?$params['include_select']:true;

        $filter = ['status'=>1];
        $this->{$this->model}->order_by('title');
        $result = $this->{$this->model}->search($filter);
        if($result) {
            $result = get_select_array($result,'id','title',$include_select,'0','Select '.$this->singular);
            return $result;
        }else{
            return [];
        }

    }

    public function _get_data($param=[]) {
        $filter = ['id'=>$param['customer_id']];
        $convert_vue = (isset($param['convert_vue']) && $param['convert_vue'])?true:false;
        $result = $this->{$this->model}->single($filter);
        if($result) {
            if($convert_vue) {
                $contact_keys = $this->{$this->model}->keys;
                foreach ($contact_keys as $old => $new) {
                    change_array_key($new,$old,$result);
                }
            }
            return $result;
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

        $list_pages = ['index'];
        if(in_array(_get_method(),$list_pages)) {
            _load_plugin(['dt']);
        }

        if(_get_method()=='add' || _get_method()=='edit') {
            _helper('control');
            $this->layout = 'customer_groups_form_view';
            _page_script_override('contacts/customer-groups-form');
        }

    }
}
