<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Inquiries extends MY_Controller
{

    public $module = 'bookings/inquiries';
    public $model = 'booking';
    public $singular = 'Booking';
    public $plural = 'Bookings';
    public $language = 'bookings/inquiries';
    public $edit_form = '';
    public $form_xtemplate = 'inquiries_form_xtemplate';

    public function __construct()
    {
        parent::__construct();
        _model($this->model);
    }

    public function index()
    {
        $table = $this->{$this->model}->table;
        _library('table');
        _set_js_var('exportUrl', base_url('contacts/customers/export'));
        _set_js_var('detailUrl', base_url($this->module . '/details'), 's');
        //  $results = $this->{$this->model}->get_list();
        $filter_dropdown_value = _input('filterDropdown');
        $offset = (_input('offset') && is_int((int)_input('offset'))) ? (int)_input('offset') : 0;
        $searchString = trim(_input('search'));
        $searchFields = [$table . '.date'];

        $filters = [];
        $filters['filter'] = [];
        if ($searchString) {
            $or_likes = [];
            foreach ($searchFields as $field) {
                $or_likes[$field] = $searchString;
            }
            $filters['or_likes'] = $or_likes;
            _set_js_var('searchString', $searchString);
        }

        $filters['orders'] = [['order_by' => $table . '.added', 'order' => 'DESC']];
        $filters['offset'] = $offset;
        $filters['limit'] = true;
        $results = $this->_search($filters);

        $total_items = $this->{$this->model}->get_list_count($filters);

        $total_rows = ($total_items) ? $total_items['total_rows'] : 0;
        $per_page = (int)_get_setting('global_limit', 50);
        $paginate_url = base_url($this->module);

        $can_edit = _can($this->module . '/edit', 'page');
        $can_delete = _can($this->module . '/remove', 'page');
        $can_show = _can($this->module . '/show', 'page');

        _model('booking_status');
        $statuses = $this->booking_status->search();

        /*_model('areas/area','area');
        $areas = $this->area->search();
        dd($areas);*/

        $body = [];
        if ($results) {
            foreach ($results as $result) {

                $id = $result['id'];
                $statusId = $result['status'];

                $status = array_values(array_filter($statuses, function ($status) use ($statusId) {
                    return ($status['id'] == $statusId);
                }));

                $action = '';
                /*$can_confirm = true;
                $can_reject = true;
                if($can_confirm && $statusId != 2) {
                    $action .= _vue_button_link("fa fa-check","handleConfirm($id)","Confirm this Booking");
                }
                if($can_reject && !in_array($statusId,[2,3,5])) {
                    $action .= _vue_button_link("fa fa-remove","handleReject($id)","Reject this Booking");
                }
                if($statusId == 2) {
                    $action .= _vue_button_link("fa fa-remove","handleCancel($id)","Cancel this Booking");
                }*/
                if ($can_show) {
                    $action .= _show_link(base_url($this->module . '/show/' . $id));
                }
                if ($can_edit) {
                    $action .= _edit_link(base_url($this->module . '/edit/' . $id));
                }
                if ($can_delete) {
                    $remove_url = base_url($this->module . '/remove/' . $id);
                    $action .= _delete_link($remove_url);
                }
                $action_cell = [
                    'class' => 'text-center',
                    'data'  => $action
                ];

                $arr = [];
                $arr[] = custom_date_format($result['date'], "d/m/Y");
                $arr[] = $result['booking_name'];
                $arr[] = $result['email'];
                $arr[] = $result['phone'];
                $arr[] = $status ? $status[0]['title'] : '';
                $arr[] = ($action) ? $action_cell : '';

                $body[] = $arr;
            }
        }

        $heading = [];
        $heading[] = _line('text_date');
        $heading[] = _line('text_name');
        $heading[] = _line('text_email');
        $heading[] = _line('text_phone');
        $heading[] = _line('text_status');
        $heading[] = ($can_edit || $can_delete) ? array('data' => _line('text_action'), 'class' => 'text-center no-sort') : '';

        _vars('table_heading', $heading);
        _vars('table_body', $body);
        $table = _view(DATA_TABLE_PATH);
        $search = false;
        if ($search) {
            _set_js_var('searchUrl', base_url($this->module));
        }
        $filter_dropdown = true;
        if ($filter_dropdown) {

        }

        $page = [
            'singular'     => $this->singular,
            'plural'       => $this->plural,
            'add_url'      => base_url($this->module . '/add'),
            'search'       => $search,
            'vue_add_url'  => '',
            'table'        => $table,
            'edit_form'    => '',
            'total_rows'   => $total_rows,
            'per_page'     => $per_page,
            'paginate_url' => $paginate_url,
        ];
        _vars('page_data', $page);
        _set_additional_component(LIST_XTEMPLATE_PATH, 'outside');
        _set_layout_type('wide');
        _set_page_title($this->plural);
        _set_page_heading($this->plural);
        _set_layout(LIST_VIEW_PATH);
    }

    public function show($id) {

        $obj = $this->_single(['id' => $id],'show');
        _vars('obj',$obj);

        $this->_show($id);
    }
    public function add()
    {
        $this->_add();
    }

    public function edit($id)
    {
        $this->_edit($id);
    }

    public function remove($id)
    {
        if (_can($this->module . '/remove', 'page')) {
            $result = $this->_perform_delete($id);
            if ($result) {
                _set_message(_get_var('mesage'), 'success');
            } else {
                _set_message(_get_var('mesage'), 'error');
            }
        } else {
            _set_message('You do not have enough privilege to delete this ' . $this->singular, 'error');
        }
        $this->set_redirect($this->module);
    }

    public function _single_get()
    {

        $id = _input('id');
        $result = $this->_single(['id' => $id]);

        if ($result) {
            _response_data('obj', $result);
        } else {
            _set_message('The requested details could not be found.', 'warning');
            _response_data('redirect', base_url($this->module));
        }
        return true;
    }

    public function _single($params = [],$type="edit")
    {
        _model('booking_status');
        $id = $params['id'];
        $obj = $this->{$this->model}->single(['id' => $id]);
        if($type == 'show') {
            $obj['current_status'] = $this->booking_status->single(['id' => $obj['status']]);
        }
        $this->_exclude_keys($obj);
        $this->_sql_to_vue($obj);
        return $obj;
    }

    public function _action_put()
    {
        if ($this->_put()) {
            _response_data('redirect', base_url($this->module));
            return true;
        }
        return false;
    }

    public function _action_post()
    {
        if ($this->_post()) {
            _response_data('redirect', base_url($this->module));
            return true;
        } else {
            return false;
        }
    }

    public function _populate_get()
    {
        _model('booking_status');
        $statuses = $this->booking_status->search();

        _response_data('statuses', $statuses);
        return true;
    }

    public function _create_inquiry()
    {
        $formData = _input('formData');

        $insert = [];
        foreach ($formData as $fd) {
            $insert[$fd['name']] = $fd['value'];
        }
        $insert['status'] = 1;
        $insert['added'] = sql_now_datetime();
        $insert['date'] = sql_now_date();

        $this->{$this->model}->insert($insert);
        return true;
    }

    public
    function _confirm_post()
    {
        $id = _input('id');
        $this->setBookingStatus($id, 2);
        _response_data('message', 'Booking confirmed successfully');
        return true;
    }

    public
    function _reject_post()
    {
        $id = _input('id');
        $this->setBookingStatus($id, 3);
        _response_data('message', 'Booking has been rejected.');
        return true;
    }

    public
    function _cancel_post()
    {
        $id = _input('id');
        $this->setBookingStatus($id, 5);
        _response_data('message', 'Booking has been rejected.');
        return true;
    }

    private
    function setBookingStatus($bookingId, $statusId)
    {
        $condition = ['id' => $bookingId];
        $data = [
            'status' => $statusId
        ];
        $this->{$this->model}->update($data, $condition);
        return true;
    }

    protected function _load_files()
    {

        $list_pages = ['index'];
        if (in_array(_get_method(), $list_pages)) {

        }

        if(_get_method() == 'show') {
            $this->layout = 'inquiries_show_view';
        }

        if (_get_method() == 'add' || _get_method() == 'edit') {
            _load_plugin(['moment', 'vue_datepicker']);
            _helper('control');
            $this->layout = 'inquiries_form_view';
            _page_script_override('bookings/inquiries-form');
        }

    }
}
