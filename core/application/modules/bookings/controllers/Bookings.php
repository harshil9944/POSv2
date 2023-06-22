<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Bookings extends MY_Controller
{

    public $module = 'bookings';
    public $model = 'booking';
    public $singular = 'Booking';
    public $plural = 'Bookings';
    public $language = 'bookings/bookings';
    public $edit_form = '';
    public $form_xtemplate = 'bookings_form_xtemplate';

    public function __construct()
    {
        parent::__construct();
        _model($this->model);
        $params = [
            'migration_path'  => BOOKINGS_MIGRATION_PATH,
            'migration_table' => BOOKINGS_MIGRATION_TABLE
        ];
        $this->init_migration($params);
    }

    public function index()
    {
        _library('table');

        $filters = [];
        $filters['filter'] = [];
        $bookings = $this->_search($filters);

        $result = $this->_prep_booking($bookings);
        _set_js_var('events', $result, 'j');

        /*$filters = [];
        $filters['filter'] = [];
        $bookings = $this->_search($filters);
        $body = [];
        if ($bookings) {
            foreach ($bookings as $booking) {
                $action = _edit_link(base_url('bookings/edit/' . $booking['id'])) . _vue_delete_link('handleRemove(' . $booking['id'] . ')');
                $action_cell = [
                    'class' => 'text-center w-110p',
                    'data'  => $action
                ];
                $body[] = [
                    'title'       => $booking['title'],
                    'description' => $booking['description'],
                    'sort_order'  => $booking['sort_order'],
                    $action_cell
                ];
            }
        }
        $heading = [
            'TITLE',
            'DESCRIPTION',
            'SORT ORDER',
            ['data' => 'Action', 'class' => 'text-center w-100p']
        ];

        _vars('table_heading', $heading);
        _vars('table_body', $body);
        $table = _view(DATA_TABLE_PATH);*/

        $page = [
            'singular' => $this->singular,
            'plural'   => $this->plural,
            'add_url'  => base_url('bookings/add'),
            //'table'    => $table
        ];
        _vars('page_data', $page);

        _set_additional_component('bookings_xtemplate_view', 'outside');


        _set_page_heading($this->plural);
        _set_layout_type('wide');
        _set_layout('bookings_view');
    }

    private function _prep_booking(&$bookings)
    {
        $result = [];
        if($bookings){
            foreach($bookings as $b){
                $array = [
                    'title'=>$b['booking_name'],
                    'start'=>$b['date'],
                    'end'=>$b['date'],
                    'textColor'=>$this->_get_status_color($b['status']),
                    'url'=>base_url('bookings/inquiries/show/'.$b['id']),
                ];
                $result[] = $array;
            }

        }

        return $result;

    }

    private function _get_status_color($status){
        switch ( $status ) {
            case 1:
                return '#666';
            case 2:
                return '#080';
            case 3:
                return '#800';
            case 4:
                return '#aaa';
            case 5:
                return '#800';
            default:
                return 'black';
            }

    }

    public function _populate_get()
    {
        _model('bookings/booking_table', 'table');
        $params = [];
        $params['filter'] = [];
        $params['exclude'] = true;
        $params['convert'] = true;
        $params['orders'] = [['order_by' => 'sort_order', 'order' => 'ASC']];
        $bookings = $this->_search($params);
        $tables = _get_module('bookings/tables', '_search', $params);
        if ($tables) {
            $temp = [];
            foreach ($tables as $table) {

                $table['durationSince'] = '';

                $temp[] = $table;
            }
            $tables = $temp;
        }
        _response_data('bookings', $bookings);
        _response_data('tables', $tables);
        return true;
    }

    public function _get_menu()
    {
        $menus = [];

        $bookings = [];
        $bookings[] = array(
            'name'	    =>  'Inquiries',
            'class'     =>  '',
            'group'     =>  '',
            'icon'      =>  'basket-loaded',
            'path'      =>  'bookings/inquiries',
            'module'    =>  'bookings',
            'children'  =>  []
        );

        $bookings[] = array(
            'name'	    =>  'Bookings',
            'class'     =>  '',
            'group'     =>  '',
            'icon'      =>  'basket-loaded',
            'path'      =>  'bookings',
            'module'    =>  'bookings',
            'children'  =>  []
        );

        $menus[] = array(
            'id'       => 'menu-bookings',
            'class'    => '',
            'icon'     => 'si si-calendar',
            'group'    => 'module',
            'name'     => 'Bookings',
            'path'     => 'bookings',
            'module'   => 'bookings',
            'priority' => 1,
            'children' => $bookings
        );
        return $menus;
    }

    public function add()
    {
        $this->_add();
    }

    public function edit($id)
    {
        $booking = $this->{$this->model}->single(['id' => $id]);
        $this->_exclude_keys($booking);
        $this->_sql_to_vue($booking);
        _set_js_var('booking', $booking, 'j');
        $this->_edit($id);
    }

    public function _action_put()
    {
        _model('booking');
        $obj = _input('booking');
        $this->_prep_obj($obj);
        $obj['added'] = sql_now_datetime();
        $affected_rows = $this->{$this->model}->insert($obj);
        if ($affected_rows) {
            _response_data('redirect', base_url('bookings'));
            return true;
        } else {
            return false;
        }
    }

    public function _action_post()
    {
        _model('booking');
        $obj = _input('booking');
        $this->_prep_obj($obj);
        $filter = ['id' => $obj['id']];
        $affected_rows = $this->{$this->model}->update($obj, $filter);
        if ($affected_rows) {
            _response_data('redirect', base_url('bookings'));
            return true;
        } else {
            return false;
        }
    }

    public function _action_delete()
    {
        _model('booking');
        $ignore_list = [1];
        $id = _input('id');
        if (!in_array($id, $ignore_list)) {
            $filter = ['id' => $id];
            $affected_rows = $this->{$this->model}->delete($filter);
            if ($affected_rows) {
                _response_data('redirect', base_url('bookings'));
                return true;
            } else {
                return false;
            }
        } else {
            _response_data('message', 'You cannot delete a protected user.');
            return false;
        }
    }

    private function _prep_obj(&$obj)
    {
        $this->_vue_to_sql($obj);
    }

    public function _install()
    {
        return true;
    }

    public function _uninstall()
    {
        return true;
    }

    protected function _load_files()
    {
        if (_get_method() == 'index') {
            _load_plugin(['dt']);
            _set_js_var('timezone', _get_timezone(), 'j');

            _enqueue_cdn_script('https://cdn.jsdelivr.net/npm/@fullcalendar/core@6.1.8/index.global.min.js','header');
            _enqueue_cdn_script('https://cdn.jsdelivr.net/npm/@fullcalendar/web-component@6.1.8/index.global.min.js','header');
            _enqueue_cdn_script('https://cdn.jsdelivr.net/npm/@fullcalendar/daygrid@6.1.8/index.global.min.js','header');
            _enqueue_cdn_script('https://cdn.jsdelivr.net/npm/@fullcalendar/list@6.1.8/index.global.min.js','header');
        }
        if (_get_method() == 'add' || _get_method() == 'edit') {
            //  _load_plugin(['vue_multiselect','moment','datepicker']);
            _page_script_override('bookings/bookings-form');
            $this->layout = 'bookings_form_view';
        }
    }
}
