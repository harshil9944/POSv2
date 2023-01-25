<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Promotions extends MY_Controller {

    public $module = 'promotions';
    public $model = 'promotion';
    public $singular = 'Promotion';
    public $plural = 'Promotions';
    public $language = 'promotions/promotions';
    public $edit_form = '';
    public $form_xtemplate = 'promotions_form_xtemplate';

    public function __construct()
    {
        parent::__construct();
        _model($this->model);
        $params = [
            'migration_path' => PROMOTIONS_MIGRATION_PATH,
            'migration_table' => PROMOTIONS_MIGRATION_TABLE
        ];
        $this->init_migration($params);
    }
    public function index()
	{
        _library('table');

        $filters = [];
        $filters['filter'] = [];
	    $areas = $this->_search($filters);

        $body=[];

        if($areas) {
            foreach ($areas as $area) {
                $action = _edit_link(base_url('areas/edit/'.$area['id'])) . _vue_delete_link('handleRemove('.$area['id'].')');
                $action_cell = [
                    'class' =>  'text-center w-70p',
                    'data'  =>  $action
                ];

                $body[] = [
                    'title'		    =>	$area['title'],
                    'description'	=>	$area['description'],
                    $action_cell
                ];
            }
        }

        $heading = [
            'TITLE',
            'DESCRIPTION',
            ['data'=>'Action','class'=>'text-center w-100p']
        ];

        _vars('table_heading',$heading);
        _vars('table_body',$body);
        $table = _view(DATA_TABLE_PATH);

        $page = [
            'singular'  =>  $this->singular,
            'plural'    =>  $this->plural,
            'add_url'   =>  base_url('areas/add'),
            'table'     =>  $table
        ];
        _vars('page_data',$page);

        _set_additional_component(LIST_XTEMPLATE_PATH,'outside');

        _set_page_heading($this->plural);
        _set_layout_type('wide');
        _set_layout(LIST_VIEW_PATH);
	}

    public function _populate_get() {

        _model('areas/area_table','table');

        $params = [];
        $params['filter'] = [];
        $params['exclude'] = true;
        $params['convert'] = true;
        $areas = $this->_search($params);

        $tables = _get_module('areas/tables','_search',$params);
        if($tables) {
            $temp = [];
            foreach ($tables as $table) {

                $table['durationSince'] = '';

                $temp[] = $table;
            }
            $tables = $temp;
        }

        _response_data('areas',$areas);
        _response_data('tables',$tables);

        return true;

    }

    public function _get_menu() {

        $menus = [];

        /*$areas = [];

        $areas[] = [
            'name'	    =>  'Areas',
            'class'     =>  '',
            'group'     =>  '',
            'icon'      =>  'basket-loaded',
            'path'      =>  'areas',
            'module'    =>  'areas',
            'children'  =>  []
        ];

        $areas[] = [
            'name'	    =>  'Tables',
            'class'     =>  '',
            'group'     =>  '',
            'icon'      =>  'basket-loaded',
            'path'      =>  'areas/tables',
            'module'    =>  'areas/tables',
            'children'  =>  []
        ];

        $menus[] = array(
            'id'        => 'menu-areas',
            'class'     => '',
            'icon'      => 'si si-bar-chart',
            'group'     => 'module',
            'name'      => 'Areas',
            'path'      => 'areas',
            'module'    => 'areas',
            'priority'  => 5,
            'children'  => $areas
        );*/

        return $menus;

    }

    public function add() {

        $this->_add();

        /*_set_js_var('back_url',base_url('areas'),'s');
        _set_js_var('mode','add','s');
	    _set_page_heading('Add areas');
	    _set_layout('areas_form_view');*/

    }
    public function edit($id='') {

        if(!$id) {
            _set_message('User was not found.','warning');
            $this->_redirect('areas');
        }

        $area = $this->{$this->model}->single(['id'=>$id]);

        _set_js_var('area',$area,'j');

        $this->_edit($id);

        /*_set_js_var('back_url',base_url('users'),'s');
        _set_js_var('mode','edit','s');
        _set_js_var('user',$user,'j');*/

      //  _set_page_heading('Edit User');
        //_set_layout('areas_form_view');

    }

    public function _action_put() {
        _model('area');


        $area = _input('area');
     //dump_exit($area);
        $data = [
            'title'         =>  $area['title'],
            'description'   =>  $area['description'],
            'added'         =>  sql_now_datetime()
        ];

        $affected_rows = $this->{$this->model}->insert($data);

        if($affected_rows) {
            _response_data('redirect',base_url('areas'));
            return true;
        }else{
            return false;
        }
    }

    public function _action_post() {

        _model('area');


        $area = _input('area');


        $data = [
            'title'         =>  $area['title'],
            'description'   =>  $area['description'],
            //'default_page'  =>  $area['default_page']
        ];


        $filter = ['id'=>$area['id']];
        $affected_rows = $this->{$this->model}->update($data,$filter);

        if($affected_rows) {
            _response_data('redirect',base_url('areas'));
            return true;
        }else{
            return false;
        }
    }

    public function _action_delete() {

        _model('area');

        $ignore_list = [1];

        $id = _input('id');

        if(!in_array($id,$ignore_list)) {
            $filter = ['id' => $id];

            $affected_rows = $this->{$this->model}->delete($filter);

            if ($affected_rows) {
                _response_data('redirect', base_url('areas'));
                return true;
            } else {
                return false;
            }
        }else{
            _response_data('message','You cannot delete a protected user.');
            return false;
        }
    }

    public function _get_pos_promotions() {

        $result = [];

        _model('promotion_criteria','criteria');
        _model('promotion_criteria_product','criteria_product');
        _model('promotion_reward','reward');
        _model('promotion_reward_product','reward_product');

        $is_weekend = $this->is_today_weekend();

        $promotion_table = PROMOTIONS_TABLE;

        $query = "SELECT * FROM $promotion_table WHERE end_date > NOW()";
        $query .= " AND status=1";
        if($is_weekend) {
            $query .= " AND (offer_days='all' OR offer_days='weekend')";
        }else{
            $query .= " AND (offer_days='all' OR offer_days='weekdays')";
        }

        $promotions = _db_get_query($query);
        if($promotions) {

            foreach ($promotions as $promotion) {

                $promotion_id = $promotion['id'];
                $promotion['offer_criteria'] = unserialize($promotion['offer_criteria']);

                $criteria = false;
                if($promotion['offer_type']!='basic') {
                    $criteria = $this->criteria->single(['promotion_id' => $promotion_id]);
                }
                $reward = $this->reward->single(['promotion_id'=>$promotion_id]);

                if($reward) {

                    if($criteria) {
                        $criteria_include = $this->criteria_product->search(['type' => 'include', 'promotion_id' => $promotion_id]);

                        if ($criteria_include) {
                            $this->_exclude_keys($criteria_include, $this->criteria_product->exclude_keys,true);
                            $this->_sql_to_vue($criteria_include, $this->criteria_product->keys,true);
                            $criteria['include'] = $criteria_include;
                        } else {
                            $criteria['include'] = false;
                        }

                        $criteria_exclude = $this->criteria_product->search(['type' => 'exclude', 'promotion_id' => $promotion_id]);

                        if ($criteria_exclude) {
                            $this->_exclude_keys($criteria_exclude, $this->criteria_product->exclude_keys,true);
                            $this->_sql_to_vue($criteria_exclude, $this->criteria_product->keys,true);
                            $criteria['exclude'] = $criteria_exclude;
                        } else {
                            $criteria['exclude'] = false;
                        }

                        $this->_exclude_keys($criteria, $this->criteria->exclude_keys);
                        $this->_sql_to_vue($criteria, $this->criteria->keys);

                        $promotion['criteria'] = $criteria;
                    }

                    $reward_include = $this->reward_product->search(['type' => 'include', 'promotion_id' => $promotion_id]);

                    if ($reward_include) {
                        $this->_exclude_keys($reward_include, $this->reward_product->exclude_keys,true);
                        $this->_sql_to_vue($reward_include, $this->reward_product->keys,true);
                        $reward['include'] = $reward_include;
                    } else {
                        $reward['include'] = false;
                    }

                    if($promotion['offer_type']!='basic') {
                        $reward_exclude = $this->reward_product->search(['type' => 'exclude', 'promotion_id' => $promotion_id]);

                        if ($reward_exclude) {
                            $this->_exclude_keys($reward_exclude, $this->reward_product->exclude_keys, true);
                            $this->_sql_to_vue($reward_exclude, $this->reward_product->keys, true);
                            $reward['exclude'] = $reward_exclude;
                        } else {
                            $reward['exclude'] = false;
                        }
                    }else{
                        $reward['exclude'] = false;
                    }

                    $this->_exclude_keys($reward, $this->reward->exclude_keys);
                    $this->_sql_to_vue($reward, $this->reward->keys);

                    $promotion['reward'] = $reward;

                    $this->_exclude_keys($promotion);
                    $this->_sql_to_vue($promotion);

                    $result[] = $promotion;
                }

            }

        }
        return $result;
    }

    private function is_today_weekend() {
        return in_array(date("l"), ["Saturday", "Sunday"]);
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

            _page_script_override('areas/areas-form');

            $this->layout = 'areas_form_view';

        }

    }
}
