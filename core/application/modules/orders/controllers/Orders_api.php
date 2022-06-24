<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Orders_api extends API_Controller
{

    public $model = 'order';
    public $module = 'orders';
    public $singular = 'order';
    public $plural = 'Orders';

    public function __construct() {
        parent::__construct();
        _model(['order','web_session','customer','payment','order_item','order_item_note','payment_description','order_item_addon']);
        _library('creatorjwt');
        _helper('password');
    }


    public function _api_validate_session($token) {
        $logged_in = false;
        $user_id = false;
        $customer = false;
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
        if($user_id){
            $customer = $this->customer->single(['id'=>$user_id]);
        }
        $allow_online_orders = _get_setting('web','0','order_sources_switch');
        if(@$customer){
           $token = $this->_get_customer_token($customer['id']);
        }
        $customerInfo['display_name'] = (@$customer['display_name'])??null;
        $customerInfo['email'] = (@$customer['email'])??null;
        $customerInfo['id'] = (@$customer['id'])??null;
        $customerInfo['phone'] = (@$customer['phone'])??null;
        $res_data['status'] = true;
        $res_data['customer'] =$customerInfo;
        $res_data['key'] =$token;
        $res_data['currencySign'] = _get_setting('currency_sign','');
        $res_data['allowOrders'] = $allow_online_orders == '1';

        $response = [
            'status' => 'ok',
            'type' => 'HTTP_CREATED',
            'data' => $res_data,
            'expiresIn' => 86400,
            'message' => 'Login Successful'
        ];

        return $response;
       
    }

    public function _api_login($params=[]) {

        $email = $params['email'];
        $password = $params['password'];
        $customer = $this->customer;

        $filters = [];
        $filters['filter'] = ['email'=>$email];
        $obj = $this->customer->single($filters['filter']);

        if($customer) {
            if ($this->_is_activated($obj)) {
                _helper('password');
                if (trim(hash_password($password)) == trim($obj['password'])) {

                    $token = $this->_get_customer_token($obj['id']);
                    if($token) {

                        $user = false;
                        $id = $obj['id'];

                        $customerInfo['display_name'] = $obj['display_name'];
                        $customerInfo['email'] = $obj['email'];
                        $customerInfo['id'] = $obj['id'];
                        $customerInfo['phone'] = $obj['phone'];
                        $data['status'] = true;
                        $data['existing']= true;
                        $data['customer'] =$customerInfo;
                        $data['key'] =$token;

                        $response = [
                            'status' => 'ok',
                            'type' => 'HTTP_CREATED',
                           'data'=>$data,
                            'expiresIn' => 86400,
                            'message' => 'Login Successful'
                        ];
                    }else{
                        $response = [
                            'status' => 'error',
                            'type' => 'HTTP_BAD_REQUEST',
                            'message' => 'Invalid Email or Password'
                        ];
                    }
                } else {
                    $response = [
                        'status' => 'error',
                        'type' => 'HTTP_BAD_REQUEST',
                        'message' => 'Invalid Email or Password'
                    ];
                }

            } else {
                $response = [
                    'status' => 'error',
                    'type' => 'HTTP_BAD_REQUEST',
                    'message' => 'Your account has been disabled.'
                ];
            }
        }else {
            $response = [
                'status' => 'error',
                'type' => 'HTTP_BAD_REQUEST',
                'message' => 'Invalid Email or Password'
            ];
        }
        return $response;
    }

    public function _api_register($obj = []) {
        
    
        $params = [
            'phone' =>  $obj['phone'],
            'email'=>   $obj['email'],
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
               $this->customer->update($data,$filter);
                $result = true;

            }else{
                $res_data['status'] = false;
                $response = [
                    'status' => 'error',
                    'data'=>$res_data,
                    'type' => 'HTTP_BAD_REQUEST',
                    'message' => 'Customer already registered. Please try login or Forgot Password.'
                ];
                return $response;
                
            }
        }else{
            $id = $this->customer->save($data);
            if($id) {
                $params = [
                    'phone' => $data['phone']
                ];
                $customer = $this->_customer_info($params);
                $result = true;
            }
        }
        if($result) {
            $token = $this->_get_customer_token($customer['id']);
            $customerInfo['display_name'] = $customer['display_name'];
            $customerInfo['email'] = $customer['email'];
            $customerInfo['id'] = $customer['id'];
            $customerInfo['phone'] = $customer['phone'];
            $res_data['status'] = true;
            $res_data['existing']= true;
            $res_data['customer'] =$customerInfo;
            $res_data['key'] =$token;
            $res_data['message'] = 'Registered Successfully';
            $response = [
                'status' => 'ok',
                'type' => 'HTTP_CREATED',
                'expiresIn' => 86400,
                'data'=>$res_data,
                'message' => 'Registration was successful'
            ];

        }else{
            $response = [
                'status' => 'error',
                'type' => 'HTTP_BAD_REQUEST',
                'message' => 'Registration Failed'
            ];
        }
        return $response;
    }

    public function _customer_info($params=[]) {
        if(@$params['phone']){
            $phone = $params['phone'];
        }
        if(@$params['email']){
            $email = $params['email'];
        }
        $filter_params = [];
        if(@$params['phone']){
           $filter_params['filter'] = ['phone'=>$phone];
        }
        if(@$params['email']){
            $filter_params['filter'] = ['email'=>$email];
        }

        $customer = $this->customer->single($filter_params['filter']);
        return ($customer)?$customer:false;
    }

    public function _api_logout($token) {

      
        if($token) {
            $data = $this->creatorjwt->decode_token($token);
            if ($data) {
                $user_id = $data['user_id'];
                $key = $data['key'];

                if ($key == $this->web_session->get($user_id)) {
                    $this->web_session->clear($user_id);
                    $res_data['status'] = true;
                    $response = [
                        'status' => 'ok',
                        'type' => 'HTTP_CREATED',
                        'expiresIn' => 86400,
                        'data'=>$res_data,
                        'message' => 'Logout was successful'
                    ];
                    
                }
            }else{
                $response = [
                    'status' => 'error',
                    'type' => 'HTTP_BAD_REQUEST',
                    'message' => 'Logout Failed'
                ];
            }
        }
        return $response;
    }

    private function _is_activated($obj) {
        return true;
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

    public function _api_order($params=[]) {
        $token = $params['token'];
        $obj = json_decode($params['obj'],true);
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
        $this->_manage_order($obj);

        $response = [
            'status' => 'ok',
            'type' => 'HTTP_CREATED',
            'expiresIn' => 86400,
            'message' => 'Order was successful'
        ];
        return $response;

    }

    public function _manage_order($obj) {
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
           // _get_module('notifications','_broadcast',$params);


        }
      

    }

    private function _prep_order_obj(&$obj) {

        $order_keys = $this->order->keys;

        if(isset($obj['customer_id']) && $obj['customer_id']) {
            $customer_id = $obj['customer_id'];
            $customer = $this->customer->single(['id'=>$customer_id]);
        }else{
            //TODO Guest Order
            $customer = false;
        }

        if($customer) {
            $obj['customer_id'] = $customer['id'];

            $obj['order_table'] = [];
            $obj['order_item_table'] = [];
            $obj['order_payment_table'] = [];

            if (isset($obj['payment'])) {
                $payments[] = $obj['payment'];
                foreach ($payments as $payment) {

                    $payment_insert = [
                        'cash'              =>  false,
                        'amount'            =>  (@$obj['grand_total'])?$obj['grand_total']:0,
                        'notes'             =>  '',
                        'payment_method_id' =>  WEB_PAYPAL_PAYMENT_METHOD_ID,//(@$payment['paymentID'])?$payment['paymentID']:WEB_PAYPAL_PAYMENT_METHOD_ID,
                        'customer_id'       =>  $obj['customer_id']
                    ];

                    $payment_desc_insert = [
                        'transaction_id'    =>  $payment['paymentID'],
                        'source_data'       =>  (is_array($payment))?json_encode($payment):''
                    ];
                    $payment_insert['description'] = $payment_desc_insert;

                    $obj['order_payment_table'][] = $payment_insert;
                }
                unset($obj['payment']);
            }

          

            foreach ($order_keys as $old => $new) {
                change_array_key($old, $new, $obj);
                if (isset($obj[$new])) {
                    $obj['order_table'][$new] = $obj[$new];
                    unset($obj[$new]);
                }
            }

            $obj['order_table']['billing_name'] = $customer['display_name'];
          /*   $obj['order_table']['address1'] = '';
            $obj['order_table']['address2'] = '';
            $obj['order_table']['city'] = '';
            $obj['order_table']['state'] = '';
            $obj['order_table']['zip_code'] = '';
            $obj['order_table']['country'] = ''; */

           

            $obj['order_table']['order_status'] = 'Confirmed';

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

            foreach ($obj['items'] as $key => $item) {
                $web_item = [
                    'type' => $item['type'],
                    'title' =>  $item['type']==='product'?$item['title']:$item['title'].' - '.$item['variant_title'],
                    'quantity' => (float)$item['quantity'],
                    'notes' => (@$item['orderItemNotes'])??'',
                    'rate' => (float)$item['rate'],
                    'item_id' => $item['itemId'],
                    'unit_id' => 2,
                    'rate' => (float)$item['rate'],
                    'has_spice_level' => $item['hasSpiceLevel'],
                    'spice_level' => $item['spiceLevel'],
                    'amount'=> $item['amount'],
                ];
                $temp = $web_item;
                $temp['addons'] = $item['addons'];

                $temp['has_spice_level'] = ($temp['has_spice_level'] == 'true') ? 1 : 0;
               
                $obj['order_item_table'][] = $temp;
            }
            unset($obj['payment']);
            unset($obj['items']);
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
            $obj['notes'] = '';
            $obj['order_date'] = sql_now_datetime();
            $obj['added'] = sql_now_datetime();
            if (!isset($obj['order_status'])) {
                $obj['order_status'] = 'Confirmed';
            }
           // $obj['salesperson_id'] = WEB_SALESPERSON_ID;//_get_user_id();

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
        $addons = $obj['addons'];
        unset($obj['addons']);
        if(isset($obj['id']) && $obj['id']) {

            $item_id = $obj['id'];
            unset($obj['id']);

            $this->order_item->update($obj,['id'=>$item_id]);

        }else{

            $obj['added'] = sql_now_datetime();

            if ($this->order_item->insert($obj)) {
                $item_id = $this->order_item->insert_id();
            }

        }
        if($item_id) {
            $this->order_item_addon->delete(['order_item_id'=>$item_id]);
            if($addons) {
                $this->_vue_to_sql($addons,$this->order_item_addon->keys,true);
                foreach ($addons as $addon) {
                    $enabled = @$addon['enabled']==true;
                    if($enabled && $addon['quantity']>0) {
                        $addon['item_id'] = $addon['id'];
                        unset($addon['id']);
                        unset($addon['enabled']);
                        $addon['order_item_id'] = $item_id;
                        $addon['added'] = sql_now_datetime();
                        $this->order_item_addon->insert($addon);
                    }
                }
            }
            $item = $this->order_item->single(['id' => $item_id]);

            if ($item) {
                return $item;
            }
        }

        return false;

    }

    private function _add_payment($obj) {
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

  

}
