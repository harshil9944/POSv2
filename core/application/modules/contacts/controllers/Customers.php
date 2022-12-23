<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
class Customers extends MY_Controller {

    public $module = 'contacts/customers';
    public $model = 'customer';
    public $singular = 'Customer';
    public $plural = 'Customers';
    public $language = 'contacts/customers';
    public $edit_form = '';
    public $form_xtemplate = 'customers_form_xtemplate';
    public function __construct()
    {

        parent::__construct();
        $params = [
            'migration_path' => CONTACT_MIGRATION_PATH,
            'migration_table' => CONTACT_MIGRATION_TABLE
        ];
        $this->init_migration($params);
    }

    // Public methods Start

    public function index()	{
        $table = $this->{$this->model}->table;
        _library('table');
        _set_js_var('exportUrl',base_url('contacts/customers/export'));
        _set_js_var('detailUrl',base_url($this->module . '/details'),'s');
      //  $results = $this->{$this->model}->get_list();
        $filter_dropdown_value = _input('filterDropdown');
        $offset = (_input('offset') && is_int((int)_input('offset')))?(int)_input('offset'):0;
        $searchString = trim(_input('search'));
        $searchFields = [$table.'.display_name',$table.'.phone',$table.'.email'];

        $filters = [];
        $filters['filter'] = ['deleted'=>0];
        if($searchString) {
            $or_likes = [];
            foreach ($searchFields as $field) {
                $or_likes[$field] = $searchString;
            }
            $filters['or_likes'] = $or_likes;
            _set_js_var('searchString',$searchString);
        }
        if($filter_dropdown_value) {
            $filters['filter']['group_id'] = $filter_dropdown_value;
        }
        $filters['orders'] = [['order_by'=>$table.'.added','order'=>'DESC']];
        $filters['offset'] = $offset;
        $filters['limit'] = true;
        $results = $this->_search($filters);

        $total_items = $this->{$this->model}->get_list_count($filters);

        $total_rows = ($total_items)?$total_items['total_rows']:0;
        $per_page = (int)_get_setting('global_limit',50);
        $paginate_url = base_url($this->module);

        $can_edit = _can($this->module . '/edit','page');
        $can_delete = _can($this->module . '/remove','page');
        $groups = [];
        if(ALLOW_CUSTOMER_GROUP) {
            $groups = _db_get_query('SELECT ccg.id,ccg.title FROM con_customer_groups ccg');
        }
        $body = [];
        if($results) {
            foreach ($results as $result) {
                $action = '';
                if($can_edit) {
                    $action .= _edit_link(base_url($this->module . '/edit/' . $result['id']));
                }
                if($can_delete) {
                    $remove_url = base_url($this->module . '/remove/' . $result['id']);
                    $action .= _vue_delete_link("handleRemove('" . $remove_url . "')");
                }
                $action_cell = [
                    'class' =>  'text-center',
                    'data'  =>  $action
                ];
               /*  $receivables_cell = [
                    'class' =>  'text-right',
                    'data'  =>  custom_money_format(0.00,_get_setting('currency_sign','₹'))
                ];
                $payables_cell = [
                    'class' =>  'text-right',
                    'data'  =>  custom_money_format(0.00,_get_setting('currency_sign','₹'))
                ]; */

                $group_title = '';
                if(ALLOW_CUSTOMER_GROUP) {
                    if ($groups) {
                        foreach ($groups as $g) {
                            if ($g['id'] === $result['group_id']) {
                                $group_title = $g['title'];
                            }
                        }
                    }
                }
                $customer_name = _vue_text_link($result['display_name'],'handleViewCustomer('.$result['id'].')','Add Address');
                //$customer_name = $result['display_name'];

                $arr = [];
                $arr[] = $customer_name;
                if(ALLOW_CUSTOMER_GROUP) {
                    $arr[] = $group_title;
                }
                $arr[] = $result['email'];
                $arr[] = $result['phone'];
                $arr[] = custom_date_format($result['added'],"d/m/Y");
                $arr[] = ($action)?$action_cell:'';

                $body[] = $arr;
            }
        }

        $heading = [];
        $heading[] = _line('text_name');
        if(ALLOW_CUSTOMER_GROUP) {
            $heading[] = _line('text_group');
        }
        $heading[] = _line('text_email');
        $heading[] = _line('text_phone');
        $heading[] = _line('text_registered');
        $heading[] = ($can_edit || $can_delete)?array('data'=>_line('text_action'),'class'=>'text-center no-sort'):'';

        _vars('table_heading',$heading);
        _vars('table_body',$body);
        $table = _view(DATA_TABLE_PATH);
        $search = true;
        if($search) {
            _set_js_var('searchUrl',base_url($this->module));
        }
        $filter_dropdown = true;
        if($filter_dropdown) {
            $filter_groups = [];
            if($groups) {
                foreach ($groups as $g) {
                    $filter_groups[] = [
                        'id'    =>  $g['id'],
                        'value' =>  $g['title'],
                    ];
                }
            }
            $default_value = '';
            $filter_dropdown_values = [
                'value'         =>  ($filter_dropdown_value)?$filter_dropdown_value:$default_value,
                'defaultValue'  =>  $default_value,
                'defaultTitle'  =>  'All Groups',
                'values'        =>  $filter_groups
            ];
            _set_js_var('filterDropdown',$filter_dropdown_values,'j');
        }

        $page = [
            'singular'      =>  $this->singular,
            'plural'        =>  $this->plural,
            'add_url'       =>  base_url($this->module.'/add'),
            'search'        =>  $search,
            'vue_add_url'   =>  '',
            'table'         =>  $table,
            'edit_form'     =>  '',
            'total_rows'    =>  $total_rows,
            'per_page'      =>  $per_page,
            'paginate_url'  =>  $paginate_url,
            'filter_dropdown' =>  ALLOW_CUSTOMER_GROUP,
        ];
        _vars('page_data',$page);
        _set_additional_component(LIST_XTEMPLATE_PATH,'outside');
        _set_additional_component('customers_import_export_tag');
        _set_additional_component('customers_xtemplates','outside');
        _set_layout_type('wide');
        _set_page_title($this->plural);
        _set_page_heading($this->plural);
        _set_layout(LIST_VIEW_PATH);

    }

    public function add() {
        $this->_add();
    }

    public function edit($id) {
        $this->_edit($id);
    }

    public function remove($id) {
        if(_can($this->module . '/remove','page')){
            $result = $this->_delete($id);

            if($result) {
                _set_message(_get_var('mesage'),'success');
            }else{
                _set_message(_get_var('mesage'),'error');
            }
        }else{
            _set_message('You do not have enough privilege to delete this ' . $this->singular,'error');
        }
        $this->set_redirect($this->module);
    }

    //Public methods End

    public function _populate_get() {


        $params = [];
        $params['include_select'] = false;
        $countries = _get_module('core/countries','_get_select_data',$params);
        $new_customerId = _get_ref('cus',3,7);

        $params_state = [];
        $params_state['include_select'] = true;
        $params_state['country_id'] = DEFAULT_COUNTRY_ID;
        $states = _get_module('core/states','_get_select_data',$params_state);

        $params_cGroups = [];
        $params_cGroups['include_select'] = false;
        $customerGroups  = _get_module('contacts/customer_groups','_get_select_data',$params_cGroups);

        _response_data('groups',$customerGroups);
        _response_data('states',$states);
        _response_data('countries',$countries);
        _response_data('newCustomerId',$new_customerId);
        return true;

    }

    public function _action_put() {
        _model('customer_address','address');
        $obj = _input('obj');
        $obj['addresses'][] = $obj['address'];
        unset($obj['address']);
        $this->_prep_obj($obj);
        $customer = $obj['customer_table'];
        $customer['status'] = 1;
        $customer['added'] = sql_now_datetime();

        if($this->{$this->model}->insert($customer)) {
            $customer_id = $this->{$this->model}->insert_id();
            $addresses = $obj['customer_address_table'];
            if($addresses){
                foreach ($addresses as $a){
                    if($a['title'] !=='' ){
                        $a['customer_id'] = $customer_id;
                        $a['added'] = sql_now_datetime();
                        $this->address->insert($a);
                       /*  $address_id = $this->address->insert_id();
                        $default_address_id['default_address_id'] = $address_id;
                        $this->{$this->model}->update($default_address_id,['id'=>$customer_id]); */
                    }
                }
            }
            _update_ref('cus');
            $customer = $this->_single(['id'=>$customer_id]);
            _response_data('customer',$customer);
            _response_data('redirect',base_url($this->module));
        }else{
            _response_data('message','Something wrong. Please try again.');
        }
        return true;
    }

    public function _action_post() {
        _model('customer_address','address');
        $obj = _input('obj');
        $this->_prep_obj($obj);

        unset($obj['address']);
        $customer = $obj['customer_table'];
        $customer_id = $customer['id'];
        unset($customer['id']);
        $filter=[
            'id'    =>  $customer_id
        ];

        if($this->{$this->model}->update($customer,$filter)) {
            $addresses = $obj['customer_address_table'];

            $ignore_list = [];
            if($addresses){
                foreach ($addresses as $a){
                    if($a['title'] !=='' ){
                        if($a['id'] !==''){
                            $this->address->update($a,['id'=>$a['id']]);
                            $ignore_list[] =$a['id'];
                        }else {
                            $a['customer_id'] = $customer_id;
                            $a['added'] = sql_now_datetime();
                            $this->address->insert($a);
                            $address_id = $this->address->insert_id();
                            $ignore_list[] =$address_id;
                        }

                       /*  $address_id = $this->address->insert_id();
                        $default_address_id['default_address_id'] = $address_id;
                        $this->{$this->model}->update($default_address_id,['id'=>$customer_id]); */
                    }
                }
                $this->_clear_payments($customer_id,$ignore_list);
            }
            _response_data('customer',$this->_single(['id'=>$customer_id]));

            _response_data('redirect',base_url($this->module));
        }else{
            _response_data('message','Something wrong. Please try again.');
        }
        return true;
    }
    private function _clear_payments($customer_id,$ignore) {

        if($ignore) {
            $this->db->where_not_in('id',$ignore);
        }
        $this->address->delete(['customer_id'=>$customer_id]);

    }
    public function _action_delete() {
        return true;
    }

    private function _prep_obj(&$obj) {
        $customer_keys = $this->{$this->model}->keys;
        $customer_address_keys = $this->address->keys;

        $obj['customer_table'] = [];
        foreach ($customer_keys as $old => $new) {
            change_array_key($old,$new,$obj);
            if(isset($obj[$new])) {
                $obj['customer_table'][$new] = $obj[$new];
                unset($obj[$new]);
            }
        }
        $obj['customer_address_table'] = [];
        if(isset($obj['addresses'])) {
            foreach ($obj['addresses'] as $single) {
                foreach ($customer_address_keys as $vue => $sql) {
                    change_array_key($vue, $sql, $single);
                }
                $obj['customer_address_table'][] = $single;
            }
            unset($obj['addresses']);
        }

         if(CUSTOMER_USERNAME_FIELD!='email') {
            if ($obj['customer_table']['email'] == '') {
                $obj['customer_table']['email'] = null;
            }
        }
        if(CUSTOMER_USERNAME_FIELD!='mobile') {
            if ($obj['customer_table']['mobile'] == '') {
                $obj['customer_table']['mobile'] = null;
            }
        }
        unset($obj['group']);
    }

    private function _delete($id) {

        $ignore_list = [];
        $ignore_list[] = DEFAULT_POS_CUSTOMER;

        if(!in_array($id,$ignore_list)) {

            $filter = ['id' => $id];

            $result = $this->{$this->model}->single($filter);

            if($result) {
                $update = ['deleted'=>1];
                $affected_rows = $this->{$this->model}->update($update,$filter);

                if ($affected_rows) {
                    _vars('message',$this->singular . ' has been deleted successfully');
                    return true;
                } else {
                    _vars('message','Something went wrong. Please try again');
                    return false;
                }

            }else{
                _vars('message','The requested ' . $this->singular . ' was not found');
                return false;
            }
        }else{
            _vars('message','You cannot delete a protected ' . $this->singular);
            return false;
        }
    }

    public function _single_get() {

        $id = _input('id');
        $result = $this->_single(['id'=>$id]);

        if($result) {
            _response_data('obj',$result);
        }else{
            _set_message('The requested details could not be found.','warning');
            _response_data('redirect',base_url($this->module));
        }
        return true;
    }

    public function _single($params=[]) {
        _model('contacts/customer_group','customer_group');
        _model('contacts/customer_address','address');

        $group_keys = $this->customer_group->keys;

        $id = $params['id'];
        $customer_keys = $this->{$this->model}->keys;
        $address_keys = $this->address->keys;

        $filter = ['id'=>$id];
        $result = $this->{$this->model}->single($filter);
        $customer_exclude_fields = $this->{$this->model}->exclude_keys;
        $address_exclude_fields = $this->address->exclude_keys;

        if($result) {
            $customer_id = $result['id'];
            $result['addresses'] = [];
            $addresses = $this->address->search(['customer_id'=>$customer_id]);
            if(!empty($addresses)){
                $this->_sql_to_vue($addresses,$address_keys,true);
                $result['addresses'] = $addresses;
            }
            $group_id = $result['group_id'];
            $group = $this->customer_group->single(['id'=>$group_id,'status'=>1]);
            $this->_sql_to_vue($group,$group_keys);
            $result['group'] = $group;
            foreach ($customer_keys as $new => $old) {
                change_array_key($old, $new, $result);
            }

            return $result;
        }
    }

    public function _address_get(){
        _model('contacts/customer_address','address');
        $address_keys = $this->address->keys;
        $address_id = _input('id');
        if($address_id){
            $address = $this->address->single(['id'=>$address_id]);
            $this->_sql_to_vue($address,$address_keys);
        }
        _response_data('address',$address);
        return true;
    }
    public function _address_put(){
        _model('contacts/customer_address','address');
        $address_keys = $this->address->keys;
        $address = _input('obj');
        if($address){
            $this->_vue_to_sql($address,$address_keys);
            $address['added'] = sql_now_datetime();
            $customer_id = $address['customer_id'];
            $this->address->insert($address);
        }
        _response_data('customer',$this->_single(['id'=>$customer_id]));
        return true;
    }
    public function _address_post(){
        _model('contacts/customer_address','address');
        $address_keys = $this->address->keys;
        $address = _input('obj');
        if($address){
            $this->_vue_to_sql($address,$address_keys);
            $address_id = $address['id'];
            unset($address['id']);
            $customer_id = $address['customer_id'];
            $this->address->update($address,['id'=>$address_id]);

        }
        _response_data('customer',$this->_single(['id'=>$customer_id]));
        return true;
    }
    public function _address_delete(){
        _model('contacts/customer_address','address');
        $address_id = _input('addressId');
        $customer_id = _input('customerId');
        $affected_rows = $this->address->delete(['id'=>$address_id]);
        if ($affected_rows) {
            _response_data('customer',$this->_single(['id'=>$customer_id]));
            return true;
        }
    }
    public function _select_data_get() {

        $params = [];
        $params['include_select'] = false;
        $result = $this->_get_select_data($params);

        _response_data('customers',$result);
        return true;

    }

    public function _get_select_data($params=[]) {
        $include_select = isset($params['include_select'])?$params['include_select']:true;

        $filter = ['deleted'=>0,'status'=>1];
        $this->{$this->model}->order_by('display_name');
        $result = $this->{$this->model}->search($filter);
        if($result) {
            $result = get_select_array($result,'id','display_name',$include_select,'0','Select '.$this->singular);
            return $result;
        }else{
            return [];
        }

    }

    public function _get_data($param=[]) {
        $filter = ['id'=>$param['customer_id']];
        $convert_vue = (isset($param['convert_vue']) && $param['convert_vue'])?true:false;
        $result = $this->{$this->model}->single($filter);
        if($result) {
            if($convert_vue) {
                $contact_keys = $this->{$this->model}->keys;
                foreach ($contact_keys as $old => $new) {
                    change_array_key($new,$old,$result);
                }
            }
            return $result;
        }else{
            return [];
        }
    }

    public function _get_address_data($param=[]) {
        _model('vendor_address','address');
        $address_id = $param['address_id'];

        $filter = ['id'=>$address_id];
        $convert_vue = (isset($param['convert_vue']) && $param['convert_vue'])?true:false;
        $result = $this->address->single($filter);
        if($result) {
            if($convert_vue) {

            }
            return $result;
        }else{
            return [];
        }
    }

    public function _query_get() {

        _model($this->model);
        $query = _input('query');
        $compact_details = (_input('compact'))?true:false;
        if($query) {
            $this->db->like('first_name', $query, 'both');
            $this->db->or_like('last_name', $query, 'both');
            $this->db->or_like('display_name', $query, 'both');
            $this->db->or_like('email', $query, 'both');
            $this->db->or_like('phone', $query, 'both');
        }
        $customers = $this->{$this->model}->search();

        $result = [];
        if($customers) {
            foreach ($customers as $single) {
                $value = $single['display_name'];
                if(!$compact_details) {
                    $value .= ($single['email']) ? ' - ' . $single['email'] : '';
                    $value .= ($single['phone']) ? ' - ' . $single['phone'] : '';
                }
                $result[] = [
                    'id'    =>  $single['id'],
                    'value' =>  $value
                ];
            }
        }
        _response_data('customers',$result);
        return true;

    }

    public function _duplicate_email_get() {
        $query = _input('email');
        $ignore_id = _input('id');
        if($query) {
            $filter = ['email'=>$query];
            if($ignore_id){
                $filter['id!='] = $ignore_id;
            }
            $customers = $this->{$this->model}->search($filter);
            if($customers) {
                _response_data('result',true);
            }else{
                _response_data('result',false);
            }
        }
        return true;
    }

    public function _duplicate_phone_get() {
        $query = _input('phone');
        $ignore_id = _input('id');
        if($query) {
            $filter = ['phone'=>$query];
            if($ignore_id){
                $filter['id!='] = $ignore_id;
            }
            $customers = $this->{$this->model}->search($filter);
            if($customers) {
                _response_data('result',true);
            }else{
                _response_data('result',false);
            }
        }
        return true;
    }
    public function export() {
        $this->view = false;
        $spreadsheet = new Spreadsheet();
        $result = $this-> _get_customers_export();
        $customers = $result['customers'];
        $customers_fields = ['first_name','last_name','display_name','email','phone','register_date'];
        $customer_sheet = $spreadsheet->getActiveSheet()->setTitle('Customers');
        $customer_sheet->fromArray($customers_fields, NULL, 'A1');
        $row=2;
        foreach ($customers as $c) {
            $char='A';
            foreach($customers_fields as $f) {
                $data = $c[$f];
                $customer_sheet->setCellValue($char.$row, $data);
                $char = chr(ord($char)+1);
            }
            $row++;
        }
        $writer = new Xlsx($spreadsheet);
        $file_name = 'customers.xlsx';
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-disposition: attachment; filename="'.$file_name.'"');
        ob_end_clean();
        $writer->save('php://output');
        //$writer->save(_get_config('global_upload_path') . 'export/items.xlsx');
        exit;
    }
    private function _get_customers_export(){
        $filters['filter'] = [
            'deleted'  =>  0
        ];
        $filters['limit'] = 99999;
        $filters['orders'] = [['order_by'=>'added','order'=>'DESC']];
        $customers = $this->_search($filters);
        $body = [];
        if($customers){
            foreach ($customers as $c){

                $body['customers'][] =[
                    'first_name' => $c['first_name'],
                    'last_name'=>$c['last_name'],
                    'display_name'         =>  $c['display_name'],
                    'email'=>  $c['email']??'',
                    'phone'  =>$c['phone']??'',
                    'register_date'=> custom_date_format($c['added'],"d/m/y h:i A")
                ];
            }
        }
        return $body;
    }

    public function details($id = null){
        if(!$id) {
            $this->view = false;
            redirect($this->module);
        }
        $customer = $this->_single(['id'=>$id]);
        if(!$customer){
            $this->view = false;
            redirect($this->module);
        }
        $customer_id = $customer['id'];
        $customer['items'] = $this->_items($customer_id);
        $filter = $this->_filter_list($customer_id);
        $interval = date_diff(date_create($filter['firstOrder']),date_create(sql_now_date()));
        $filter['days'] = (int)$interval->format("%r%a");
        
        $orders = $this->_orders_get($customer_id);
        $customer['backUrl'] = base_url($this->module);
        $customer['pdfUrl'] = base_url("contacts/customers/pdf/$id");
        $sales_data = _db_get_query("SELECT date_format(oo.order_date,'%b') as month_name,COUNT(*) as sales_count ,SUM(oo.grand_total + oo.tip - (SELECT IFNULL(SUM(opr.amount),0) FROM ord_payment_refund opr WHERE opr.order_id = oo.id)) AS grand_total FROM ord_order oo WHERE oo.order_status NOT IN ('Draft','Cancelled','Confirmed','Refunded','Deleted') AND oo.customer_id = $customer_id AND oo.order_date> now() - INTERVAL 12 month GROUP BY MONTH(oo.order_date) ORDER BY oo.order_date ASC;");
        $month_name = [];
        $sales_count = [];
        $grand_total = [];
        if($sales_data){
            $month_name = array_column($sales_data,'month_name');
            $sales_count = array_column($sales_data,'sales_count');
            $grand_total = array_column($sales_data,'grand_total');
        }

        _set_js_var('this_year_months',$month_name,'j');
        _set_js_var('this_year_sales',$sales_count,'j');
        _set_js_var('this_year_earning',$grand_total,'j');

        $yearlyData = _db_get_query("SELECT COUNT(*) AS yearCount, SUM(oo.grand_total + oo.tip- (SELECT IFNULL(SUM(opr.amount),0) FROM ord_payment_refund opr WHERE opr.order_id = oo.id)) AS yearEarnings FROM ord_order oo WHERE oo.order_status NOT IN ('Draft','Cancelled','Confirmed','Refunded','Deleted') AND oo.customer_id = $customer_id AND year(oo.order_date) = year(CURDATE()) GROUP BY year(oo.order_date);",true);
        _set_js_var('yearlyData',$yearlyData,'j');

        $popular_times = _db_get_query("SELECT ROUND(COUNT(*) * 100 / (SELECT COUNT(*) FROM ord_order oo1 WHERE oo1.customer_id = $customer_id AND oo1.order_status NOT IN ('Draft','Cancelled','Confirmed','Refunded','Deleted')),2) AS percent, CONCAT(DATE_FORMAT(oo.added,'%l %p'),' - ',DATE_FORMAT(DATE_ADD(oo.added, INTERVAL 1 hour),'%l %p')) AS time_range FROM ord_order oo WHERE oo.order_status NOT IN ('Draft','Cancelled','Confirmed','Refunded','Deleted') AND oo.customer_id = $customer_id GROUP BY HOUR(oo.added)");
        $percent = [];
        $time_range = [];
        if($popular_times){
            $percent = array_column($popular_times,'percent');
            $time_range = array_column($popular_times,'time_range');
        }
        
        _set_js_var('popular_times',$percent,'j');
        _set_js_var('time_range',$time_range,'j');
        _helper('control');
        _set_js_var('orders',$orders,'j');
        _set_js_var('customer',$customer,'j');
        _set_js_var('defaultCityId',DEFAULT_CITY_ID,'n');
        _set_js_var('defaultCountryId',DEFAULT_COUNTRY_ID,'n');
        _set_js_var('defaultStateId',DEFAULT_STATE_ID,'n');
        _set_js_var('filter',$filter,'j');
         $edit_url = base_url($this->module.'/edit/'.$id);
        _set_js_var('editUrl',$edit_url,'s');
        _enqueue_script('assets/plugins/chart-js/Chart.bundle.min.js');
        _enqueue_script('assets/plugins/vue-chartjs/vue-chartjs.min.js');
        _enqueue_script('assets/plugins/vue-chartjs/chart.piece-label.js');
        _set_page_heading('Customer Details');
        _set_layout_type('wide');
        _set_layout('customer_details_view');
        _set_additional_component('customer_details_xtemplate','outside');
        _load_plugin(['vue_multiselect','moment','datepicker']);
        return true;
    }

    public function _items($customer_id) {
        $result =_db_get_query("SELECT ooi.title AS title , SUM(ooi.quantity) AS total_quantity FROM ord_order oo LEFT JOIN ord_order_item ooi ON ooi.order_id = oo.id WHERE oo.customer_id = $customer_id GROUP BY ooi.item_id ORDER BY SUM(ooi.quantity) DESC;");
        return $result;
    }
    public function _filter_list($customer_id) {
        
        $statuses = ['closed','cancelled','partial_refunded'];
        $sql = "SELECT";
        $sql .= " (SELECT SUM(oo.grand_total + oo.tip) FROM ord_order oo WHERE oo.order_status NOT IN ('cancelled','draft','confirmed','refunded','deleted') AND oo.customer_id = $customer_id) as `totalEarnings`,";
        $sql .= " (SELECT IFNULL(SUM(amount),0) FROM ord_payment_refund opr WHERE opr.order_id IN (SELECT oo.id FROM ord_order oo WHERE oo.customer_id = $customer_id)) as `refundTotal`";
        $sql .= " , (SELECT IFNULL(AVG(oo.grand_total),0) FROM ord_order oo WHERE oo.order_status NOT IN ('Draft','Cancelled','Confirmed','Refunded','Deleted') AND oo.customer_id = $customer_id) as `avgOrder`";
        $sql .= " , (SELECT IFNULL(SUM(oo.discount),0) FROM ord_order oo WHERE oo.order_status NOT IN ('Draft','Cancelled','Confirmed','Refunded','Deleted') AND oo.customer_id = $customer_id) as `discount`";
        $sql .= " , (SELECT IFNULL(SUM(oo.tip),0) FROM ord_order oo WHERE oo.order_status NOT IN ('Draft','Cancelled','Confirmed','Refunded','Deleted') AND oo.customer_id = $customer_id) as `tip`";
        $sql .= " , (SELECT IFNULL(AVG(op.amount),0) FROM ord_payment op LEFT JOIN ord_order oo ON oo.id = op.order_id WHERE oo.order_status NOT IN ('Draft','Cancelled','Confirmed','Refunded','Deleted') AND oo.customer_id = $customer_id) as `avgPayOrder`";
        $sql .= " , (SELECT COUNT(*) FROM ord_order oo WHERE oo.type='p' AND  oo.order_status NOT IN ('Draft','Cancelled','Confirmed','Refunded','Deleted') AND oo.customer_id = $customer_id) as `pickUpOrder`";
        $sql .= " , (SELECT COUNT(*) FROM ord_order oo WHERE oo.type='dine' AND  oo.order_status NOT IN ('Draft','Cancelled','Confirmed','Refunded','Deleted') AND oo.customer_id = $customer_id) as `dineOrder`";
        $sql .= " , (SELECT MIN(oo.order_date) FROM ord_order oo WHERE oo.order_status NOT IN ('Draft','Cancelled','Confirmed','Refunded','Deleted') AND oo.customer_id = $customer_id) as `firstOrder`";
        $sql .= " , (SELECT MAX(oo.order_date) FROM ord_order oo WHERE oo.order_status NOT IN ('Draft','Cancelled','Confirmed','Refunded','Deleted') AND oo.customer_id = $customer_id) as `lastOrder`";
        foreach($statuses as $status) {
            $sql .= ", (SELECT COUNT(*) FROM ord_order oo WHERE oo.order_status = '$status' AND oo.customer_id = $customer_id) as `$status`";
        }
        $sql .= " FROM dual";
        $result = _db_get_query($sql,true);
        $result['partialRefunded'] = $result['partial_refunded'];
        return $result;
    }

    public function _orders_get($customer_id){
        _model('orders/order_item','order_item');
        $orders = [];
        $params = [];
        $params['filter'] = [
            'customer_id'=>  $customer_id,
        ];
        $params['exclude'] = true;
        $params['convert'] = true;
        $params['orders'] = [
            ['order_by' => 'id', 'order' => 'DESC']
        ];
        $orders = _get_module('orders', '_search', $params);

        $sources = _get_module('orders', '_order_sources', ['convert'=>true,'exclude'=>true]);

        if($orders) {
            $temp = [];
            foreach ($orders as $order) {
                $order_id = $order['id'];

                $items = $this->order_item->search(['order_id'=>$order_id]);

                $item_titles = [];
                foreach ($items as $item) {
                    $item_titles['title'][] = $item['title'];
                }

                $order['items'][] = $item_titles;
                $source_id = $order['sourceId'];
                $source = array_values(array_filter($sources,function($single) use ($source_id) {
                    return $single['id'] == $source_id;
                }));
                $source = ($source)?$source[0]:false;

                $order_type = $order['type'];
                if($order_type == 'p') {
                    $order['type'] = 'Pickup';
                    if($source){
                        $order['type'] .= ' (' . $source['title'] . ')';
                    }
                }elseif($order_type == 'dine') {
                    $order['type'] = 'Dine-in';
                }
                $temp[] = $order;
            }
            $orders = $temp;
        }
        return $orders;
        
    }

    public function _install() {
        return true;
    }

    public function _uninstall() {
        return true;
    }

    public function pdf($id) {
           
        $this->view = false;

        $params = [
            'id'    =>  $id,
            'force' =>  true
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

        $id = $params['id'];
        $force = $params['force'];


        $this->view = false;

        $file_name = strtolower('so-'.$id.'-'.date('M').date('Y').'.pdf');
        $upload_path = _get_config('pdf_path') . 'customer/';

        if(!file_exists($upload_path)) {
            mkdir($upload_path,0777,true);
        }

        if(!file_exists($upload_path.$file_name) || $force==true) {

            $customer = $this->_single($params);
            dd($customer);

            $watermark_text = '';

            _vars('obj',$customer);

            $pdf_data = _view('customer_pdf');

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

        $list_pages = ['index'];
        if(in_array(_get_method(),$list_pages)) {
            _helper('control');
            _load_plugin(['dt','moment']);
            _set_js_var('customerCustomFields',CUSTOMER_CUSTOM_FIELDS,'j');
            $edit_url = base_url($this->module.'/edit/');
            _set_js_var('editUrl',$edit_url,'s');
        }

        if(_get_method()=='add' || _get_method()=='edit') {
            _helper('control');
            _vars('customer_user_field',_get_setting('customer_username_field',CUSTOMER_USERNAME_FIELD));
            $this->layout = 'customers_form_view';
            _set_js_var('customerCustomFields',CUSTOMER_CUSTOM_FIELDS,'j');
            _set_js_var('allowCustomerGroup',ALLOW_CUSTOMER_GROUP,'b');
            _page_script_override('contacts/customers-form');
            _set_js_var('allowCustomerNotes',ALLOW_CUSTOMER_NOTES,'b');
        }

    }
}
