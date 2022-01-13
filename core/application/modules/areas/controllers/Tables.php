<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Tables extends MY_Controller {

    public $module = 'areas/tables';
    public $model = 'Area_table';
    public $singular = 'Table';
    public $plural = 'Tables';
    public $language = 'areas/tables';
    public $edit_form = '';
    public $form_xtemplate = 'tables_form_xtemplate';
    public function __construct()
    {
        parent::__construct();
        _model($this->model);
    }
    public function index()
	{
        _library('table');
        _model('areas/area','area');

        $filters = [];
        $filters['filter'] = [];
        $filters['orders'] = [['order_by'=>'sort_order','order'=>'ASC']];
	    $tables = $this->_search($filters);
        $area_ids =  array_values(array_unique(array_column($tables??[],'area_id')));
        $query = "SELECT aa.title,aa.id FROM ara_area aa  WHERE aa.id IN (" . implode(',',$area_ids) . ")";
        $areas = _db_get_query($query);
    

        if($tables) {
            foreach ($tables as $table) {
                $action = _edit_link(base_url('areas/tables/edit/'.$table['id'])) . _vue_delete_link('handleRemove('.$table['id'].')');
                $action_cell = [
                    'class' =>  'text-center w-110p',
                    'data'  =>  $action
                ];
                $area_name = '';
                if($areas){
                    foreach ($areas as $area) {
                        if((int)$area['id'] === (int)$table['area_id']){
                            $area_name = $area['title'];
                        }
                    }
                }
                $body[] = [
                    'area'		      =>    $area_name,
                    'title'           =>	$table['title'],
                    'description'     =>	$table['description'],
                    'sort_order'      =>    $table['sort_order'],
                    'max_seat'        =>	$table['max_seat'],
                    'short_name'      =>	$table['short_name'],
                    'status'          =>    ($table['status'] ==='available')?'<span class="text-primary">Available</span>':'<span class="text-danger">Enagaged</span>',
                    $action_cell
                ];
            }
        }
        $heading = [
            'AREA',
            'TITLE',
            'DESCRIPTION',
            'SORT ORDER',
            'MAX SEAT',
            'SHORT NAME',
            'STATUS',
            ['data'=>'Action','class'=>'text-center w-100p']
        ];
        _vars('table_heading',$heading);
        _vars('table_body',$body);
        $table = _view(DATA_TABLE_PATH);

        $page = [
            'singular'  =>  $this->singular,
            'plural'    =>  $this->plural,
            'add_url'   =>  base_url('areas/tables/add'),
            'table'     =>  $table
        ];
        _vars('page_data',$page);
        _set_additional_component(LIST_XTEMPLATE_PATH,'outside');
        _set_page_heading($this->plural);
        _set_layout_type('wide');
        _set_layout(LIST_VIEW_PATH);
    }

	public function add() {
        $this->_add();
    }
    public function edit($id='') {
        if(!$id) {
            _set_message('User was not found.','warning');
            $this->_redirect('areas/tables');
        }
        $table = $this->{$this->model}->single(['id'=>$id]);
        _set_js_var('table',$table,'j');
        $this->_edit($id);
    }

    public function _action_put() {
        $table = _input('table');
        $data = [
            'area_id'		=>	$table['area_id'],
            'title'         =>	$table['title'],
            'description'   =>	$table['description'],
            'max_seat'      =>	$table['max_seat'],
            'short_name'   =>	str_replace(' ','',$table['short_name']),
            'status'        =>  $table['status'],
            'sort_order'       =>  $table['sort_order'],
            'added'         =>  sql_now_datetime()
        ];
        $affected_rows = $this->{$this->model}->insert($data);
        if($affected_rows) {
            _response_data('redirect',base_url('areas/tables'));
            return true;
        }else{
            return false;
        }
    }

    public function _action_post() {
        $table = _input('table');
        $data = [
            'area_id'		=>	$table['area_id'],
            'title'         =>	$table['title'],
            'description'   =>	$table['description'],
            'max_seat'      =>	$table['max_seat'],
            'short_name'    =>	str_replace(' ','',$table['short_name']),
            'status'        =>  $table['status'],
            'sort_order'        =>  $table['sort_order'],
        ];
        $filter = ['id'=>$table['id']];
        $affected_rows = $this->{$this->model}->update($data,$filter);
        if($affected_rows) {
            _response_data('redirect',base_url('areas/tables'));
            return true;
        }else{
            return false;
        }
    }

    public function _action_delete() {
        $ignore_list = [];
        $id = _input('id');
        if(!in_array($id,$ignore_list)) {
            $filter = ['id' => $id];
            $affected_rows = $this->{$this->model}->delete($filter);
            if ($affected_rows) {
                _response_data('redirect', base_url('areas/tables'));
                return true;
            } else {
                return false;
            }
        }else{
            _response_data('message','You cannot delete a protected user.');
            return false;
        }
    }

    public function _reserve_post() {
        _model('areas/area_session','area_session');
        _model('areas/area_table','area_table');
        $table_id = _input('tableId');
        $seat_used = _input('seatUsed');
        $user_id = _get_user_id();
        $session_data = [
            'table_id'      =>  $table_id,
            'user_id'       =>  $user_id,
            'session_start' =>  sql_now_datetime()
        ];
        if($this->area_session->insert($session_data)) {
            $session_id = $this->area_session->insert_id();

            $table_data = [
                'session_id'    =>  $session_id,
                'seat_used'     =>  $seat_used,
                'status'        =>  'engaged',
                'use_since'     =>  $session_data['session_start']
            ];
            $this->area_table->update($table_data,['id'=>$table_id]);

            return true;
        }
        return false;
    }

    public function _release_post() {
        _model('areas/area_session','area_session');
        _model('areas/area_table','area_table');
        $table_id = _input('tableId');
        $session_id = _input('sessionId');
        $session_data = [
            'session_end' =>  sql_now_datetime()
        ];
        $this->area_session->update($session_data,['id'=>$session_id]);
        $table_data = [
            'session_id'    =>  '',
            'seat_used'     =>  '',
            'status'        =>  'available',
            'use_since'     =>  ''
        ];
        $this->area_table->update($table_data,['id'=>$table_id]);
        return true;
    }

    public function _get_order_table($params) {
        _model('area_relation');
        $order_id = $params['order_id'];
        $this->{$this->model}->left_join(AREAS_RELATION_TABLE,AREAS_RELATION_TABLE.'.table_id='.AREAS_TABLES_TABLE.'.id');
        $this->{$this->model}->select(AREAS_TABLES_TABLE.'.*');
        $this->area_relation->order_by('ara_relation.id','DESC');
        $result = $this->{$this->model}->single([AREAS_RELATION_TABLE.'.order_id'=>$order_id]);
        return ($result)?$result:false;
    }

    protected function _load_files() {
        if(_get_method()=='index') {
            _load_plugin(['dt']);
        }
	    if(_get_method()=='add' || _get_method()=='edit') {
            _load_plugin(['vue_multiselect','moment','datepicker']);
            _model('area');
            $filter = [];
            _set_js_var('areas',$this->area->search($filter),'j');
            _set_js_var('statuses',get_status_array('Available','Engaged','available','engaged'),'j');
            _page_script_override('areas/tables-form');
            $this->layout = 'tables_form_view';
        }
    }
}
