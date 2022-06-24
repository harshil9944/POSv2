<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Hours_worked extends MY_Controller {

    public $module = 'reports/hours_worked';
    public $model = '';
    public $singular = 'Shift';
    public $plural = 'Hours Worked Report';
    public $language = 'reports/sales';
    public $edit_form = '';
    public function __construct()
    {
        parent::__construct();
        //_model($this->model);
    }
    public function index() {

        

        _set_js_var('paginationLimit',_get_setting('pagination_limit',10),'s');
        _set_js_var('PDFUrl',base_url('reports/hours_worked/pdf'),'s');
        _set_js_var('CSVUrl',base_url('reports/hours_worked/csv'),'s');
        _set_js_var('enableRefunded',_get_setting('enable_refunded',ALLOW_REFUND),'s');
        _set_js_var('allowGratuity',ALLOW_GRATUITY,'b');
        _set_layout_type('wide');
        _set_page_title($this->plural);
        _set_page_heading($this->plural);
        _set_additional_component('hours_worked_list_xtemplate','outside');
        _set_layout('hours_worked_list_view');

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
            'orders'            =>  ['order_by'=>'es.employee_id','order'=>'DESC']
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


        $filter_start_date = (isset($params['filter_date_start']) && $params['filter_date_start'])?$params['filter_date_start']:'2000-01-01';
        $filter_end_date = (isset($params['filter_date_end']) && $params['filter_date_end'])?$params['filter_date_end']:sql_now_date();

        $sql = "SELECT es.employee_id,SUM(ROUND(time_to_sec((TIMEDIFF(es.end_shift, es.start_shift))) / 60)) AS total FROM emp_shift es WHERE es.end_shift IS NOT NULL AND DATE(es.start_shift)>='$filter_start_date' AND DATE(es.start_shift)<='$filter_end_date'  GROUP BY es.employee_id ";
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
        $hours = _db_get_query($sql);
        if($hours){
            _model('employees/employee');
            $employees =$this->employee->search();
            foreach ($hours as &$hour) {
                $empId = $hour['employee_id'];
                $employee = array_values(array_filter($employees, function ($single) use ($empId) {
                    return $single['id'] === $empId;
                }));
                $hour['name'] = $employee[0]['first_name']." ".$employee[0]['last_name'];
                $hour['time'] = $this->_get_hours_two_digits($hour['total']);
            }

        }

        return $hours;
    }

    public function _get_hours($minutes) {
        $hours = floor($minutes / 60);
        $minutes = $minutes % 60;
        return $hours . ':' . $minutes;
    }
    public function _get_hours_two_digits($minutes) {
        $hours = floor($minutes / 60);
        $minutes = $minutes % 60;
        return sprintf('%02d', $hours) . ':' . sprintf('%02d', $minutes);
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

        $file_name = strtolower('hours-worked-'.$start_date.'-'.$end_date.'-'.date('M').date('Y').'.pdf');
        $upload_path = _get_config('pdf_path') . 'reports/hours_worked/';

        if(!file_exists($upload_path)) {
            mkdir($upload_path,0777,true);
        }

        if(!file_exists($upload_path.$file_name) || $force==true) {

            $param = [
                'filter_date_start' =>  $start_date,
                'filter_date_end'   =>  $end_date
            ];

            $report = $this->_filter_list($param);


            $watermark_text = '';//($watermark_enabled)?$status:false;

            _vars('obj',$report);

            $pdf_data = _view('hours_worked_pdf');

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
        $file_name = strtolower('hours-'.$start_date.'-'.$end_date.'-'.date('M').date('Y').'.csv');
        $upload_path = _get_config('csv_path') . 'reports/hours_worked/';

        if(!file_exists($upload_path)) {
            mkdir($upload_path,0777,true);
        }

        if(!file_exists($upload_path.$file_name)|| $force==true) {

            $param = [
                'filter_date_start' =>  $start_date,
                'filter_date_end'   =>  $end_date
            ];

            $reports = $this->_filter_list($param);
          //  dd($reports);
            $body=[];

            if($reports) {
                foreach ($reports as $report) {
                    $body[] = [
                        'name'		=>	$report['name'],
                        'time'		=>	$report['time'],
                    ];
                }
            }

            $header = [
                'Employee',
                'Shift Time',
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
