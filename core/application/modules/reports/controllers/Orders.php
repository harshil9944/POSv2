<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Orders extends MY_Controller {

    public $module = 'reports/orders';
    public $model = '';
    public $singular = 'Order';
    public $plural = 'Orders Report';
    public $language = 'reports/orders';
    public $edit_form = '';
    public function __construct()
    {
        parent::__construct();
        //_model($this->model);
    }
    public function index() {

        if(_input('startDate') && _input('endDate')) {
            _set_js_var('startDate',_input('startDate'),'s');
            _set_js_var('endDate',_input('endDate'),'s');
        }
        _set_js_var('allowGratuity',ALLOW_GRATUITY,'b');
        _set_js_var('ordersReportPDFUrl',base_url('reports/orders/pdf'),'s');
        _set_js_var('paginationLimit',_get_setting('pagination_limit',10),'s');

        _set_layout_type('wide');
        _set_page_title($this->plural);
        _set_page_heading($this->plural);
        _set_additional_component('orders_list_xtemplate','outside');
        _set_layout('orders_list_view');

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

        _response_data('reports',$result);
        return true;
    }

    public function _filter_list_total_get() {
        $filter_start_date = _input('filterStartDate');
        $filter_end_date = _input('filterEndDate');

        $params = [
            'filter_date_start' =>  $filter_start_date,
            'filter_date_end'   =>  $filter_end_date
        ];

        $result = $this->_filter_list_total($params);

        _response_data('reportsCount',$result);
        return true;
    }

    public function _filter_list($params) {

        _model('orders/order','order');

        $order_table = ORDER_TABLE;

        $sql = "SELECT oo.id,oo.order_date,oo.type,oo.billing_name,oo.sub_total,oo.discount,oo.tax_total,(oo.grand_total - (SELECT IFNULL(SUM(opr.amount),0) FROM ord_payment_refund opr WHERE opr.order_id = oo.id)) AS grand_total,oo.order_status FROM $order_table oo";

        if (!empty($params['filter_date_start'])) {
            $sql .= " WHERE DATE(oo.order_date) >= " . $this->db->escape($params['filter_date_start']) . "";
        }

        if (!empty($params['filter_date_end'])) {
            $sql .= " AND DATE(oo.order_date) <= " . $this->db->escape($params['filter_date_end']) . "";
        }

        $sql .= " AND oo.order_status  NOT IN ('cancelled','refunded','deleted') ORDER BY oo.order_date DESC";

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
        if($result) {
            $this->_exclude_keys($result, $this->order->exclude_keys, true);
            $this->_sql_to_vue($result, $this->order->keys, true);
        }

        return $result;
    }
    public function _filter_list_total($params) {

        $filter_start_date = (isset($params['filter_date_start']) && $params['filter_date_start'])?$params['filter_date_start']:'2000-01-01';
        $filter_end_date = (isset($params['filter_date_end']) && $params['filter_date_end'])?$params['filter_date_end']:sql_now_date();

        $order_table = ORDER_TABLE;

        $sql = "SELECT COUNT(*) AS totalOrders FROM $order_table oo ";
        if (!empty($params['filter_date_start'])) {
            $sql .= " WHERE DATE(oo.order_date) >= " . $this->db->escape($params['filter_date_start']) . "";
        }

        if (!empty($params['filter_date_end'])) {
            $sql .= " AND DATE(oo.order_date) <= " . $this->db->escape($params['filter_date_end']) . "";
        }
        $sql .= " AND oo.order_status  NOT IN ('cancelled','refunded','deleted')";
        if (isset($params['start']) || isset($params['limit'])) {
            if ($params['start'] < 0) {
                $params['start'] = 0;
            }

            if ($params['limit'] < 1) {
                $params['limit'] = 20;
            }

            $sql .= " LIMIT " . (int)$params['start'] . "," . (int)$params['limit'];
        }
        $result = _db_get_query($sql,true);
        return $result['totalOrders'];
    }

    public function pdf() {

        $this->view = false;

        $start_date = _input('startDate');
        $end_date = _input('endDate');

        $params = [
            'start_date'    =>  $start_date,
            'end_date'      =>  $end_date,
            'force'         =>  true
        ];

        $pdf_data = $this->_pdf($params);

        $file_name = $pdf_data['file_name'];
        $upload_path = $pdf_data['upload_path'];

        header('Content-Type: application/pdf');
        header('Content-Transfer-Encoding: Binary');
        header('Content-disposition: inline; filename="'.$file_name.'"');

        $fp = fopen($upload_path.$file_name, "r");

        ob_clean();
        flush();
        while (!feof($fp)) {
            $buff = fread($fp, 1024);
            print $buff;
        }
        exit;
    }

    private function _pdf($params) {

        $start_date = $params['start_date'];
        $end_date = $params['end_date'];
        $force = $params['force'];

        $watermark_enabled = _get_setting('watermark_enabled',false,'pdf');
        //$watermark_text = ($watermark_enabled)?_get_setting('watermark_text',false,'pdf'):false;

        $this->view = false;

        $file_name = strtolower('orders-'.$start_date.'-'.$end_date.'-'.date('M').date('Y').'.pdf');
        $upload_path = _get_config('pdf_path') . 'reports/orders/';

        if(!file_exists($upload_path)) {
            mkdir($upload_path,0777,true);
        }

        if(!file_exists($upload_path.$file_name) || $force==true) {

            $param = [
                'filter_date_start' =>  $start_date,
                'filter_date_end'   =>  $end_date
            ];

            $report = $this->_filter_list($param);
            //$status = ($so['orderStatus']=='Received')?'Received':false;

            $watermark_text = '';//($watermark_enabled)?$status:false;

            _vars('obj',$report);

            $pdf_data = _view('orders_pdf');

            $params = [
                'watermark'     =>  $watermark_text,
                'footer_html'   =>  '<hr/><p style="text-align:center;text-transform:uppercase;">'.CORE_APP_TITLE.'</p>'
            ];

            _generate_pdf($pdf_data,$upload_path.$file_name,$params);
        }
        return [
            'file_name'     =>  $file_name,
            'upload_path'   =>  $upload_path
        ];

    }

    protected function _load_files() {

        _load_plugin(['moment']);
        _enqueue_style('assets/plugins/vue2-daterange-picker/dist/vue2-daterange-picker.css');
        _enqueue_script('assets/plugins/vue2-daterange-picker/dist/vue2-daterange-picker.umd.min.js');

    }
}
