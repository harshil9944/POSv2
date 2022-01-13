<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Payments extends MY_Controller {

    public $module = 'orders/payments';
    public $model = 'payment';
    public $singular = 'Payment';
    public $plural = 'Payments';
    public $language = 'orders/payments';
    public $edit_form = '';
    public function __construct()
    {
        parent::__construct();
        _model($this->model);
    }
    public function index()	{

        _library('table');
        _model('order');

        $results = $this->{$this->model}->get_list();

        $body = [];
        if($results) {
            foreach ($results as $result) {

                $invoice = $this->order->single(['id'=>$result['id']]);

                $grand_total = 0;
                if($invoice) {
                    $grand_total = $invoice['grand_total'];
                }

                $amount_cell = [
                    'class' =>  'text-right',
                    'data'  =>  custom_money_format($grand_total,_get_setting('currency_sign',''))
                ];

                //$order_no = _vue_text_link($result['order_no'],'handleViewOrder('.$result['id'].')','View Order');
                $order_no = $result['order_no'];

                $customer = _get_module('contacts/customers','_single',['id'=>$result['customer_id']]);
                $method_filter = ['id'=>$result['payment_method_id']];
                $mode = _get_module('core/payment_methods','_find',['filter'=>$method_filter]);

                $body[] = [
                    custom_date_format($result['payment_date'],'d/m/Y'),
                    $order_no,
                    $result['reference_no'],
                    ($customer)?$customer['displayName']:'',
                    ($mode)?$mode['title']:'',
                    $amount_cell
                ];
            }
        }

        $heading = [
            'Date',
            'Payment#',
            'Invoice#',
            'Customer Name',
            'Mode',
            array('data'=>'Amount','class'=>'text-right')
        ];

        _vars('table_heading',$heading);
        _vars('table_body',$body);
        $table = _view(DATA_TABLE_PATH);

        $page = [
            'singular'      =>  $this->singular,
            'plural'        =>  $this->plural,
            'add_url'       =>  '',//base_url($this->module.'/add'),
            'vue_add_url'   =>  '',
            'table'         =>  $table,
            'edit_form'     =>  ''
        ];
        _vars('page_data',$page);
        _set_layout_type('wide');
        _set_page_heading($this->plural);
        _set_additional_component(LIST_XTEMPLATE_PATH,'outside');
        _set_layout(LIST_VIEW_PATH);

    }

    public function add($invoice_id='') {

        if($invoice_id) {
            _model('invoice');
            $invoice = $this->invoice->single(['id'=>$invoice_id]);
            if(!$invoice) {
                $this->_redirect(base_url('orders/payments'),'refresh');
            }
        }else{
            $this->_redirect(base_url('orders/payments'),'refresh');
        }

        _helper('control');
        _set_js_var('back_url',base_url($this->module),'s');
        _set_js_var('mode','add','s');
        _set_js_var('invoiceId',$invoice_id,'s');
        _set_page_heading('New ' . $this->singular);
        _set_page_title('New ' . $this->singular);
        _set_layout('payments_form_view');
    }

    public function edit($id) {

        _helper('control');
        _set_js_var('back_url',base_url($this->module),'s');
        _set_js_var('mode','edit','s');
        _set_js_var('id',$id,'s');
        _set_page_heading('Edit ' . $this->singular);
        _set_layout_type('wide');
        _set_layout('salesorders_form_view');

    }

    public function _populate_get() {

        $invoice_id = _input('invoiceId');

        $methods = _get_module('core/payment_methods','_get_select_data',[]);
        _response_data('paymentMethods',$methods);

        $payments = $this->_get_invoice_payments(['invoice_id'=>$invoice_id]);
        if($payments) {
            $temp = [];
            foreach ($payments as $payment) {
                $this->_exclude_keys($payment);
                $this->_sql_to_vue($payment);
                $temp[] = $payment;
            }
            $payments = $temp;
        }
        _response_data('payments',($payments)?$payments:[]);

        $order_number = _get_ref('pay');
        _response_data('orderNo',$order_number);
        return true;

    }

    public function _action_put() {

        _model('invoice_item');

        $obj = _input('obj');
        $this->_prep_obj($obj);

        $payment = $obj['payment_table'];
        unset($payment['id']);
        $payment['added'] = sql_now_datetime();

        if($this->{$this->model}->insert($payment)) {
            $payment_id = $this->{$this->model}->insert_id();

            _update_ref('pay');

            _response_data('redirect',base_url($this->module));
            _response_data('payment_id',$payment_id);
        }else{
            _response_data('message','Something wrong. Please try again.');
        }
        return true;
    }

    public function _action_post() {

        _model('orders/invoice_item','invoice_item');

        $obj = _input('obj');
        $this->_prep_obj($obj);

        $order = $obj['order_table'];
        $order_items = $obj['order_item_table'];

        $warehouse_id = $order['warehouse_id'];

        $so_id = $order['id'];

        $filter=[
            'id'    =>  $so_id
        ];

        if($this->{$this->model}->update($order,$filter)) {

            $old_order = $this->{$this->model}->single(['id'=>$so_id]);
            $old_order['reason'] = 'sale';
            $old_order_items = $this->invoice_item->search(['so_id'=>$so_id]);
            _get_module('items','_clear_inventory',['order'=>$old_order,'order_items'=>$old_order_items]);

            foreach ($order_items as $item) {
                $order_item_id = $item['id'];
                if($item['id']) {
                    $this->invoice_item->update($item, ['id' => $order_item_id]);
                }else{
                    $item['so_id'] = $so_id;
                    $this->invoice_item->insert($item);
                }

                if($order['order_status']=='Confirmed') {

                    $item_id = $item['item_id'];
                    $sku_id = $item['sku_id'];

                    $amount = (float)$item['quantity'] * (float)$item['rate'];

                    $item_inventory = [
                        'order_id'      =>  $so_id,
                        'item_id'       =>  $item_id,
                        'sku_id'        =>  $sku_id,
                        'warehouse_id'  =>  $warehouse_id,
                        'reason'        =>  'sale',
                        'date'          =>  sql_now_datetime(),
                        'quantity'      =>  (-1 * abs($item['quantity'])),
                        'rate'          =>  $item['rate'],
                        'amount'        =>  $amount,
                        'created_by'    =>  _get_user_id(),
                        'added'         =>  sql_now_datetime()
                    ];
                    _get_module('items','_update_inventory',['inventory'=>$item_inventory]);
                }
            }

            _response_data('redirect',base_url($this->module));
        }else{
            _response_data('message','Something wrong. Please try again.');
        }
        return true;
    }

    public function _action_delete() {

        $ignore_list = [];

        $id = _input('id');

        if(!in_array($id,$ignore_list)) {

            $filter = ['id' => $id];

            $result = $this->{$this->model}->single($filter);

            if($result) {

                $affected_rows = $this->{$this->model}->delete($filter);

                if ($affected_rows) {
                    _response_data('redirect', base_url($this->module));
                    return true;
                } else {
                    return false;
                }

            }else{
                _response_data('message','You cannot delete a protected size.');
                return false;
            }
        }else{
            _response_data('message','You cannot delete a protected size.');
            return false;
        }
    }

    private function _prep_obj(&$obj) {

        $payment_keys = $this->{$this->model}->keys;

        $obj['payment_table'] = [];

        foreach ($payment_keys as $old => $new) {
            change_array_key($old,$new,$obj);
            if(isset($obj[$new])) {
                $obj['payment_table'][$new] = $obj[$new];
                unset($obj[$new]);
            }
        }

    }

    private function _single($id) {

        _model('invoice_item');

        $order_exclude_fields = $this->{$this->model}->exclude_keys;
        $order_item_exclude_fields = $this->invoice_item->exclude_keys;

        $order_keys = $this->{$this->model}->keys;
        $order_item_keys = $this->invoice_item->keys;

        $filter = ['id'=>$id];
        $result = $this->{$this->model}->single($filter);

        if($result) {

            $result = filter_array_keys($result,$order_exclude_fields);
            $result['items'] = [];

            $customer = _get_module('contacts/customers','_single',['id'=>$result['customer_id']]);

            $result['customer'] = $customer;

            $warehouse = _get_module('warehouses','_single',['id'=>$result['warehouse_id']]);

            $result['company'] = [
                'name'      =>  $warehouse['title'],
                'address1'  =>  $warehouse['address_1'],
                'address2'  =>  $warehouse['address_2'],
                'city'      =>  $warehouse['city'],
                'state'     =>  $warehouse['state_id'],
                'country'   =>  $warehouse['country_id'],
                'pincode'   =>  $warehouse['zipcode'],
                'email'     =>  $warehouse['email'],
                'phone'     =>  $warehouse['phone'],
            ];

            $items = $this->invoice_item->search(['invoice_id'=>$result['id']]);

            if($items) {
                foreach ($items as $item) {

                    $price_filter = ['item_id'=>$item['item_id'],'sku_id'=>$item['sku_id']];

                    $prices = _get_module('items/item_prices','_search',['filter'=>$price_filter,'exclude'=>true,'convert'=>true]);

                    if($prices) {
                        $temp = [];
                        foreach ($prices as $price) {
                            unset($price['purchasePrice']);
                            $temp[] = $price;
                        }
                        $prices = $temp;
                    }

                    foreach ($order_item_keys as $new=>$old) {
                        change_array_key($old,$new,$item);
                    }
                    $item = filter_array_keys($item,$order_item_exclude_fields);
                    $item['prices'] = $prices;
                    $result['items'][] = $item;
                }
            }

            foreach ($order_keys as $new=>$old) {
                change_array_key($old,$new,$result);
            }

            return $result;

        }else{

            return false;

        }

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

        $watermark_enabled = _get_setting('watermark_enabled',false,'pdf');
        //$watermark_text = ($watermark_enabled)?_get_setting('watermark_text',false,'pdf'):false;

        $this->view = false;

        $file_name = strtolower('payment-'.$id.'-'.date('M').date('Y').'.pdf');
        $upload_path = _get_config('pdf_path') . 'payments/';

        if(!file_exists($upload_path)) {
            mkdir($upload_path,0777,true);
        }

        if(!file_exists($upload_path.$file_name) || $force==true) {

            $invoice = $this->_single($id);

            //$status = ($invoice['orderStatus']=='Received')?'Received':false;

            $watermark_text = '';//($watermark_enabled)?$status:false;

            _vars('obj',$invoice);

            $pdf_data = _view('invoice_pdf');

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

    public function _single_get() {

        $id = _input('id');

        if($id) {

            $result = $this->_single($id);

            if ($result) {

                _response_data('obj', $result);

            } else {
                _set_message('The requested details could not be found.', 'warning');
                _response_data('redirect', base_url($this->module));
            }
        }else{
            _set_message('The requested details could not be found.', 'warning');
            _response_data('redirect', base_url($this->module));
        }
        return true;
    }

    public function _single_view_get() {

        $id = _input('id');

        if($id) {

            $result = $this->_single($id);

            if ($result) {

                $result['pdfUrl'] = base_url("orders/payments/pdf/$id");

                _response_data('obj', $result);

            } else {
                _set_message('The requested details could not be found.', 'warning');
                _response_data('redirect', base_url($this->module));
            }
        }else{
            _set_message('The requested details could not be found.', 'warning');
            _response_data('redirect', base_url($this->module));
        }
        return true;

    }

    public function _select_data_get() {

        $params = [];
        $params['include_select'] = false;
        $tours = $this->_get_select_data($params);

        _response_data('tours',$tours);
        return true;

    }

    public function _get_order_payments($params=[]) {
        $order_id = $params['order_id'];
        $filter = [
            'order_id'    => $order_id
        ];
        $this->{$this->model}->order_by('payment_date','DESC');
        $result = $this->{$this->model}->search($filter);

        $payments = [];
        if($result) {
            foreach ($result as $row) {
                $this->_exclude_keys($row);
                $this->_sql_to_vue($row);
                $payments[] = $row;
            }
        }
        return $payments;
    }

    public function _get_order_payment_total($params=[]) {

        $order_id = $params['order_id'];
        $filter = [
            'order_id'    => $order_id
        ];

        $this->{$this->model}->select('SUM(amount) as amount');
        $result = $this->{$this->model}->single($filter);

        if($result['amount'] === NULL) {
            return 0;
        }else{
            return $result['amount'];
        }

    }

    public function _get_select_data($params=[]) {
        $include_select = isset($params['include_select'])?$params['include_select']:true;

        $tours = $this->{$this->model}->get_active_list();
        if($tours) {
            $tours = get_select_array($tours,'id','title',$include_select,'','Select '.$this->singular);
            return $tours;
        }else{
            return [];
        }

    }

    private function _generate($so_id) {

        _model('orders/so_item','so_item');

        _model('orders/payment','payment');
        _model('orders/invoice_item','invoice_item');

        $so = $this->{$this->model}->single(['id'=>$so_id]);
        $so_items = $this->invoice_item->search(['so_id'=>$so_id]);

        if($so && $so_items) {

            $invoice_data = $so;

            $invoice_data['so_id'] = $invoice_data['id'];
            unset($invoice_data['id']);
            $invoice_data['added'] = sql_now_datetime();
            $invoice_data['order_no'] = _get_ref('inv');

            $this->payment->insert($invoice_data);
            $invoice_id = $this->payment->insert_id();

            if($invoice_id) {

                _update_ref('inv');

                foreach ($so_items as $item) {

                    $item['added'] = sql_now_datetime();
                    $item['inv_id'] = $invoice_id;
                    unset($item['so_id']);
                    $this->invoice_item->insert($item);

                }

            }
            return true;

        }
        return false;

    }

    protected function _load_files() {

        if(_get_method()=='index') {
            _load_plugin(['moment','dt']);
        }

        if(_get_method()=='add' || _get_method()=='edit') {
            _load_plugin(['moment','vue_multiselect','datepicker']);
            _page_script_override('orders/payments-form');
        }

    }
}
