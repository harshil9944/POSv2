<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Sessions extends MY_Controller {

    public $module = 'reports/sessions';
    public $model = '';
    public $singular = 'POS Session';
    public $plural = 'POS Sessions';
    public $language = 'reports/sales';
    public $edit_form = '';
    public function __construct()
    {
        parent::__construct();
        //_model($this->model);
    }
    public function index() {

        _set_js_var('paginationLimit',_get_setting('pagination_limit',10),'s');
        _set_js_var('sessionsPDFUrl',base_url('reports/sessions/pdf'),'s');
        _set_js_var('enableRefunded',_get_setting('enable_refunded',ALLOW_REFUND),'s');
        _set_js_var('allowGratuity',ALLOW_GRATUITY,'b');
        _set_layout_type('wide');
        _set_page_title($this->plural);
        _set_page_heading($this->plural);
        _set_additional_component('sessions_list_xtemplate','outside');
        _set_layout('sessions_list_view');

    }

    public function _filter_list_get() {

        $filter_start_date = _input('filterStartDate');
        $filter_end_date = _input('filterEndDate');

        $limit = _get_setting('pagination_limit',10);

        $params = [
            'filter_date_start' =>  $filter_start_date,
            'filter_date_end'   =>  $filter_end_date,
            'start'             =>  (_input('currentPage') - 1) * $limit,
            'limit'             =>  $limit,
            'orders'            =>  ['order_by'=>'opening_date','order'=>'DESC']
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

        $result = $this->_filter_list($params);

        _response_data('reportsCount',count((array)$result));
        return true;
    }

    public function _filter_list($params) {

        _model('pos/pos_session','pos_session');
        _model('users/user','user');

        $filter_start_date = (isset($params['filter_date_start']) && $params['filter_date_start'])?$params['filter_date_start']:'2000-01-01';
        $filter_end_date = (isset($params['filter_date_end']) && $params['filter_date_end'])?$params['filter_date_end']:sql_now_date();

        $session_table = POS_SESSION_TABLE;
        $sql = "SELECT ps.*,COUNT(oo.id) AS ordersCount,SUM(oo.grand_total - (SELECT IFNULL(SUM(opr.amount),0) FROM ord_payment_refund opr WHERE opr.order_id = oo.id)) AS transactionsTotal,SUM(oo.tax_total) AS taxTotal,SUM(oo.change) AS changeTotal,SUM(oo.discount) AS discountTotal,SUM(oo.tip) AS tipTotal, (SELECT CONCAT(uu.first_name, ' ', uu.last_name) FROM usr_user uu WHERE ps.opening_user_id = uu.id ) AS openingEmployee,(SELECT CONCAT(uu.first_name, ' ', uu.last_name) FROM usr_user uu WHERE ps.closing_user_id = uu.id ) AS closingEmployee FROM pos_session ps INNER JOIN ord_order oo ON ps.id = oo.session_id WHERE  oo.order_status  NOT IN ('cancelled','refunded','deleted') AND (DATE(ps.opening_date)>='$filter_start_date' AND DATE(ps.opening_date)<='$filter_end_date') GROUP BY ps.id";
        //$sql = "SELECT * FROM $session_table ps WHERE (DATE(ps.opening_date)>='$filter_start_date' AND DATE(ps.opening_date)<='$filter_end_date')";
        if(isset($params['orders'])) {
            $order_by = $params['orders']['order_by'];
            $order = $params['orders']['order'];
            $sql .= " ORDER BY $order_by $order";
        }

        if (isset($params['start']) || isset($params['limit'])) {
            if ($params['start'] < 0) {
                $params['start'] = 0;
            }

            if ($params['limit'] < 1) {
                $params['limit'] = 20;
            }

            $sql .= " LIMIT " . (int)$params['start'] . "," . (int)$params['limit'];
        }

        $sessions = _db_get_query($sql);
        $result = [];
        if($sessions) {
            foreach ($sessions as $session) {
                $session['expectedClosingCash'] = 0;
                $session['openingDate'] =  $session['opening_date'];
                $session['closingDate'] =  $session['closing_date'];
                $session['openingCash'] =  $session['opening_cash'];
                $session['closingCash'] =  $session['closing_cash']??0;
                $session['takeOut'] =  $session['take_out']??0;
                if( $session['status'] == 'Close'){
                    $session['expectedClosingCash'] = $session['takeOut'] + $session['closingCash'];
                }
                $result[] = $session;
            }
        }
        return $result;
    }

    public function _single($params) {
        return _get_module('pos','_close_session_summary',$params);
    }

    public function _single_get() {

        $session_id = _input_get('id');

        $params = ['session_id'=>$session_id,'recalculate_cash'=>false,'enableRefunded'=>ALLOW_REFUND];
        $session = $this->_single($params);
        if($session){
            $session['sessionPdfUrl'] =  base_url("reports/sessions/detailpdf/" . $session['id']);
        }

        _response_data('session',$session);
        return true;
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

        $file_name = strtolower('sessions-'.$start_date.'-'.$end_date.'-'.date('M').date('Y').'.pdf');
        $upload_path = _get_config('pdf_path') . 'reports/sessions/';

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

            $pdf_data = _view('sessions_pdf');

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
    public function detailpdf($id) {
        $this->view = false;
        $params = [
            'session_id'    =>  $id,
            'force'         =>  true
        ];
        $pdf_data = $this->_detailPdf($params);

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

    private function _detailPdf($params) {

        $session_id = $params['session_id'];
        
        $force = $params['force'];

        $watermark_enabled = _get_setting('watermark_enabled',false,'pdf');

        $this->view = false;
        $param = [
            'session_id' =>  $session_id,
            'recalculate_cash'=>false
        ];

        $summary = $this->_single($param);
        $summary['enableRefunded'] = ALLOW_REFUND;
        $summary['allowGratuity'] = ALLOW_GRATUITY;
        $opening_date =custom_date_format($summary['openingDate'],'d-m-y');
        $file_name = strtolower($opening_date.'-summary'.'.pdf');
        $upload_path = _get_config('pdf_path') . 'reports/summary/';

        if(!file_exists($upload_path)) {
            mkdir($upload_path,0777,true);
        }

        if(!file_exists($upload_path.$file_name) || $force==true) {

            $watermark_text = '';

            _vars('summary',$summary);

            $pdf_data = _view('summary_pdf');

            $params = [
                'watermark'     =>  $watermark_text,
                'footer_html'   =>  '<hr/><p style="text-align:center;text-transform:uppercase;">' . CORE_APP_TITLE . '</p>'
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
