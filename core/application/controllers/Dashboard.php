<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends MY_Controller {

    public $language = 'dashboard';
    public $module = 'dashboard';
	public function index()
	{
		if(_input('startDate') && _input('endDate')) {
            _set_js_var('startDate',_input('startDate'),'s');
            _set_js_var('endDate',_input('endDate'),'s');
        }
        $sales_data = _db_get_query("SELECT date_format(oo.order_date,'%b') as month_name,COUNT(*) as sales_count,SUM(oo.grand_total + oo.tip - (SELECT IFNULL(SUM(opr.amount),0) FROM ord_payment_refund opr WHERE opr.order_id = oo.id)) AS grand_total FROM ord_order oo WHERE oo.order_status NOT IN ('Draft','Cancelled','Confirmed','Refunded','Deleted') AND year(oo.order_date) = year(CURDATE()) GROUP BY year(oo.order_date), MONTH(oo.order_date);");
        $month_name = [];
        $sales_count = [];
        $grand_total = [];
        if($sales_data){
            $month_name = array_column($sales_data,'month_name');
            $sales_count = array_column($sales_data,'sales_count');
            $grand_total = array_column($sales_data,'grand_total');
        }

        _set_js_var('this_year_months',$month_name,'j');
        _set_js_var('this_year',$sales_count,'j');
        _set_js_var('this_year_earning',$grand_total,'j');
        $sources = _get_module('orders/order_sources','_search',[]);
        $order_sources = get_select_array($sources,'id','title',true,'','All');
        _set_js_var('orderSources',$order_sources,'j');
        $popular_times = _db_get_query("SELECT ROUND(COUNT(*) * 100 / (SELECT COUNT(*) FROM ord_order oo1 WHERE oo1.order_status NOT IN ('Draft','Cancelled','Confirmed','Refunded','Deleted')),2) AS percent, CONCAT(DATE_FORMAT(oo.added,'%l %p'),' - ',DATE_FORMAT(DATE_ADD(oo.added, INTERVAL 1 hour),'%l %p')) AS time_range FROM ord_order oo WHERE oo.order_status NOT IN ('Draft','Cancelled','Confirmed','Refunded','Deleted') GROUP BY HOUR(oo.added)");
        $percent = [];
        $time_range = [];
        if($popular_times){
            $percent = array_column($popular_times,'percent');
            $time_range = array_column($popular_times,'time_range');
        }
        _set_js_var('popular_times',$percent,'j');
        _set_js_var('time_range',$time_range,'j');
        $yearlyData = _db_get_query("SELECT COUNT(*) AS yearCount, SUM(oo.grand_total + oo.tip- (SELECT IFNULL(SUM(opr.amount),0) FROM ord_payment_refund opr WHERE opr.order_id = oo.id)) AS yearEarnings FROM ord_order oo WHERE oo.order_status NOT IN ('Draft','Cancelled','Confirmed','Refunded','Deleted') AND year(oo.order_date) = year(CURDATE()) GROUP BY year(oo.order_date);",true);
        _set_js_var('yearlyData',$yearlyData,'j');

	    _set_layout_type('wide');
	    _set_additional_component('system/dashboard_xtemplate','outside');
		_set_layout('system/dashboard_view');
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
        $last_orders = $this->_orders($sourceId);
        $pieChartOrders = $this->_pieChartOrders($params);
        $pieChartEarnings = $this->_pieChartEarnings($params);

		_response_data('items',$items);
        _response_data('dashboard',$result);
        _response_data('lastOrders',$last_orders);
        _response_data('pieChartOrders',$pieChartOrders);
        _response_data('pieChartEarnings',$pieChartEarnings);
        return true;
    }
	public function _filter_list($params) {
        $start_date = $this->db->escape($params['filter_date_start']);
        $end_date = $this->db->escape($params['filter_date_end']);
        $statuses = ['closed','cancelled','partial refunded'];
        $sql = "SELECT";
        $sql .= " (SELECT SUM(oo.grand_total + oo.tip) FROM ord_order oo WHERE oo.order_status NOT IN ('cancelled','draft','confirmed') AND DATE(oo.order_date) BETWEEN $start_date AND $end_date) as `totalEarnings`,";
        $sql .= " (SELECT IFNULL(SUM(amount),0) FROM ord_payment_refund opr WHERE opr.order_id IN (SELECT oo.id FROM ord_order oo WHERE DATE(oo.order_date) BETWEEN $start_date AND $end_date)) as `refundTotal`";
        foreach($statuses as $status) {
            $sql .= ", (SELECT COUNT(*) FROM ord_order oo WHERE oo.order_status = '$status' AND DATE(oo.order_date) BETWEEN $start_date AND $end_date) as `$status`";
        }
        $sql .= " FROM dual";
        $result = _db_get_query($sql,true);
        $result['partialRefunded'] = $result['partial refunded'];
        return $result;
    }
	public function _items($params) {
        $start_date = $this->db->escape($params['filter_date_start']);
        $end_date = $this->db->escape($params['filter_date_end']);
       $sql ="SELECT SUM(ooi.quantity) AS total_quantity,ooi.title FROM ord_order_item ooi WHERE DATE(ooi.added) BETWEEN $start_date AND $end_date GROUP BY ooi.item_id,ooi.sku_id ORDER BY SUM(ooi.quantity) DESC LIMIT 10;";
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
        $order_sources = _get_module('orders/order_sources','_search',[]);

        $sources = array_column($order_sources,'id');

        $sql = "SELECT";
        $first = true;
        foreach($sources as $source) {
            if(!$first) {
                $sql .= ',';
            }
            $first=false;
            $sql .= "(SELECT COUNT(*) FROM ord_order oo WHERE oo.order_status NOT IN ('cancelled','draft','confirmed','refunded','Deleted') AND oo.source_id = $source AND DATE(oo.order_date) BETWEEN $start_date AND $end_date) as `$source`";
        }
        $sql .= " FROM dual";
        $result = _db_get_query($sql,true);

        return $result;
    }
    public function _pieChartEarnings($params){
        $start_date = $this->db->escape($params['filter_date_start']);
        $end_date = $this->db->escape($params['filter_date_end']);
        $order_sources = _get_module('orders/order_sources','_search',[]);

        $sources = array_column($order_sources,'id');

        $sql = "SELECT";
        $first = true;
        foreach($sources as $source) {
            if(!$first) {
                $sql .= ',';
            }
            $first=false;
            $sql .= "(SELECT IFNULL(SUM(oo.grand_total + oo.tip - (SELECT IFNULL(SUM(opr.amount),0) FROM ord_payment_refund opr WHERE opr.order_id = oo.id)),0) FROM ord_order oo WHERE oo.order_status NOT IN ('cancelled','draft','confirmed','refunded','Deleted') AND oo.source_id = $source AND DATE(oo.order_date) BETWEEN $start_date AND $end_date) as `$source`";
        }
        $sql .= " FROM dual";
        $result = _db_get_query($sql,true);
        return $result;
    }
	/*public function set_default_settings() {
	    _set_setting('name','Status Tour & Travel','company',false);
	    _set_setting('url','http://www.statustours.in/','company',false);
	    _set_setting('logo','assets/img/brand-logo.png','company',false);

	    _set_setting('watermark_enabled',true,'pdf',false);
	    _set_setting('watermark_text','Status Tour & Travel','pdf',false);
	    _set_setting('footer_text','Status Tour & Travel','pdf',false);
	    _set_setting('primary_color','#222222','pdf',false);
	    _set_setting('table_header_bg','#EFEFEF','pdf',false);
	    _set_setting('table_border_color','#DDDDDD','pdf',false);
    }*/
    protected function _load_files() {
        if(_get_method()=='index') {
			_load_plugin(['moment']);
            _enqueue_script('assets/plugins/chart-js/Chart.bundle.min.js');
            _enqueue_script('assets/plugins/vue-chartjs/vue-chartjs.min.js');
            _enqueue_script('assets/plugins/vue-chartjs/chart.piece-label.js');
			_enqueue_style('assets/plugins/vue2-daterange-picker/dist/vue2-daterange-picker.css');
           _enqueue_script('assets/plugins/vue2-daterange-picker/dist/vue2-daterange-picker.umd.min.js');
        }
    }
}
