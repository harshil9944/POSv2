<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Templates extends MY_Controller {

    public $module = 'printers/templates';
    public $model = 'template';
    public $singular = 'Template';
    public $plural = 'Templates';
    public $language = 'printers/printers';
    public $edit_form = '';
    public $form_xtemplate = 'templates_form_xtemplate';

    public function __construct()
    {
        parent::__construct();
        _model($this->model);
    }
    public function index()
	{
        
        _library('table');
        $filters = [];
        $filters['filter'] = [];
	    $templates = $this->_search($filters);

        $body=[];

        if($templates) {
            foreach ($templates as $template) {
                $action = _edit_link(base_url('printers/templates/edit/'.$template['id'])) . _vue_delete_link('handleRemove('.$template['id'].')');
                $action_cell = [
                    'class' =>  'text-center w-110p',
                    'data'  =>  $action
                ];

                $body[] = [
                    'title'		 =>	$template['title'],
                    $action_cell
                ];
            }
        }

        $heading = [
            'TITLE',
           
            ['data'=>'Action','class'=>'text-center w-100p']
        ];
        _vars('table_heading',$heading);
        _vars('table_body',$body);
        $table = _view(DATA_TABLE_PATH);

      

        $page = [
            'singular'  =>  $this->singular,
            'plural'    =>  $this->plural,
            'add_url'   =>  base_url('printers/templates/add'),
            'table'         =>  $table,
           
        ];
        _vars('page_data',$page);

        _set_additional_component(LIST_XTEMPLATE_PATH,'outside');

        _set_page_heading($this->plural);
        _set_layout_type('wide');
        _set_layout(LIST_VIEW_PATH);
	}

   

    

    public function add() {

        $this->_add();

        /*_set_js_var('back_url',base_url('areas'),'s');
        _set_js_var('mode','add','s');
	    _set_page_heading('Add areas');
	    _set_layout('areas_form_view');*/

    }

    public function edit($id) {

        
        $template = $this->{$this->model}->single(['id'=>$id]);

        $this->_exclude_keys($template);
        $this->_sql_to_vue($template);

        _set_js_var('template',$template,'j');

        $this->_edit($id);

        /*_set_js_var('back_url',base_url('users'),'s');
        _set_js_var('mode','edit','s');
        _set_js_var('user',$user,'j');*/

      //  _set_page_heading('Edit User');
        //_set_layout('areas_form_view');

    }

    public function _action_put() {
        _model('template');

        $obj = _input('template');
        $this->_prep_obj($obj);
       
       
        $obj['added'] = sql_now_datetime();
        $affected_rows = $this->{$this->model}->insert($obj);

        if($affected_rows) {
            _response_data('redirect',base_url('printers/templates'));
            return true;
        }else{
            return false;
        }
        
    }

    public function _action_post() {

        _model('template');

        $obj = _input('template');
        $this->_prep_obj($obj);
        $filter = ['id'=>$obj['id']];
        $affected_rows = $this->{$this->model}->update($obj,$filter);
        

        if($affected_rows) {
            _response_data('redirect',base_url('printers/templates'));
            return true;
        }else{
            return false;
        }
    }

    public function _action_delete() {

        _model('template');

        $ignore_list = [1];

        $id = _input('id');

        if(!in_array($id,$ignore_list)) {
            $filter = ['id' => $id];

            $affected_rows = $this->{$this->model}->delete($filter);

            if ($affected_rows) {
                _response_data('redirect', base_url('printers/templates'));
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
          

          
         
         
            _page_script_override('printers/templates-form');

            $this->layout = 'templates_form_view';

        }

    }
}
