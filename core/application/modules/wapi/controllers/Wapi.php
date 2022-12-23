<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Wapi extends MY_Controller {

    public $module = 'wapi';
    public $model = 'wapi_model';
    public $singular = 'Web API';
    public $plural = 'Web API';
    public $language = 'wapi/wapi';
    public $edit_form = '';
    public function __construct()
    {
        parent::__construct();
        _model($this->model);
        $params = [
            'migration_path' => WAPI_MIGRATION_PATH,
            'migration_table' => WAPI_MIGRATION_TABLE
        ];
        $this->init_migration($params);
    }
    public function index()	{

        return false;

    }

    public function _populate_get() {

        _model('pos_session');

        $register_id = _input('register_id');

        $session = $this->pos_session->get_open($register_id);
        if($session) {
            $this->_exclude_keys($session,$this->pos_session->exclude_keys);
            $this->_sql_to_vue($session,$this->pos_session->keys);
        }

        _response_data('session',($session)?$session:null);
        return true;
    }

    public function _populate_menu_items_get() {

        $type = _input('type');
        $category_cache = $type ? "web_categories_$type" : "web_categories";
        $item_cache = $type ? "web_items_$type" : "web_items";

        _helper('zebra');

        if(!$categories = _get_cache($category_cache)) {
            $filters = ['web_status' => 1];
            if($type != null) {
                $filters['type'] = $type;
            }
            $categories = _get_module('items', '_get_web_categories', ['order' => ['order_by' => 'sort_order', 'order' => 'ASC'], 'filter' => $filters]);
            $skip_categories = [];
            $skip_categories[] = OPEN_ITEM_CATEGORY_ID;

            if($categories) {
                $temp = [];
                foreach ($categories as $category) {
                    if(in_array($category['id'],$skip_categories)) {
                        continue;
                    }
                    $temp[] = $category;
                }
                $categories = $temp;
            }
            _set_cache($category_cache, $categories);
        }
        if ( !$items = _get_cache($item_cache) ) {
            $item_params = [];
            $item_params['filter'] = ['web_status' => 1, 'type' => 'product', 'parent' => 0];

            $item_params['limit'] = 3000;
            $item_params['orders'] = [['order_by' => 'title', 'order' => 'ASC']];
            $item_params['exclude'] = true;
            $item_params['convert'] = true;
            $items = _get_module( 'items', '_search', $item_params );
            if ( $items ) {
                $temp = [];
                foreach ( $items as $item ) {
                    $variant_params = [
                        'filter'  => [
                            'parent' => $item['id'],
                            'type'   => ITEM_TYPE_VARIANT,
                        ],
                        'exclude' => true,
                        'convert' => true,
                    ];

                    $variations = _get_module( 'items', '_get_item_variations', $variant_params );
                    $new_variations = [];
                    if ( $variations ) {
                        $prices = array_column($variations,'rate');
                        $min_price = min($prices);
                        $item['rate'] = $min_price;
                        foreach ($variations as $v){
                            $new_variations[] = [
                                'title' => $v['title'],
                                'isVeg' =>$v['isVeg'] == '1',
                                'rate' =>$v['rate'],
                                'isVegan' => $v['isVegan'] == '1',
                                'isGlutenFree' => $v['isGlutenFree'] == '1',
                                'isDairyFree' => $v['isDairyFree'] == '1',
                            ];
                        }
                    }
                    $temp[]=[
                        'id'          =>  $item['id'],
                        'name'        =>  $item['title'],
                        'rate'        =>  $item['rate'],
                        'type'        =>  $item['type'],
                        'description' =>  $item['description'],
                        'isVeg'        =>  $item['isVeg'] == '1',
                        'isVegan'       => $item['isVegan'] == '1',
                        'isGlutenFree' => $item['isGlutenFree'] == '1',
                        'isDairyFree' => $item['isDairyFree'] == '1',
                        'variations'  =>  $new_variations,
                        'categoryId'  =>  $item['categoryId'],
                    ];
                }

                $items = $temp;
                _set_cache($item_cache,$items);
            }
        }
        _response_data('categories',$categories);
        _response_data('items',$items);
        return true;

    }
    public function _get_menu(){
        return [];
    }

    public function _populate_items_get() {

        _helper('zebra');

        $params['include_select'] = true;
        $categories = _get_module('items','_get_categories',['order'=>['order_by'=>'sort_order','order'=>'ASC'],'filter'=>['web_status'=>1],'select_data'=>true,'include_select'=>false]);

        $skip_categories = [];
        $skip_categories[] = OPEN_ITEM_CATEGORY_ID;

        if($categories) {
            $temp = [];
            foreach ($categories as $category) {
                if(in_array($category['id'],$skip_categories)) {
                    continue;
                }
                $temp[] = $category;
            }
            $categories = $temp;
        }

        $item_params = [];
        $item_params['limit'] = 3000;
        $item_params['orders'] = [['order_by'=>'title','order'=>'ASC']];
        $item_params['filter'] = ['web_status'=>1,'is_addon'=>0];
        $item_params['exclude'] = true;
        $item_params['convert'] = true;
        $items = _get_module('items','_search',$item_params);

        $skip_items = [];
        $skip_items[] = OPEN_ITEM_ID;

        if($items) {
            $temp = [];
            foreach ($items as $item) {

                if(in_array($item['id'],$skip_items)) {
                    continue;
                }

                $item_details = _get_module('pos','_single_item',['id'=>$item['id'],'type'=>$item['type']]);

                if(@$item_details['prices'] && $item_details['type']==='single') {
                    if (count($item_details['prices'])) {
                        $item_details['rate'] = $item_details['prices'][0]['salePrice'];
                        unset($item_details['prices']);
                    }
                }
                if($item_details['type']==='group') {
                    $item_details['rate'] = $item_details['variations'][0]['salePrice'];
                }

                $temp[] = $item_details;

            }
            $items = $temp;
        }


        _response_data('categories',$categories);
        _response_data('items',$items);
        return true;

    }

    public function _customer_login_post() {

        _model('web_session');
        _helper('password');
        _library('creatorjwt');

        $email = _input('email');
        $password = _input('password');

        if($email && $password) {
            $filters = [];
            $filters['filter'] = ['email'=>$email,'password'=>hash_password($password)];
            $customer = _get_module('contacts/customers','_find',$filters);

            if($customer) {
                $token = $this->_get_customer_token($customer['id']);
                $customerInfo = $customer['display_name'] . ' ( ' . $customer['phone'] . ')';

                _response_data('customerInfo',$customerInfo);
                _response_data('token',$token);
                return true;
            }
            _response_data('message','Invalid email or password');
            return false;
        }
        _response_data('message','Invalid email or password');
        return false;
    }

    public function _customer_logout_post() {

        _model('web_session');
        _library('creatorjwt');

        $token = _input('token');
        if($token) {
            $data = $this->creatorjwt->decode_token($token);
            if ($data) {
                $user_id = $data['user_id'];
                $key = $data['key'];

                if ($key == $this->web_session->get($user_id)) {
                    $this->web_session->clear($user_id);
                    return true;
                }
            }
        }
        return false;

    }

    public function _validate_session_post() {
        _library('creatorjwt');
        _model('web_session');

        $logged_in = false;

        $token = _input('token');
        if($token) {
            $data = $this->creatorjwt->decode_token($token);
            if ($data) {
                $user_id = $data['user_id'];
                $key = $data['key'];

                if ($key == $this->web_session->get($user_id)) {
                    $logged_in = true;
                }
            }
        }

        $tax = ['title'=>'TAX', 'rate'=>5];
        $allow_online_orders = _get_setting('web','0','order_sources_switch');

        _response_data('allowOrders',$allow_online_orders == '1');
        _response_data('tax',$tax);
        _response_data('loggedIn',$logged_in);
        return true;
    }

    public function _customer_register_post() {
        _helper('password');
        _library('creatorjwt');
        _model('web_session');
        $obj = _input('obj');

        $params = [
            'phone' =>  $obj['phone']
        ];
        $customer = $this->_customer_info($params);

        $data = [
            'phone'         =>  $obj['phone'],
            'email'         =>  $obj['email'],
            'display_name'  =>  $obj['displayName'],
            'password'      =>  hash_password($obj['password'])
        ];

        $result = null;
        if($customer) {
            //Check if existing POS customer is registering
            if($customer['password']=='') {

                $filter = ['id'=>$customer['id']];
                _get_module('contacts/customers','_update',['data'=>$data,'filter'=>$filter]);
                $result = true;

            }else{
                _response_data('message','Customer already registered. Please try login or Forgot Password.');
                return false;
            }
        }else{
            $id = _get_module('contacts/customers','_insert',['data'=>$data]);
            if($id) {
                $params = [
                    'id' => $id
                ];
                $customer = $this->_customer_info($params);
                $result = true;
            }
        }
        if($result) {
            $token = $this->_get_customer_token($customer['id']);
            _response_data('token',$token);

            $customerInfo = $customer['displayName'] . ' ( ' . $customer['phone'] . ')';

            _response_data('customerInfo',$customerInfo);
            _response_data('message','Registered Successfully');
            return true;
        }
        return false;
    }

    private function _get_customer_token($customer_id) {
        $session = $this->web_session->generate($customer_id);
        $token_params = [
            'user_id'   =>  $session['user_id'],
            'key'       =>  $session['key'],
            'timestamp' =>  $session['timestamp']
        ];
        return $this->creatorjwt->generate_token($token_params);
    }

    public function _customer_info($params=[]) {

        $filter_params = [];
        $filter_params['filter'] = $params;
        $filter_params['exclude'] = ['status','added','baddress_id','saddress_id'];
        $filter_params['convert'] = true;

        $customer = _get_module('contacts/customers','_find',$filter_params);

        return ($customer)?$customer:false;

    }

    public function _order_put() {

        _library('creatorjwt');
        _model('web_session');

        $obj = _input('obj');
        $token = _input('token');
        if($token) {
            //Member order
            $data = $this->creatorjwt->decode_token($token);
            if ($data) {
                $user_id = $data['user_id'];
                $key = $data['key'];

                if ($key == $this->web_session->get($user_id)) {
                    //Valid Customer
                    $obj['customer_id'] = $user_id;
                }
            }
        }

        return $this->_manage_order($obj);
    }

    public function _manage_order($obj) {

        _model('orders/order','order');
        _model('orders/order_item','order_item');
        _model('orders/order_item_note','note');
        _model('orders/payment_description','payment_description');

        $transaction_id = [];

        $this->_prep_order_obj($obj);

        $mode = 'add';

        $order_table = $obj['order_table'];
        $payment_table = $obj['order_payment_table'];

        $order = $this->_add_order($order_table,$mode);

        if($order) {

            $order_id = $order['id'];

            $items_table = $obj['order_item_table'];
            $order['items'] = [];
            $ignore_items = [];

            foreach ($items_table as $item) {

                $item['order_id'] = $order_id;
                $updated_item = $this->_add_order_item($item);

                $ignore_items[] = $updated_item['id'];

                $order['items'][] = $updated_item;

            }
            $this->_clear_order_items($order_id,$ignore_items);

            $ignore_payments = [];
            if($payment_table) {
                $first = true;
                foreach ($payment_table as $payment) {

                    $payment['reference_no'] = $order['order_no'];
                    $payment['order_id'] = $order_id;

                    $updated_payment = $this->_add_payment($payment);
                    if($updated_payment) {
                        if($first) {
                            $transaction_id = $payment['description']['transaction_id'];
                            $first = false;
                        }
                        $ignore_payments[] = $updated_payment['id'];
                    }
                }
            }

            $this->_clear_payments($order['id'],$ignore_payments);

            $items = $order['items'];
            unset($order['items']);
            $this->_exclude_keys($order,$this->order->exclude_keys);
            $this->_sql_to_vue($order,$this->order->keys);
            foreach ($items as $item) {
                $this->_exclude_keys($item,$this->order_item->exclude_keys);
                $this->_sql_to_vue($item,$this->order_item->keys);
                $order['items'][] = $item;
            }
            $order['transactionId'] = $transaction_id;

            $params = [
                'type'      =>  'newOrder',
                'title'     =>  'New Online Order',
                'message'   =>  'Received online order'
            ];
            if(@$obj['order_table']['billing_name']) {
                $params['message'] .= ' from ' . $obj['order_table']['billing_name'];
            }
            _get_module('notifications','_broadcast',$params);

            _response_data('order',$order);
            return true;

        }
        return false;

    }

    private function _prep_order_obj(&$obj) {

        $order_keys = $this->order->keys;

        if(isset($obj['customer_id']) && $obj['customer_id']) {
            $customer_id = $obj['customer_id'];
            $customer = _get_module('contacts/customers','_single',['id'=>$customer_id]);
        }else{
            //TODO Guest Order
            $customer = false;
        }

        if($customer) {
            $obj['customer_id'] = $customer['id'];

            $obj['billingName'] = $customer['displayName'];
            $obj['billingAddress1'] = $customer['billing']['address1'];
            $obj['billingAddress2'] = $customer['billing']['address2'];
            $obj['billingCity'] = $customer['billing']['city'];
            $obj['billingState'] = $customer['billing']['state'];
            $obj['billingCountry'] = $customer['billing']['country'];
            $obj['billingZipCode'] = $customer['billing']['zipCode'];

            $obj['shippingAddress1'] = $customer['shipping']['address1'];
            $obj['shippingAddress2'] = $customer['shipping']['address2'];
            $obj['shippingCity'] = $customer['shipping']['city'];
            $obj['shippingState'] = $customer['shipping']['state'];
            $obj['shippingCountry'] = $customer['shipping']['country'];
            $obj['shippingZipCode'] = $customer['shipping']['zipCode'];

            $obj['order_table'] = [];
            $obj['order_item_table'] = [];
            $obj['order_payment_table'] = [];

            if (isset($obj['cart']['totals']['payments'])) {
                foreach ($obj['cart']['totals']['payments'] as $payment) {

                    $payment_insert = [
                        'cash'              =>  false,
                        'amount'            =>  (@$payment['transactions'][0]['amount']['total'])?$payment['transactions'][0]['amount']['total']:0,
                        'notes'             =>  '',
                        'payment_method_id' =>  (@$payment['payment_id'])?$payment['payment_id']:WEB_PAYPAL_PAYMENT_METHOD_ID,
                        'customer_id'       =>  $obj['customer_id']
                    ];

                    $payment_desc_insert = [
                        'transaction_id'    =>  $payment['id'],
                        'source_data'       =>  (is_array($payment))?json_encode($payment):''
                    ];
                    $payment_insert['description'] = $payment_desc_insert;

                    $obj['order_payment_table'][] = $payment_insert;
                }
                unset($obj['cart']['totals']['payments']);
            }

            foreach ($obj['cart']['totals'] as $key => $value) {
                $obj[$key] = $value;
            }
            unset($obj['cart']['totals']);

            foreach ($order_keys as $old => $new) {
                change_array_key($old, $new, $obj);
                if (isset($obj[$new])) {
                    $obj['order_table'][$new] = $obj[$new];
                    unset($obj[$new]);
                }
            }

            if (isset($obj['close']) && $obj['close'] === 'true') {
                $obj['order_table']['order_status'] = 'Closed';
                unset($obj['close']);
            }

            if (!isset($obj['order_table']['source_id'])) {
                $obj['order_table']['source_id'] = SO_SOURCE_WEB_ID;
            }

            if (!isset($obj['order_table']['tax_rate'])) {
                $obj['order_table']['tax_rate'] = 5;
            }

            if (!isset($obj['order_table']['freight_total'])) {
                $obj['order_table']['freight_total'] = 0;
            }

            if (!isset($obj['order_table']['duty_total'])) {
                $obj['order_table']['duty_total'] = 0;
            }

            $freight = ((float)$obj['order_table']['freight_total']) ? (float)$obj['order_table']['freight_total'] : 0;
            $duty = ((float)$obj['order_table']['duty_total']) ? (float)$obj['order_table']['duty_total'] : 0;

            foreach ($obj['cart']['items'] as $key => $item) {
                $web_item = [
                    'type' => $item['type'],
                    'quantity' => (float)$item['quantity'],
                    'title' => $item['name'],
                    'notes' => '',
                    'rate' => (float)$item['rate'],
                    'item_id' => $item['id'],
                    'unit_id' => $item['unit'],
                    'sale_unit_id' => $item['unit'],
                    'unit_quantity' => $item['unit'],
                    'unit_rate' => (float)$item['rate'],
                    'has_spice_level' => $item['hasSpiceLevel'],
                    'spice_level' => $item['spiceLevel'],
                ];
                $temp = $web_item;

                $temp['has_spice_level'] = ($temp['has_spice_level'] == 'true') ? 1 : 0;
                $temp['amount'] = (float)$temp['quantity'] * (float)$temp['rate'];
                $temp['freight_total'] = 0;
                $temp['duty_total'] = 0;
                if ($freight > 0) {
                    $item_freight = ((float)$temp['amount'] * (float)$freight) / (float)$obj['order_table']['sub_total'];
                    $temp['freight_total'] = $item_freight;
                }
                if ($duty > 0) {
                    $item_duty = ((float)$temp['amount'] * (float)$duty) / (float)$obj['order_table']['sub_total'];
                    $temp['duty_total'] = $item_duty;
                }
                $obj['order_item_table'][] = $temp;
            }
            unset($obj['cart']['items']);
            unset($obj['cart']);
            unset($obj['company']);
        }
    }

    private function _add_order($obj,$mode) {

        $order_id = false;
        if($mode==='add') {

            $session_id = WEB_SESSION_ID;
            $obj['session_id'] = $session_id;
            $obj['session_order_no'] = $this->_get_new_session_order_no($session_id);
            $obj['order_no'] = _get_ref(ORDER_REF);
            $obj['reference_no'] = '';
            $obj['notes'] = '';
            $obj['order_date'] = sql_now_datetime();
            $obj['added'] = sql_now_datetime();
            if (!isset($obj['order_status'])) {
                $obj['order_status'] = 'Confirmed';
            }
            $obj['employee_id'] = WEB_SALESPERSON_ID;//_get_user_id();

            $obj['adjustment'] = 0;

            if($this->order->insert($obj)) {
                $order_id = $this->order->insert_id();
                _update_ref(ORDER_REF);
            }

        }

        if($order_id) {
            $order = $this->order->single(['id'=>$order_id]);
            if($order) {
                return $order;
            }
        }
        return false;
    }

    private function _add_order_item($obj) {

        $item_id = false;
        if(isset($obj['id']) && $obj['id']) {

            $item_id = $obj['id'];
            unset($obj['id']);

            $this->order_item->update($obj,['id'=>$item_id]);

        }else{

            $item['unit'] = '';
            $item['sale_unit'] = '';
            $obj['added'] = sql_now_datetime();

            if ($this->order_item->insert($obj)) {
                $item_id = $this->order_item->insert_id();
            }

        }

        if($item_id) {
            $item = $this->order_item->single(['id' => $item_id]);

            if ($item) {
                return $item;
            }
        }

        return false;

    }

    private function _add_payment($obj) {

        _model('orders/payment','payment');

        $payment_id = false;
        $order_id = $obj['order_id'];
        $payment_method_id = $obj['payment_method_id'];
        $description = $obj['description'];

        unset($obj['cash']);
        unset($obj['description']);

        $payment_obj = $this->payment->single(['order_id'=>$order_id,'payment_method_id'=>$payment_method_id]);

        if($payment_obj) {

            $payment_id = $payment_obj['id'];
            $this->payment->update($obj,['id'=>$payment_id]);

        }else {

            $obj['order_no'] = _get_ref('pay');
            $obj['payment_date'] = sql_now_datetime();
            $obj['added'] = sql_now_datetime();

            if ($this->payment->insert($obj)) {
                $payment_id = $this->payment->insert_id();
                _update_ref('pay');

                $description['added'] = sql_now_datetime();
                $description['payment_id'] = $payment_id;
                $this->payment_description->insert($description);
            }

        }
        if($payment_id) {
            $payment = $this->payment->single(['id' => $payment_id]);

            if ($payment) {
                return $payment;
            }
        }
        return false;
    }

    private function _get_new_session_order_no($session_id) {

        $query = "SELECT MAX(oo.session_order_no) as last_order_no FROM ord_order oo WHERE oo.session_id=$session_id";

        $result = _db_get_query($query,true);

        if($result['last_order_no'] === null) {
            return 1;
        }else{
            return (int)$result['last_order_no'] + 1;
        }

    }

    private function _clear_order_items($so_id,$ignore) {

        if($ignore) {
            $this->db->where_not_in('id',$ignore);
        }
        return $this->order_item->delete(['order_id'=>$so_id]);

    }

    private function _clear_payments($order_id,$ignore) {

        $payments = $this->payment->search(['order_id'=>$order_id]);
        if($payments) {
            foreach ($payments as $payment) {
                $payment_id = $payment['id'];
                if(!in_array($payment_id,$ignore)) {
                    $this->payment_description->delete(['payment_id' => $payment_id]);
                }
            }
        }

        if($ignore) {
            $this->db->where_not_in('id',$ignore);
        }
        $this->payment->delete(['order_id'=>$order_id]);

    }

    public function _install() {
        return true;
    }

    public function _uninstall() {
        return true;
    }

    protected function _load_files() {

        if(_get_method()=='index') {
            _load_plugin(['vue_multiselect','moment']);
            //_load_plugin(['moment','dt']);
        }

    }

}
