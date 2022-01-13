<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Routes extends MY_Controller {

    public $module = 'routes';
    public $model = 'route';
    public $singular = 'Route';
    public $plural = 'Routes';
    public $edit_form = '';
    public function __construct()
    {
        parent::__construct();
        _model('route');
    }

    public function index() {

        _library('table');

        $results = $this->{$this->model}->get_list();
        $body = [];
        if($results) {
            foreach ($results as $result) {
                $body[] = [
                    $result['id'],
                    $result['slug'],
                    ($result['status'])?'Enabled':'Disabled',
                ];
            }
        }

        _vars('table_heading',[array('data'=>'ID','class'=>'text-center w-50p'),'Title',array('data'=>'Action','class'=>'text-center w-50p')]);

        $heading = [
            array('data'=>'ID','class'=>'w-50p'),
            $this->singular . ' Slug',
            array('data'=>'Status','class'=>'w-50p'),
        ];

        _vars('table_heading',$heading);
        _vars('table_body',$body);
        $table = _view(DATA_TABLE_PATH);

        $button = [
            'url'   =>  base_url('routes/refresh'),
            'label' =>  'Refresh Routes',
            'icon'  =>  'fa fa-refresh'
        ];

        $page = [
            'singular'      =>  $this->singular,
            'plural'        =>  $this->plural,
            'add_url'       =>  '',
            'button'        =>  $button,
            'vue_add_url'   =>  '',
            'table'         =>  $table,
            'edit_form'     =>  ''
        ];
        _vars('page_data',$page);

        _set_additional_component(LIST_XTEMPLATE_PATH,'outside');

        _set_page_heading($this->plural);
        _set_layout(LIST_VIEW_PATH);

	}

	public function refresh() {

        $this->view = false;

        $this->route->refresh();

        _set_message('Routes refreshed successfully','success');
        $this->_redirect('routes','refresh');

    }

    public function _load_files() {
        if(_get_method()=='index') {
            _load_plugin('dt');
            _page_script_override('routes');
        }
    }
}
