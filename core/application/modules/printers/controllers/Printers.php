<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Printers extends MY_Controller {

    public $module = 'printers';
    public $model = 'printer';
    public $singular = 'Printer';
    public $plural = 'Printers';
    public $language = 'printers/printers';
    public $edit_form = '';
    public $form_xtemplate = 'printers_form_xtemplate';

    public function __construct()
    {
        parent::__construct();
        _model($this->model);
        $params = [
            'migration_path' => PRINTERS_MIGRATION_PATH,
            'migration_table' => PRINTERS_MIGRATION_TABLE
        ];
        $this->init_migration($params);
    }
    public function index()
	{

        _library('table');
        $filters = [];
        $filters['filter'] = [];
	    $printers = $this->_search($filters);

        $body=[];

        if($printers) {
            foreach ($printers as $printer) {
                $action = _edit_link(base_url('printers/edit/'.$printer['id'])) . _vue_delete_link('handleRemove('.$printer['id'].')');
                $action_cell = [
                    'class' =>  'text-center w-110p',
                    'data'  =>  $action
                ];

                $body[] = [
                    'title'		 =>	$printer['title'],
                    'status'	=>	($printer['status'])?'<span class="text-primary">Enabled</span>':'<span class="text-muted">Disabled</span>',
                    'type'  	=>  $printer['type'],
                    'port'  	=>  $printer['port'],
                    'address'  	=>  $printer['address'],
                    'open_cash_drawer'=> $printer['open_cash_drawer']?'Enabled':'Disabled',
                    $action_cell
                ];
            }
        }

        $heading = [
            'TITLE',
            'STATUS',
            'TYPE',
            'PORT',
            'ADDRESS',
            'OPEN CASH DRAWER',
            ['data'=>'Action','class'=>'text-center w-100p']
        ];
        _vars('table_heading',$heading);
        _vars('table_body',$body);
        $table = _view(DATA_TABLE_PATH);



        $page = [
            'singular'  =>  $this->singular,
            'plural'    =>  $this->plural,
            'add_url'   =>  base_url('printers/add'),
            'table'         =>  $table,

        ];
        _vars('page_data',$page);

        _set_additional_component(LIST_XTEMPLATE_PATH,'outside');

        _set_page_heading($this->plural);
        _set_layout_type('wide');
        _set_layout(LIST_VIEW_PATH);
	}



    public function _get_menu() {

        $menus = [];

        $printers = [];

        $printers[] = [
            'name'	    =>  'Printers',
            'class'     =>  '',
            'group'     =>  '',
            'icon'      =>  'basket-loaded',
            'path'      =>  'printers',
            'module'    =>  'printers',
            'children'  =>  []
        ];

        $printers[] = [
            'name'	    =>  'Templates',
            'class'     =>  '',
            'group'     =>  '',
            'icon'      =>  'basket-loaded',
            'path'      =>  'printers/templates',
            'module'    =>  'printers',
            'children'  =>  []
        ];



        $menus[] = array(
            'id'        => 'menu-printers',
            'class'     => '',
            'icon'      => 'si si-printer',
            'group'     => 'module',
            'name'      => 'Printers',
            'path'      => 'printers',
            'module'    => 'printers',
            'priority'  => 5,
            'children'  => $printers
        );

        return $menus;

    }

    public function add() {

        $this->_add();

        /*_set_js_var('back_url',base_url('areas'),'s');
        _set_js_var('mode','add','s');
	    _set_page_heading('Add areas');
	    _set_layout('areas_form_view');*/

    }

    public function edit($id) {


        $printer = $this->{$this->model}->single(['id'=>$id]);

        $this->_exclude_keys($printer);
        $this->_sql_to_vue($printer);

        _set_js_var('printer',$printer,'j');

        $this->_edit($id);

        /*_set_js_var('back_url',base_url('users'),'s');
        _set_js_var('mode','edit','s');
        _set_js_var('user',$user,'j');*/

      //  _set_page_heading('Edit User');
        //_set_layout('areas_form_view');

    }

    public function _action_put() {
        _model('printer');

        $obj = _input('printer');
        $this->_prep_obj($obj);


        $obj['added'] = sql_now_datetime();

        $affected_rows = $this->{$this->model}->insert($obj);

        if($affected_rows) {
            _response_data('redirect',base_url('printers'));
            return true;
        }else{
            return false;
        }

    }

    public function _action_post() {

        _model('printer');

        $obj = _input('printer');
        $this->_prep_obj($obj);
        $filter = ['id'=>$obj['id']];
        $affected_rows = $this->{$this->model}->update($obj,$filter);


        if($affected_rows) {
            _response_data('redirect',base_url('printers'));
            return true;
        }else{
            return false;
        }
    }

    public function _action_delete() {

        _model('printer');

        $ignore_list = [1];

        $id = _input('id');

        if(!in_array($id,$ignore_list)) {
            $filter = ['id' => $id];

            $affected_rows = $this->{$this->model}->delete($filter);

            if ($affected_rows) {
                _response_data('redirect', base_url('printers'));
                return true;
            } else {
                return false;
            }
        }else{
            _response_data('message','You cannot delete a protected user.');
            return false;
        }
    }
    private function _prep_obj(&$obj) {
        $this->_vue_to_sql($obj);
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

          //  _load_plugin(['vue_multiselect','moment','datepicker']);


          _set_js_var('statuses',get_status_array('Enabled','Disabled'),'j');


            _page_script_override('printers/printers-form');

            $this->layout = 'printers_form_view';

        }

    }
}
