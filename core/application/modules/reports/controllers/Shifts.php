<?php
defined( 'BASEPATH' ) OR exit( 'No direct script access allowed' );

class Shifts extends MY_Controller {

    public $module = 'reports/shifts';
    public $model = '';
    public $singular = 'Shift';
    public $plural = 'Shifts Report';
    public $language = 'reports/sales';
    public $edit_form = '';
    public function __construct() {
        parent::__construct();
        //_model($this->model);
    }
    public function index() {

        $params = [];
        $params['exclude'] = true;
        $params['convert'] = true;
        $employees = _get_module( 'employees', '_search', $params );
        if ( $employees ) {
            foreach ( $employees as &$employee ) {
                $employee['name'] = $employee['firstName'] . ' ' . $employee['lastName'];
            }
        }

        _set_js_var( 'employees', $employees, 'j' );

        _set_js_var( 'paginationLimit', _get_setting( 'pagination_limit', 10 ), 's' );
        _set_js_var( 'shiftsPDFUrl', base_url( 'reports/shifts/pdf' ), 's' );
        _set_js_var( 'shiftsCSVUrl', base_url( 'reports/shifts/csv' ), 's' );
        _set_js_var( 'enableRefunded', _get_setting( 'enable_refunded', ALLOW_REFUND ), 's' );
        _set_layout_type( 'wide' );
        _set_page_title( $this->plural );
        _set_page_heading( $this->plural );
        _set_additional_component( 'shifts_list_xtemplate', 'outside' );
        _set_layout( 'shifts_list_view' );

    }

    public function _filter_list_get() {

        $filter_start_date = _input( 'filterStartDate' );
        $filter_end_date = _input( 'filterEndDate' );
        $employee_id = _input( 'employeeId' );

        $limit = _get_setting( 'pagination_limit', 10 );

        $params = [
            'filter_date_start' => $filter_start_date,
            'filter_date_end'   => $filter_end_date,
            'employee_id'       => $employee_id,
            'start'             => ( _input( 'currentPage' ) - 1 ) * $limit,
            'limit'             => $limit,
            'orders'            => ['order_by' => 'start_shift', 'order' => 'DESC'],
        ];

        $result = $this->_filter_list( $params );

        _response_data( 'reports', $result );
        return true;
    }

    public function _filter_list_total_get() {
        $filter_start_date = _input( 'filterStartDate' );
        $filter_end_date = _input( 'filterEndDate' );
        $employee_id = _input( 'employeeId' );

        $params = [
            'filter_date_start' => $filter_start_date,
            'filter_date_end'   => $filter_end_date,
            'employee_id'       => $employee_id,
        ];

        $result = $this->_filter_list( $params );

        _response_data( 'reportsCount', count( (array) $result ) );
        return true;
    }

    public function _filter_list( $params ) {
        $filter_start_date = ( isset( $params['filter_date_start'] ) && $params['filter_date_start'] ) ? $params['filter_date_start'] : '2000-01-01';
        $filter_end_date = ( isset( $params['filter_date_end'] ) && $params['filter_date_end'] ) ? $params['filter_date_end'] : sql_now_date();

        $sql = "SELECT es.*,ee.* , SUM(oo.tip) AS tip,COUNT(oo.id) as totalOrder FROM emp_shift es LEFT JOIN emp_employee ee ON ee.id = es.employee_id LEFT JOIN ord_order oo ON es.session_id = oo.session_id AND oo.employee_id = es.employee_id   WHERE (DATE(es.start_shift)>='$filter_start_date' AND DATE(es.start_shift)<='$filter_end_date') ";
        if ( $params['employee_id'] != '' ) {
            $sql .= " AND es.employee_id = '" . $params['employee_id'] . "'";
        }
        $sql .= " GROUP BY es.start_shift";
        if ( isset( $params['orders'] ) ) {
            $order_by = $params['orders']['order_by'];
            $order = $params['orders']['order'];
            $sql .= " ORDER BY $order_by $order";
        }

        if ( isset( $params['start'] ) || isset( $params['limit'] ) ) {
            if ( $params['start'] < 0 ) {
                $params['start'] = 0;
            }

            if ( $params['limit'] < 1 ) {
                $params['limit'] = 20;
            }

            $sql .= " LIMIT " . (int) $params['start'] . "," . (int) $params['limit'];
        }

        $shifts = _db_get_query( $sql );
        $result = [];
        if ( $shifts ) {
            foreach ( $shifts as &$shift ) {
                $temp = [
                    'id'         => $shift['id'],
                    'empName'    => $shift['first_name'] . " " . $shift['last_name'],
                    'tip'        => $shift['tip'],
                    'startShift' => $shift['start_shift'],
                    'endShift'   => $shift['end_shift'],
                    'totalOrder' => $shift['totalOrder'],
                    'status'     => $shift['end_shift'] ? 'Close' : 'Open',
                    'employeeId' => $shift['employee_id'],
                    'sessionId'  => $shift['session_id'],
                ];

                $result[] = $temp;
            }
        }
        return $result;
    }

    public function _single( $params ) {
        return _get_module( 'pos', '_close_employee_summary', $params );
    }

    public function _single_get() {

        $empId = _input_get( 'empId' );
        $sessionId = _input_get( 'sessionId' );

        $params = ['session_id' => $sessionId, 'enableRefunded' => ALLOW_REFUND, 'employee_id' => $empId];
        $shift = $this->_single( $params );

        _response_data( 'shift', $shift );
        return true;
    }

    public function pdf() {

        $this->view = false;

        $start_date = _input( 'startDate' );
        $end_date = _input( 'endDate' );
        $employee_id = _input( 'employeeId' );

        $params = [
            'start_date'  => $start_date,
            'end_date'    => $end_date,
            'force'       => true,
            'employee_id' => $employee_id,
        ];

        $pdf_data = $this->_pdf( $params );

        $file_name = $pdf_data['file_name'];
        $upload_path = $pdf_data['upload_path'];

        header( 'Content-Type: application/pdf' );
        header( 'Content-Transfer-Encoding: Binary' );
        header( 'Content-disposition: inline; filename="' . $file_name . '"' );

        $fp = fopen( $upload_path . $file_name, "r" );

        ob_clean();
        flush();
        while ( !feof( $fp ) ) {
            $buff = fread( $fp, 1024 );
            print $buff;
        }
        exit;
    }

    private function _pdf( $params ) {

        $start_date = $params['start_date'];
        $end_date = $params['end_date'];
        $force = $params['force'];
        $employee_id = $params['employee_id'];

        $watermark_enabled = _get_setting( 'watermark_enabled', false, 'pdf' );
        //$watermark_text = ($watermark_enabled)?_get_setting('watermark_text',false,'pdf'):false;

        $this->view = false;

        $file_name = strtolower( 'shifts-' . $start_date . '-' . $end_date . '-' . date( 'M' ) . date( 'Y' ) . '.pdf' );
        $upload_path = _get_config( 'pdf_path' ) . 'reports/shifts/';

        if ( !file_exists( $upload_path ) ) {
            mkdir( $upload_path, 0777, true );
        }

        if ( !file_exists( $upload_path . $file_name ) || $force == true ) {

            $param = [
                'filter_date_start' => $start_date,
                'filter_date_end'   => $end_date,
                'employee_id'       => $employee_id,
            ];

            $report = $this->_filter_list( $param );

            //$status = ($so['orderStatus']=='Received')?'Received':false;

            $watermark_text = ''; //($watermark_enabled)?$status:false;

            _vars( 'obj', $report );

            $pdf_data = _view( 'shifts_pdf' );

            $params = [
                'watermark'   => $watermark_text,
                'footer_html' => '<hr/><p style="text-align:center;text-transform:uppercase;">' . CORE_APP_TITLE . '</p>',
            ];

            _generate_pdf( $pdf_data, $upload_path . $file_name, $params );
        }
        return [
            'file_name'   => $file_name,
            'upload_path' => $upload_path,
        ];

    }
    public function csv() {

        $this->view = false;

        $start_date = _input( 'startDate' );
        $end_date = _input( 'endDate' );
        $employee_id = _input( 'employeeId' );

        $params = [
            'start_date'  => $start_date,
            'end_date'    => $end_date,
            'force'       => true,
            'employee_id' => $employee_id,
        ];

        $csv_data = $this->_csv( $params );

        $file_name = $csv_data['file_name'];
        $upload_path = $csv_data['upload_path'];

        header( 'Content-Type: application/vnd.ms-excel' );
        header( 'Content-Transfer-Encoding: Binary' );
        header( 'Content-disposition: inline; filename="' . $file_name . '"' );

        $fp = fopen( $upload_path . $file_name, "r" );

        ob_clean();
        flush();
        while ( !feof( $fp ) ) {
            $buff = fread( $fp, 1024 );
            print $buff;
        }
        exit;
    }

    private function _csv( $params ) {

        $start_date = $params['start_date'];
        $end_date = $params['end_date'];
        $force = $params['force'];
        $employee_id = $params['employee_id'];
        $file_name = strtolower( 'shifts-' . $start_date . '-' . $end_date . '-' . date( 'M' ) . date( 'Y' ) . '.csv' );
        $upload_path = _get_config( 'csv_path' ) . 'reports/shifts/';

        if ( !file_exists( $upload_path ) ) {
            mkdir( $upload_path, 0777, true );
        }

        if ( !file_exists( $upload_path . $file_name ) || $force == true ) {

            $param = [
                'filter_date_start' => $start_date,
                'filter_date_end'   => $end_date,
                'employee_id'       => $employee_id,
            ];

            $reports = $this->_filter_list( $param );
            $body = [];

            if ( $reports ) {
                foreach ( $reports as $report ) {
                    $body[] = [
                        'empName'    => $report['empName'],
                        'totalOrder' => $report['totalOrder'],
                        'startShift' => custom_date_format( $report['startShift'] ),
                        'endShift'   => custom_date_format( $report['endShift'] ),
                        'status'     => $report['status'],
                        'tip'        => $report['tip'],
                    ];
                }
            }

            $header = [
                'Employee',
                'Orders',
                'Shift Start',
                'Shift End',
                'Status',
                'Tip',
            ];
            _generate_csv( $body, $header, $upload_path . $file_name );
        }
        return [
            'file_name'   => $file_name,
            'upload_path' => $upload_path,
        ];

    }

    public function detailpdf( $id ) {
        $this->view = false;
        $params = [
            'shift_id' => $id,
            'force'    => true,
        ];
        $pdf_data = $this->_detailPdf( $params );

        $file_name = $pdf_data['file_name'];
        $upload_path = $pdf_data['upload_path'];

        header( 'Content-Type: application/pdf' );
        header( 'Content-Transfer-Encoding: Binary' );
        header( 'Content-disposition: inline; filename="' . $file_name . '"' );

        $fp = fopen( $upload_path . $file_name, "r" );

        ob_clean();
        flush();
        while ( !feof( $fp ) ) {
            $buff = fread( $fp, 1024 );
            print $buff;
        }
        exit;
    }

    private function _detailPdf( $params ) {

        $shift_id = $params['shift_id'];

        $force = $params['force'];

        $watermark_enabled = _get_setting( 'watermark_enabled', false, 'pdf' );

        $this->view = false;
        $param = [
            'shift_id'         => $shift_id,
            'recalculate_cash' => false,
        ];

        $summary = $this->_single( $param );
        $summary['enableRefunded'] = ALLOW_REFUND;
        $summary['allowGratuity'] = ALLOW_GRATUITY;
        $opening_date = custom_date_format( $summary['openingDate'], 'd-m-y' );
        $file_name = strtolower( $opening_date . '-shifts' . '.pdf' );
        $upload_path = _get_config( 'pdf_path' ) . 'reports/shift/';

        if ( !file_exists( $upload_path ) ) {
            mkdir( $upload_path, 0777, true );
        }

        if ( !file_exists( $upload_path . $file_name ) || $force == true ) {

            $watermark_text = '';

            _vars( 'summary', $summary );

            $pdf_data = _view( 'summary_pdf' );

            $params = [
                'watermark'   => $watermark_text,
                'footer_html' => '<hr/><p style="text-align:center;text-transform:uppercase;">' . CORE_APP_TITLE . '</p>',
            ];

            _generate_pdf( $pdf_data, $upload_path . $file_name, $params );
        }
        return [
            'file_name'   => $file_name,
            'upload_path' => $upload_path,
        ];

    }

    protected function _load_files() {

        _load_plugin( ['moment'] );
        _enqueue_style( 'assets/plugins/vue2-daterange-picker/dist/vue2-daterange-picker.css' );
        _enqueue_script( 'assets/plugins/vue2-daterange-picker/dist/vue2-daterange-picker.umd.min.js' );

    }
}
