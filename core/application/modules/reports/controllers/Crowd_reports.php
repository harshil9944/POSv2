<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Crowd_reports extends MY_Controller {

    public $language = '';
    public $module = 'reports/crowd_reports';
    public $title = 'Crowd Reports';
    public function __construct()
    {
        parent::__construct();
    }
	public function index()
	{
        $filter_start_date = _input( 'filterStartDate' );
        $filter_end_date = _input( 'filterEndDate' );
        $week_day_id = _input( 'weekDayId' );
        if ( !$filter_start_date && !$filter_end_date ) {
          $filter_start_date = date("Y/m/d", strtotime("-1 month")) ;
          $filter_end_date = date('Y/m/d');
        }
      
        $start_date = $this->db->escape($filter_start_date);
        $end_date = $this->db->escape($filter_end_date);
        //Popular Times
        $popular_times = _db_get_query("SELECT ROUND(COUNT(*) * 100 / (SELECT COUNT(*) FROM ord_order oo1 WHERE oo1.order_status NOT IN ('Draft','Cancelled','Confirmed','Refunded','Deleted') AND DATE(oo1.added) BETWEEN $start_date AND $end_date),2) AS percent, CONCAT(DATE_FORMAT(oo.added,'%l %p'),' - ',DATE_FORMAT(DATE_ADD(oo.added, INTERVAL 1 hour),'%l %p')) AS time_range FROM ord_order oo WHERE oo.order_status NOT IN ('Draft','Cancelled','Confirmed','Refunded','Deleted')  AND DATE(oo.added) BETWEEN $start_date AND $end_date GROUP BY HOUR(oo.added)");
        $percent = [];
          $time_range = [];
          if($popular_times){
              $percent = array_column($popular_times,'percent');
              $time_range = array_column($popular_times,'time_range');
          }
          _set_js_var('popular_times',$percent,'j');
          _set_js_var('time_range',$time_range,'j');
          
        $day_of_week_number =  $week_day_id? (int)$week_day_id: 2;
        $date_filter = "DATE(oo.added) BETWEEN $start_date AND $end_date";

        if($filter_start_date === $filter_end_date){
            $day_of_week_number = date('w', strtotime($filter_end_date)) + 1;
            $date_filter = 'oo.added  >= '.$end_date.' - INTERVAL 1 year';
        }
          // Day of week
          //$day_of_week = _db_get_query("SELECT ROUND(COUNT(*) * 100 / (SELECT COUNT(*) FROM ord_order oo1 WHERE oo1.order_status NOT IN ('Draft','Cancelled','Confirmed','Refunded','Deleted') AND  DATE(oo1.added) BETWEEN $start_date AND $end_date AND DAYOFWEEK(oo1.added) = $day_of_week_number),2) AS week_day_orders, CONCAT(DATE_FORMAT(oo.added,'%l %p'),' - ',DATE_FORMAT(DATE_ADD(oo.added, INTERVAL 1 hour),'%l %p')) AS week_day FROM ord_order oo WHERE oo.order_status NOT IN ('Draft','Cancelled','Confirmed','Refunded','Deleted')  AND DAYOFWEEK(oo.added) = $day_of_week_number AND DATE(oo.added) BETWEEN $start_date AND $end_date  GROUP BY HOUR(oo.added)");
          $day_of_week = _db_get_query("SELECT COUNT(*) AS week_day_orders, CONCAT(DATE_FORMAT(oo.added,'%l %p'),' - ',DATE_FORMAT(DATE_ADD(oo.added, INTERVAL 1 hour),'%l %p')) AS week_day FROM ord_order oo WHERE oo.order_status NOT IN ('Draft','Cancelled','Confirmed','Refunded','Deleted')  AND DAYOFWEEK(oo.added) = $day_of_week_number AND DATE(oo.added) BETWEEN $start_date AND $end_date  GROUP BY HOUR(oo.added)");
          $percent = [];
            $week_day = [];
            $week_day_orders = [];
            if($day_of_week){
                $week_day_orders = array_column($day_of_week,'week_day_orders');
                $week_day = array_column($day_of_week,'week_day');
            }
            _set_js_var('week_day_orders',$week_day_orders,'j');
            _set_js_var('week_day',$week_day,'j');
            _set_js_var('week_day_id',$day_of_week_number,'n');

        // Order Source
        $sources = _get_module('orders/order_sources','_search',[]);
        $order_sources = get_select_array($sources,'id','title',true,'','All');
        _set_js_var('orderSources',$order_sources,'j');

        $methods = _get_module( 'core/payment_methods', '_search', ['filter' => []] );
        $payment_methods = get_select_array($methods,'id','title',true,'','All');
        _set_js_var('paymentMethods',$payment_methods,'j');


        $params = [
            'filter_date_start' =>  $filter_start_date,
            'filter_date_end'   =>  $filter_end_date,
            'day_of_week_number'=>$day_of_week_number,
            'order_sources'=>$sources,
            'payment_methods'=>$methods
        ];

        //Pie chart data
        $pieChartPayments = $this->_pieChartPayments($params);
        $pieChartPaymentOrders = $this->_pieChartPaymentOrders($params);
        $pieChartOrders = $this->_pieChartOrders($params);
        $pieChartEarnings = $this->_pieChartEarnings($params);

        $items = $this->_items($params);
        $customers = $this->_customers($params);
        _set_js_var('pieChartOrders',$pieChartOrders,'j');
        _set_js_var('pieChartEarnings',$pieChartEarnings,'j');
        _set_js_var('pieChartPayments',$pieChartPayments,'j');
        _set_js_var('pieChartPaymentOrders',$pieChartPaymentOrders,'j');
        _set_js_var('items',$items,'j');
        _set_js_var('customers',$customers,'j');
        

        _set_js_var('startDate',$filter_start_date,'s');
        _set_js_var('endDate',$filter_end_date,'s');
        _set_js_var('crowdReportsUrl',base_url('reports/crowd_reports'),'s');
        _set_js_var('dbWeekDays',DB_WEEKDAYS,'j');
	    _set_layout_type('wide');
        _set_page_heading($this->title);
        _set_page_title($this->title);
	    _set_additional_component('crowd_reports_xtemplate','outside');
		_set_layout('crowd_reports_view');
	}
	public function _filter_list_get() {
        $filter_start_date = _input('filterStartDate');
        $filter_end_date = _input('filterEndDate');
        $sourceId = _input('sourceId');
        $params = [
            'filter_date_start' =>  $filter_start_date,
            'filter_date_end'   =>  $filter_end_date,
        ];

        $result = $this->_filter_list($params);
		$items = $this->_items($params);
        $mostVisited = $this->_customers($params);
        $last_orders = $this->_orders($sourceId);
        $pieChartOrders = $this->_pieChartOrders($params);
        $pieChartEarnings = $this->_pieChartEarnings($params);
        

		_response_data('items',$items);
        _response_data('mostVisitedCustomers',$mostVisited);
        _response_data('dashboard',$result);
        _response_data('lastOrders',$last_orders);
        _response_data('pieChartOrders',$pieChartOrders);
        _response_data('pieChartEarnings',$pieChartEarnings);
        return true;
    }
	public function _filter_list($params) {
        $start_date = $this->db->escape($params['filter_date_start']);
        $end_date = $this->db->escape($params['filter_date_end']);
        $statuses = ['closed','cancelled','partial_refunded'];
        $sql = "SELECT";
        $sql .= " (SELECT SUM(oo.grand_total + oo.tip) FROM ord_order oo WHERE oo.order_status NOT IN ('cancelled','draft','confirmed') AND DATE(oo.order_date) BETWEEN $start_date AND $end_date) as `totalEarnings`,";
        $sql .= " (SELECT IFNULL(SUM(amount),0) FROM ord_payment_refund opr WHERE opr.order_id IN (SELECT oo.id FROM ord_order oo WHERE DATE(oo.order_date) BETWEEN $start_date AND $end_date)) as `refundTotal`";
        $sql .= " , (SELECT IFNULL(AVG(oo.grand_total),0) FROM ord_order oo WHERE oo.order_status NOT IN ('Draft','Cancelled','Confirmed','Refunded','Deleted') AND DATE(oo.order_date) BETWEEN $start_date AND $end_date) as `avgOrder`";
        $sql .= " , (SELECT IFNULL(AVG(op.amount),0) FROM ord_payment op LEFT JOIN ord_order oo ON oo.id = op.order_id WHERE oo.order_status NOT IN ('Draft','Cancelled','Confirmed','Refunded','Deleted') AND DATE(oo.order_date) BETWEEN $start_date AND $end_date) as `avgPayOrder`";
        $sql .= " , (SELECT COUNT(*) FROM  con_customer cc  WHERE DATE(cc.added) BETWEEN $start_date AND $end_date) as `totalCustomers`";
        $sql .= " , (SELECT COUNT(*) FROM ord_order oo WHERE oo.type='p' AND  oo.order_status NOT IN ('Draft','Cancelled','Confirmed','Refunded','Deleted') AND DATE(oo.order_date) BETWEEN $start_date AND $end_date) as `pickUpOrder`";
        $sql .= " , (SELECT COUNT(*) FROM ord_order oo WHERE oo.type='dine' AND  oo.order_status NOT IN ('Draft','Cancelled','Confirmed','Refunded','Deleted') AND DATE(oo.order_date) BETWEEN $start_date AND $end_date) as `dineOrder`";
        foreach($statuses as $status) {
            $sql .= ", (SELECT COUNT(*) FROM ord_order oo WHERE oo.order_status = '$status' AND DATE(oo.order_date) BETWEEN $start_date AND $end_date) as `$status`";
        }
        $sql .= " FROM dual";
        $result = _db_get_query($sql,true);
        $result['partialRefunded'] = $result['partial_refunded'];
        return $result;
    }
	public function _items($params) {
        $start_date = $this->db->escape($params['filter_date_start']);
        $end_date = $this->db->escape($params['filter_date_end']);
        $day_of_week_number = $this->db->escape($params['day_of_week_number']);
        $sql ="SELECT SUM(ooi.quantity) AS total_quantity,ooi.title FROM ord_order_item ooi  LEFT JOIN ord_order oo ON oo.id = ooi.order_id WHERE oo.order_status NOT IN ('cancelled','draft','confirmed','refunded','Deleted') AND DAYOFWEEK(ooi.added) = $day_of_week_number AND DATE(ooi.added) BETWEEN $start_date AND $end_date GROUP BY ooi.item_id ORDER BY SUM(ooi.quantity) DESC LIMIT 10;";
        $result = _db_get_query($sql);
        return $result;
    }
    public function _customers($params) {
        $start_date = $this->db->escape($params['filter_date_start']);
        $end_date = $this->db->escape($params['filter_date_end']);
        $day_of_week_number = $this->db->escape($params['day_of_week_number']);
        $avoid_customer_id =AVOID_DASHBOARD_CUSTOMER_ID;
        $sql ="SELECT COUNT(oo.customer_id) AS totalVisited,cc.display_name FROM ord_order oo LEFT JOIN con_customer cc ON cc.id = oo.customer_id WHERE oo.order_status NOT IN ('Draft','Cancelled','Confirmed','Refunded','Deleted') AND DAYOFWEEK(oo.added) = $day_of_week_number AND  DATE(oo.order_date) BETWEEN $start_date AND $end_date AND cc.id NOT IN($avoid_customer_id) GROUP BY oo.customer_id ORDER BY COUNT(oo.customer_id) DESC LIMIT 10;";
        $result = _db_get_query($sql);
        return $result;
    }
    public function _orders($sourceId){
        $sql = "SELECT oo.billing_name,sos.title,oo.order_status,oo.grand_total FROM ord_order oo JOIN sys_order_source sos ON oo.source_id=sos.id WHERE oo.order_status NOT IN ('Deleted')";
        if($sourceId!='') {
            $sql .= " AND oo.source_id = $sourceId";
        }
        $sql .= " ORDER BY oo.order_date DESC LIMIT 10;";
        $result = _db_get_query($sql);
        return $result;
    }
    public function _orderBySource_get(){
        $sourceId = _input('sourceId');
        $last_orders =$this->_orders($sourceId);
        _response_data('orderBySource',$last_orders);
        return true;
    }
    public function _pieChartOrders($params){
        $start_date = $this->db->escape($params['filter_date_start']);
        $end_date = $this->db->escape($params['filter_date_end']);
        $day_of_week_number = $this->db->escape($params['day_of_week_number']);
        $order_sources = $params['order_sources'];
        $sources = array_column($order_sources,'id');

        $sql = "SELECT";
        $first = true;
        foreach($sources as $source) {
            if(!$first) {
                $sql .= ',';
            }
            $first=false;
            $sql .= "(SELECT COUNT(*) FROM ord_order oo WHERE oo.order_status NOT IN ('cancelled','draft','confirmed','refunded','Deleted') AND oo.source_id = $source  AND DAYOFWEEK(oo.added) = $day_of_week_number AND DATE(oo.order_date) BETWEEN $start_date AND $end_date) as `$source`";
        }
        $sql .= " FROM dual";
        $result = _db_get_query($sql,true);

        return $result;
    }
    public function _pieChartPayments($params){
        $start_date = $this->db->escape($params['filter_date_start']);
        $end_date = $this->db->escape($params['filter_date_end']);
        $day_of_week_number = $this->db->escape($params['day_of_week_number']);
        $db_methods = $params['payment_methods'];
        $methods = array_column($db_methods,'id');

        $sql = "SELECT";
        $first = true;
        foreach($methods as $source) {
            if(!$first) {
                $sql .= ',';
            }
            $first=false;
            $sql .= "(SELECT IFNULL(SUM(op.amount),0) FROM ord_payment op LEFT JOIN ord_order oo ON oo.id = op.order_id LEFT JOIN sys_payment_method spm ON spm.id = op.payment_method_id WHERE oo.order_status NOT IN ('Draft','Cancelled','Confirmed','Refunded','Deleted') AND op.payment_method_id = $source  AND DAYOFWEEK(oo.added) = $day_of_week_number AND DATE(oo.order_date) BETWEEN $start_date AND $end_date) as `$source`";
        }
        $sql .= " FROM dual";
        $result = _db_get_query($sql,true);

        return $result;
    }
    public function _pieChartPaymentOrders($params){
        $start_date = $this->db->escape($params['filter_date_start']);
        $end_date = $this->db->escape($params['filter_date_end']);
        $day_of_week_number = $this->db->escape($params['day_of_week_number']);
        $db_methods = $params['payment_methods'];
        $methods = array_column($db_methods,'id');

        $sql = "SELECT";
        $first = true;
        foreach($methods as $source) {
            if(!$first) {
                $sql .= ',';
            }
            $first=false;
            $sql .= "(SELECT IFNULL(COUNT(oo.id),0) FROM ord_payment op LEFT JOIN ord_order oo ON oo.id = op.order_id LEFT JOIN sys_payment_method spm ON spm.id = op.payment_method_id WHERE oo.order_status NOT IN ('Draft','Cancelled','Confirmed','Refunded','Deleted') AND op.payment_method_id = $source  AND DAYOFWEEK(oo.added) = $day_of_week_number AND DATE(oo.order_date) BETWEEN $start_date AND $end_date) as `$source`";
        }
        $sql .= " FROM dual";
        $result = _db_get_query($sql,true);

        return $result;
    }
    public function _pieChartEarnings($params){
        $start_date = $this->db->escape($params['filter_date_start']);
        $end_date = $this->db->escape($params['filter_date_end']);
        $day_of_week_number = $this->db->escape($params['day_of_week_number']);
        $order_sources = $params['order_sources'];

        $sources = array_column($order_sources,'id');

        $sql = "SELECT";
        $first = true;
        foreach($sources as $source) {
            if(!$first) {
                $sql .= ',';
            }
            $first=false;
            $sql .= "(SELECT IFNULL(SUM(oo.grand_total + oo.tip - (SELECT IFNULL(SUM(opr.amount),0) FROM ord_payment_refund opr WHERE opr.order_id = oo.id)),0) FROM ord_order oo WHERE oo.order_status NOT IN ('cancelled','draft','confirmed','refunded','Deleted') AND oo.source_id = $source AND DAYOFWEEK(oo.added) = $day_of_week_number AND DATE(oo.order_date) BETWEEN $start_date AND $end_date) as `$source`";
        }
        $sql .= " FROM dual";
        $result = _db_get_query($sql,true);
        return $result;
    }
	
    protected function _load_files() {
        if(_get_method()=='index') {
			_load_plugin(['moment']);
            _enqueue_script('assets/plugins/chart-js/Chart.bundle.min.js');
            _enqueue_script('assets/plugins/vue-chartjs/vue-chartjs.min.js');
            _enqueue_script('assets/plugins/vue-chartjs/chart.piece-label.js');
			_enqueue_style('assets/plugins/vue2-daterange-picker/dist/vue2-daterange-picker.css');
           _enqueue_script('assets/plugins/vue2-daterange-picker/dist/vue2-daterange-picker.umd.min.js');


           _enqueue_cdn_script('https://cdnjs.cloudflare.com/ajax/libs/html2canvas/0.5.0-beta4/html2canvas.min.js','header');
           _enqueue_cdn_script('https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.3.2/jspdf.debug.js','header');
        }
    }
}
