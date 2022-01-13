<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Items extends MY_Controller {

    public $module = 'reports/items';
    public $model = '';
    public $singular = 'Item';
    public $plural = 'Items Report';
    public $language = 'reports/items';
    public $edit_form = '';
    public function __construct()
    {
        parent::__construct();
        //_model($this->model);
    }
    public function index() {

        _set_js_var('paginationLimit',_get_setting('pagination_limit',10),'s');

        _set_layout_type('wide');
        _set_page_title($this->plural);
        _set_page_heading($this->plural);
        _set_additional_component('items_list_xtemplate','outside');
        _set_layout('items_list_view');

    }

    public function _filter_list_get() {

        $filter_start_date = _input('filterStartDate');
        $filter_end_date = _input('filterEndDate');

        $limit = _get_setting('pagination_limit',10);

        $params = [
            'filter_date_start' =>  $filter_start_date,
            'filter_date_end'   =>  $filter_end_date,
            'start'             =>  (_input('currentPage') - 1) * $limit,
            'limit'             =>  $limit
        ];

        $result = $this->_filter_list($params);

        _response_data('reports',($result)?$result:[]);
        return true;
    }

    public function _filter_list_total_get() {
        $filter_start_date = _input('filterStartDate');
        $filter_end_date = _input('filterEndDate');

        $params = [
            'filter_date_start' =>  $filter_start_date,
            'filter_date_end'   =>  $filter_end_date
        ];

        $result = $this->_filter_list($params);

        _response_data('reportsCount',($result)?count($result):0);
        return true;
    }

    public function _filter_list($params) {

        $order_table = ORDER_TABLE;
        $order_item_table = ORDER_ITEM_TABLE;

        $sql = "SELECT op.title, SUM(op.quantity) AS quantity, SUM(op.rate * (op.quantity)) AS total FROM $order_item_table  op LEFT JOIN `$order_table` o ON (op.order_id = o.id)";

        if (!empty($params['filter_date_start'])) {
            $sql .= " WHERE DATE(o.order_date) >= " . $this->db->escape($params['filter_date_start']) . "";
        }

        if (!empty($params['filter_date_end'])) {
            $sql .= " AND DATE(o.order_date) <= " . $this->db->escape($params['filter_date_end']) . "";
        }
        $sql .= " AND o.order_status  NOT IN ('cancelled','refunded','deleted') ";
        $sql .= " GROUP BY op.item_id,op.sku_id ORDER BY total DESC";

        if (isset($params['start']) || isset($params['limit'])) {
            if ($params['start'] < 0) {
                $params['start'] = 0;
            }

            if ($params['limit'] < 1) {
                $params['limit'] = 20;
            }

            $sql .= " LIMIT " . (int)$params['start'] . "," . (int)$params['limit'];
        }

        $result = _db_get_query($sql);
        return $result;
    }

    protected function _load_files() {

        _load_plugin(['moment']);
        _enqueue_style('assets/plugins/vue2-daterange-picker/dist/vue2-daterange-picker.css');
        _enqueue_script('assets/plugins/vue2-daterange-picker/dist/vue2-daterange-picker.umd.min.js');

    }
}
