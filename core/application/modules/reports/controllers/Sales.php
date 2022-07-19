<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Sales extends MY_Controller {

    public $module = 'reports/sales';
    public $model = '';
    public $singular = 'Sale';
    public $plural = 'Sales Report';
    public $language = 'reports/sales';
    public $edit_form = '';
    public function __construct()
    {
        parent::__construct();
        //_model($this->model);
    }
    public function index() {

        _set_js_var('paginationLimit',DEFAULT_PAGINATION_LIMIT,'s');
        _set_js_var('ordersReportUrl',base_url('reports/orders'),'s');
        _set_js_var('salesReportPDFUrl',base_url('reports/sales/pdf'),'s');
        _set_js_var('salesReportCSVUrl',base_url('reports/sales/csv'),'s');

        _set_layout_type('wide');
        _set_page_title($this->plural);
        _set_page_heading($this->plural);
        _set_additional_component('sales_list_xtemplate','outside');
        _set_layout('sales_list_view');

    }

    public function _filter_list_get() {

        $filter_start_date = _input('filterStartDate');
        $filter_end_date = _input('filterEndDate');

       // $limit = _get_setting('pagination_limit',10);
       $limit = _input('limit');
       if(!$limit){
        $limit = DEFAULT_PAGINATION_LIMIT;
       }

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


        _response_data('reportsCount',count((array)$result));
        return true;
    }
    public function _filter_list($params) {

        $filter_start_date = (isset($params['filter_date_start']) && $params['filter_date_start'])?$params['filter_date_start']:'2000-01-01';
        $filter_end_date = (isset($params['filter_date_end']) && $params['filter_date_end'])?$params['filter_date_end']:sql_now_date();

        $order_table = ORDER_TABLE;
        $order_item_table = ORDER_ITEM_TABLE;

        //$sql = "SELECT oo.order_date AS orderDate, COUNT(oo.id) totalOrders, SUM(oo.grand_total) AS totalAmount, SUM((SELECT SUM(op.quantity) FROM `$order_item_table` op WHERE op.order_id = oo.id GROUP BY op.order_id)) AS totalItems, SUM(oo.tax_total) AS totalTax,SUM(oo.sub_total) AS subTotal,SUM(oo.discount) AS discount FROM $order_table oo WHERE (DATE(oo.order_date)>='$filter_start_date' AND DATE(oo.order_date)<='$filter_end_date') GROUP BY CAST(oo.order_date AS date)";
        $sql = "SELECT oo.order_date AS orderDate, COUNT(oo.id) totalOrders, SUM(oo.grand_total - (SELECT IFNULL(SUM(opr.amount),0) FROM ord_payment_refund opr WHERE opr.order_id = oo.id)) AS totalAmount, SUM(oo.tax_total) AS totalTax,SUM(oo.sub_total) AS subTotal,SUM(oo.discount) AS discount,SUM(oo.tip) AS tip FROM $order_table oo WHERE (DATE(oo.order_date)>='$filter_start_date' AND DATE(oo.order_date)<='$filter_end_date') AND oo.order_status  NOT IN ('cancelled','refunded','deleted') GROUP BY CAST(oo.order_date AS date)";

        if (isset($params['start']) || isset($params['limit'])) {
            if ($params['start'] < 0) {
                $params['start'] = 0;
            }

            if ($params['limit'] < 1) {
                $params['limit'] = 20;
            }

            $sql .= " LIMIT " . (int)$params['start'] . "," . (int)$params['limit'];
        }
//dump_exit($sql);
        $result = _db_get_query($sql);

        return $result;
    }

    public function _filter_list_total($params) {

        $filter_start_date = (isset($params['filter_date_start']) && $params['filter_date_start'])?$params['filter_date_start']:'2000-01-01';
        $filter_end_date = (isset($params['filter_date_end']) && $params['filter_date_end'])?$params['filter_date_end']:sql_now_date();

        $order_table = ORDER_TABLE;

        $sql = "SELECT COUNT(oo.id) totalOrders FROM $order_table oo WHERE (DATE(oo.order_date)>='$filter_start_date' AND DATE(oo.order_date)<='$filter_end_date') AND oo.order_status  NOT IN ('cancelled','refunded','deleted') GROUP BY CAST(oo.order_date AS date)";

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
    /* public function _filter_list($params) {

        $filter_start_date = (isset($params['filter_date_start']) && $params['filter_date_start'])?$params['filter_date_start']:'2000-01-01';
        $filter_end_date = (isset($params['filter_date_end']) && $params['filter_date_end'])?$params['filter_date_end']:sql_now_date();

        $order_table = ORDER_TABLE;
        $order_item_table = ORDER_ITEM_TABLE;

        $sql = "SELECT oo.order_date AS orderDate, COUNT(oo.id) totalOrders, SUM(oo.grand_total) AS totalAmount, SUM((SELECT SUM(op.quantity) FROM `$order_item_table` op WHERE op.order_id = oo.id GROUP BY op.order_id)) AS totalItems, SUM(oo.tax_total) AS totalTax,SUM(oo.sub_total) AS subTotal,SUM(oo.discount) AS discount FROM $order_table oo WHERE (DATE(oo.order_date)>='$filter_start_date' AND DATE(oo.order_date)<='$filter_end_date') GROUP BY CAST(oo.order_date AS date)";

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
    } */

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

        $file_name = strtolower('sales-'.$start_date.'-'.$end_date.'-'.date('M').date('Y').'.pdf');
        $upload_path = _get_config('pdf_path') . 'reports/sales/';

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

            $pdf_data = _view('sales_pdf');

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
    public function csv() {

        $this->view = false;

        $start_date = _input('startDate');
        $end_date = _input('endDate');

        $params = [
            'start_date'    =>  $start_date,
            'end_date'      =>  $end_date,
            'force'         =>  true
        ];

        $csv_data = $this->_csv($params);

        $file_name = $csv_data['file_name'];
        $upload_path = $csv_data['upload_path'];

        header('Content-Type: application/vnd.ms-excel');
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

    private function _csv($params) {

        $start_date = $params['start_date'];
        $end_date = $params['end_date'];
        $force = $params['force'];
        $file_name = strtolower('sales-'.$start_date.'-'.$end_date.'-'.date('M').date('Y').'.csv');
        $upload_path = _get_config('csv_path') . 'reports/sales/';

        if(!file_exists($upload_path)) {
            mkdir($upload_path,0777,true);
        }

        if(!file_exists($upload_path.$file_name)|| $force==true) {

            $param = [
                'filter_date_start' =>  $start_date,
                'filter_date_end'   =>  $end_date
            ];

            $reports = $this->_filter_list($param);
            //dd($reports);
            $body=[];

            if($reports) {
                foreach ($reports as $report) {
                    $body[] = [
                        'orderDate'      =>	custom_date_format($report['orderDate'],'d/m/Y'),
                        'totalOrders'	    =>	$report['totalOrders'],
                        'subTotal'	=>	$report['subTotal'],
                        'tip'	=>	$report['tip'],
                        'discount'	=>	$report['discount'],
                        'totalTax'	=>	$report['totalTax'],
                        'totalAmount'	=>	$report['totalAmount'],
                    ];
                }
            }

            $header = [
                'Date',
                'Orders',
                'Sub Total',
                'Tip',
                'Discount',
                'Tax',
                'Amount',
            ];
            _generate_csv($body,$header,$upload_path . $file_name);
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
