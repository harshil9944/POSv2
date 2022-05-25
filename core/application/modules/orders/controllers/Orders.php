<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Orders extends MY_Controller {

    public $module = 'orders';
    public $model = 'order';
    public $singular = 'Order';
    public $plural = 'Orders';
    public $language = 'orders/orders';
    public $edit_form = '';
    public function __construct()
    {
        parent::__construct();
        _model($this->model);
        $params = [
            'migration_path' => ORDER_MIGRATION_PATH,
            'migration_table' => ORDER_MIGRATION_TABLE
        ];
        $this->init_migration($params);
    }
    public function index()	{

        _library('table');
        $table = $this->{$this->model}->table;
        $offset = (_input('offset') && is_int((int)_input('offset')))?(int)_input('offset'):0;
        $filters['orders'] = [['order_by'=>$table.'.added','order'=>'DESC']];
        $filters['offset'] = $offset;
        $filters['limit'] = true;
        $results = $this->_search($filters);
       
       
        $count = _db_get_query('SELECT COUNT(*) as total FROM ord_order oo',true);
        $total_rows = ($count != false) ? $count['total'] : 0;
        $per_page = (int)_get_setting('global_limit',50);
        $paginate_url = base_url($this->module);  
        $body = [];
        if($results) {
            foreach ($results as $result) {
                $action = _edit_link(base_url($this->module.'/edit/'.$result['id'])) . _vue_delete_link('handleRemove('.$result['id'].')');
                $action_cell = [
                    'class' =>  'text-center',
                    'data'  =>  $action
                ];

                $amount_cell = [
                    'class' =>  'text-right',
                    'data'  =>  custom_money_format($result['grand_total'],'')
                ];

                $order_no = _vue_text_link($result['order_no'],'handleViewOrder('.$result['id'].')','View Order');

                $body[] = [
                    custom_date_format($result['order_date'],'d/m/Y'),
                    $order_no,
                   // $result['reference_no'],
                    $result['billing_name'],
                    $result['order_status'],
                    $amount_cell,
                    //$action_cell
                ];
            }
        }

        $heading = [
            'Date',
            'Order#',
            //'Reference#',
            'Customer Name',
            'Status',
            array('data'=>'Amount','class'=>'text-right'),
           // array('data'=>'Action','class'=>'text-center no-sort w-110p')
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
            'edit_form'     =>  '',
            'total_rows'    =>  $total_rows,
            'per_page'      =>  $per_page,
            'paginate_url'  =>  $paginate_url
        ];
        _vars('page_data',$page);
        _set_additional_component(LIST_XTEMPLATE_PATH,'outside');
        _set_additional_component('orders_detail_view');
        _set_layout_type('wide');
        _set_page_heading($this->plural);
        _set_layout(LIST_VIEW_PATH);

    }

    public function add() {
        _helper('control');
        _set_js_var('back_url',base_url($this->module),'s');
        _set_js_var('mode','add','s');
        _set_js_var('default_warehouse',_get_setting('default_warehouse'),'s');
        _set_page_heading('New ' . $this->singular);
        _set_page_title('New ' . $this->singular);
        _set_layout('salesorders_form_view');
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

    public function _get_menu() {

        $menus = [];

        $salesorders = [];

        $salesorders[] = [
            'name'	    =>  'Orders',
            'class'     =>  '',
            'group'     =>  '',
            'icon'      =>  'basket-loaded',
            'path'      =>  'orders',
            'module'    =>  'orders',
            'children'  =>  []
        ];

        $salesorders[] = [
            'name'	    =>  'Payments',
            'class'     =>  '',
            'group'     =>  '',
            'icon'      =>  'basket-loaded',
            'path'      =>  'orders/payments',
            'module'    =>  'orders',
            'children'  =>  []
        ];
        $salesorders[] = [
            'name'	    =>  'Order Sources',
            'class'     =>  '',
            'group'     =>  '',
            'icon'      =>  'basket-loaded',
            'path'      =>  'orders/order_sources',
            'module'    =>  'orders',
            'children'  =>  []
        ];
        $salesorders[] = [
            'name'	    =>  'Order Refunds',
            'class'     =>  '',
            'group'     =>  '',
            'icon'      =>  'basket-loaded',
            'path'      =>  'orders/refunds',
            'module'    =>  'orders',
            'children'  =>  []
        ];

        $menus[] = array(
            'id'        => 'menu-orders',
            'class'     => '',
            'icon'      => 'si si-basket',
            'group'     => 'module',
            'name'      => 'Sales',
            'path'      => 'orders',
            'module'    => 'orders',
            'priority'  => 3,
            'children'  => $salesorders
        );

        return $menus;

    }

    public function _populate_get() {

        $params['include_select'] = true;
        $customers = _get_module('contacts/customers','_get_select_data',$params);

        $warehouses = _get_module('warehouses','_get_select_data',['include_select'=>false]);
        $units = _get_module('core/units','_get_select_data',['include_select'=>false]);
        $salespersons = _get_module('salespersons','_get_select_data',['include_select'=>true]);

        $tax = [
            'title' =>  'VAT',
            'rate'  =>  5
        ];

        $order_number = _get_ref('so');

        _response_data('orderNo',$order_number);
        _response_data('defaultWarehouse',_get_setting('default_warehouse',1));
        _response_data('tax',$tax);

        _response_data('units',$units);
        _response_data('customers',$customers);
        _response_data('warehouses',$warehouses);
        _response_data('salespersons',$salespersons);
        return true;

    }

    public function _action_put() {

        _model('orders/order_item','order_item');

        $obj = _input('obj');
        $this->_prep_obj($obj);

        $order = $obj['order_table'];
        $order['added'] = sql_now_datetime();
        //$order['order_status'] = 'Confirmed';

        $warehouse_id = $order['warehouse_id'];

        /*$when = new DateTime($order['order_date']);
        $order['order_date'] = $when->format('Y-m-d H:i:s');*/

        $order_items = $obj['order_item_table'];

        if($this->{$this->model}->insert($order)) {
            $order_id = $this->{$this->model}->insert_id();

            _update_ref('so');

            foreach ($order_items as $item) {
                unset($item['id']);
                $item['order_id'] = $order_id;
                $item['added'] = sql_now_datetime();
                $this->order_item->insert($item);
                if($order['order_status']=='Confirmed') {

                    $item_id = $item['item_id'];

                    $amount = (float)$item['quantity'] * (float)$item['rate'];

                    $item_inventory = [
                        'order_id'      =>  $order_id,
                        'item_id'       =>  $item_id,
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
            //$this->_generate_invoice($order_id);

            _response_data('redirect',base_url($this->module));
        }else{
            _response_data('message','Something wrong. Please try again.');
        }
        return true;
    }

    public function _action_post() {

        _model('orders/order_item','order_item');

        $obj = _input('obj');
        $this->_prep_obj($obj);

        $order = $obj['order_table'];
        $order_items = $obj['order_item_table'];

        $warehouse_id = $order['warehouse_id'];

        $order_id = $order['id'];

        $filter=[
            'id'    =>  $order_id
        ];

        if($this->{$this->model}->update($order,$filter)) {

            $old_order = $this->{$this->model}->single(['id'=>$order_id]);
            $old_order['reason'] = 'sale';
            $old_order_items = $this->order_item->search(['order_id'=>$order_id]);
            _get_module('items','_clear_inventory',['order'=>$old_order,'order_items'=>$old_order_items]);

            foreach ($order_items as $item) {
                $order_item_id = $item['id'];
                if($item['id']) {
                    $this->order_item->update($item, ['id' => $order_item_id]);
                }else{
                    $item['order_id'] = $order_id;
                    $this->order_item->insert($item);
                }

                if($order['order_status']=='Confirmed') {

                    $item_id = $item['item_id'];

                    $amount = (float)$item['quantity'] * (float)$item['rate'];

                    $item_inventory = [
                        'order_id'      =>  $order_id,
                        'item_id'       =>  $item_id,
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

        _model('orders/order','order');
        _model('orders/order_item','order_item');
        _model('orders/order_item_addon','addon');
        _model('orders/order_item_note','note');
        _model('orders/payment','payment');
        _model('orders/order_promotion','promotion');

        _model('orders/split','split');
        _model('orders/split_item','split_item');
        _model('orders/split_payment','split_payment');

        $ignore_list = [];

        $order_id = _input('id');

        if(!in_array($order_id,$ignore_list)) {
            $result = $this->{$this->model}->single(['id' => $order_id]);
            if($result) {

                $order_items = $this->order_item->search(['order_id' => $order_id]);
                if($order_items) {
                    foreach($order_items as $item) {
                        $this->addon->delete(['order_item_id' => $item['id']]);
                        $this->note->delete(['order_item_id' => $item['id']]);
                    }
                }

                $this->order_item->delete(['order_id' => $order_id]);
                $this->payment->delete(['order_id' => $order_id]);
                $this->promotion->delete(['order_id' => $order_id]);
                $this->split->delete(['order_id' => $order_id]);
                $this->split_item->delete(['order_id' => $order_id]);
                $this->split_payment->delete(['order_id' => $order_id]);

                $affected_rows = $this->{$this->model}->delete(['id' => $order_id]);
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

        $order_keys = $this->{$this->model}->keys;
        $order_item_keys = $this->order_item->keys;

        $customer = _get_module('contacts/customers','_single',['id'=>$obj['customerId']]);

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

        foreach ($order_keys as $old => $new) {
            change_array_key($old,$new,$obj);
            if(isset($obj[$new])) {
                $obj['order_table'][$new] = $obj[$new];
                unset($obj[$new]);
            }
        }

        if(!isset($obj['order_table']['source_id'])) {
            $obj['order_table']['source_id'] = ORDER_SOURCE_PANEL_ID;
        }

        if(!isset($obj['order_table']['tax_rate'])) {
            $obj['order_table']['tax_rate'] = 5;
        }

        if(!isset($obj['order_table']['freight_total'])) {
            $obj['order_table']['freight_total'] = 0;
        }

        if(!isset($obj['order_table']['duty_total'])) {
            $obj['order_table']['duty_total'] = 0;
        }

        $freight = ((float)$obj['order_table']['freight_total'])?(float)$obj['order_table']['freight_total']:0;
        $duty = ((float)$obj['order_table']['duty_total'])?(float)$obj['order_table']['duty_total']:0;

        $remove_keys = ['value','prices'];
        foreach ($obj['items'] as $key=>$item) {
            $temp = $item;
            foreach ($remove_keys as $key) {
                if(isset($temp[$key])) {
                    unset($temp[$key]);
                }
            }
            foreach ($order_item_keys as $old => $new) {
                change_array_key($old,$new,$temp);
            }
            $temp['amount'] = (float)$temp['quantity'] * (float)$temp['rate'];
            if($freight>0) {
                $item_freight = ((float)$temp['amount'] * (float)$freight) / (float)$obj['order_table']['sub_total'];
                $temp['freight_total'] = $item_freight;
            }
            if($duty>0) {
                $item_duty = ((float)$temp['amount'] * (float)$duty) / (float)$obj['order_table']['sub_total'];
                $temp['duty_total'] = $item_duty;
            }
            $obj['order_item_table'][] = $temp;
        }
        unset($obj['items']);

    }

    public function _single($params=[]) {

        $id = $params['id'];
        $only_enabled_addons = (@$params['filter_disabled_addons'])?true:false;

        _model('order_item','order_item');
        _model('order_item_addon','addon');
        _model('order_item_note','note');
        _model('order_source','source');
        _model('order_promotion','promotion');

        _model('split','split');
        _model('split_item','split_item');
        _model('split_payment','split_payment');

        $order_exclude_fields = $this->{$this->model}->exclude_keys;
        $order_item_exclude_fields = $this->order_item->exclude_keys;

        $order_keys = $this->{$this->model}->keys;
        $order_item_keys = $this->order_item->keys;

        $filter = ['id'=>$id];
        $result = $this->{$this->model}->single($filter);

        if($result) {

            $result = filter_array_keys($result,$order_exclude_fields);
            $result['items'] = [];

            $customer = _get_module('contacts/customers','_single',['id'=>$result['customer_id']]);

            $result['customer'] = $customer;

            $result['orderSource'] = false;
            $source = $this->source->single(['id'=>$result['source_id']]);
            if($source) {
                //TODO make dynamic whether to print particular source
                $print_sources = (array)PRINT_SOURCE_IDS;
                $short_name = $source['title'];
                if(in_array($result['source_id'],$print_sources)) {
                    $result['orderSource'] = $short_name;
                }
            }

           // $warehouse = _get_module('warehouses','_single',['id'=>$result['warehouse_id']]);
/* 
            if($warehouse) {
                $result['company'] = [
                    'name' => $warehouse['title'],
                    'address1' => $warehouse['address_1'],
                    'address2' => $warehouse['address_2'],
                    'city' => $warehouse['city'],
                    'state' => $warehouse['state_id'],
                    'country' => $warehouse['country_id'],
                    'pincode' => $warehouse['zipcode'],
                    'email' => $warehouse['email'],
                    'phone' => $warehouse['phone'],
                ];
            } */

           // $result['company'] = [];

            $result['promotions']['available'] = [];

            $promotions = $this->promotion->search(['order_id'=>$id]);
            $result['promotions']['applied'] = ($promotions)?array_column($promotions, 'promotion_id'):[];

            $result['tableId'] = false;
            if($result['type'] === 'dine') {
                _model('areas/area_relation','area_relation');
                $this->area_relation->left_join(AREAS_TABLES_TABLE,AREAS_TABLES_TABLE.'.id='.AREAS_RELATION_TABLE.'.table_id');
                $this->area_relation->order_by('ara_relation.id','DESC');
                $relation = $this->area_relation->single(['order_id'=>$result['id']]);
                if($relation) {
                    $result['tableId'] = $relation['table_id'];
                    $result['tableTitle'] = $relation['title'];
                    $result['tableShortName'] = $relation['short_name'];
                }
            }

            $items = $this->order_item->search(['order_id'=>$result['id']]);

            $item_titles = [];

            if($items) {
                foreach ($items as $item) {
                    $item_id = $item['item_id'];
                    $item_titles[$item['id']] = $item['title'];
                    foreach ($order_item_keys as $new=>$old) {
                        change_array_key($old,$new,$item);
                    }
                    $item = filter_array_keys($item,$order_item_exclude_fields);


                    $notes = _get_module('items','_search_notes',['item_id'=>$item_id,'exclude'=>true,'convert'=>true]);
                    $item['notes'] = ($notes)?$notes:[];

                    $selected_addons = $this->addon->search(['order_item_id'=>$item['id']]);

                    $addons = _get_module('items','_search_addons',['parent_id'=>$item['parentId']]);
                    if($addons) {
                        $temp = [];
                        foreach ($addons as $addon) {
                            $item_id = $addon['itemId'];
                            $type = $addon['type'];
                            if($selected_addons) {
                                //TODO Check if duplicate item is found. it can cause error is same item is repeated
                                //TODO Never let repeat same addon_item_id for an addon otherwise it will case error
                                $filtered = array_values(array_filter($selected_addons,function($single) use ($item_id,$type) {
                                    return $single['item_id'] == $item_id  && $single['type'] == $type;
                                }));
                                if(count($filtered)==1) {
                                    $filtered = $filtered[0];
                                    $addon['enabled'] = true;
                                    $addon['quantity'] = $filtered['quantity'];
                                }else{
                                    $addon['enabled'] = false;
                                    $addon['quantity'] = 1;
                                }
                            }else{
                                $addon['enabled'] = false;
                                $addon['quantity'] = 1;
                            }
                            if($only_enabled_addons) {
                                if($addon['enabled']) {
                                    $temp[] = $addon;
                                }
                            }else {
                                $temp[] = $addon;
                            }
                        }
                        $addons = $temp;
                    }
                    $item['addons'] = ($addons)?$addons:[];

                    $item['selectedNotes'] = [];
                    $selected_notes = $this->note->search(['order_item_id'=>$item['id']]);
                    if($selected_notes) {
                        $this->_exclude_keys($selected_notes,$this->note->exclude_keys,true);
                        $this->_sql_to_vue($selected_notes,$this->note->keys,true);
                        $item['selectedNotes'] = $selected_notes;
                    }

                   /*  if($prices) {
                        $temp = [];
                        foreach ($prices as $price) {
                            unset($price['purchasePrice']);
                            $temp[] = $price;
                        }
                        $prices = $temp;
                    } */
                    //$item['prices'] = $prices;
                    $result['items'][] = $item;
                }
            }

            _model('pos/pos_model', 'pos');

            $result['payments'] = [];

            $payments = $this->_get_order_payments(['order_id'=>$result['id']]);
            if($payments) {
                $result['payments'] = $payments;
            }

            $result['split'] = false;
            if($result['split_type'] != 'none') {
                $this->split->order_by('id','ASC');
                $split_invoices = $this->split->search(['order_id'=>$id]);

                if($split_invoices) {

                    foreach ($split_invoices as $invoice) {

                        /*$invoice_id = $invoice['id'];

                        $invoice_items = $this->split_item->search(['split_id'=>$invoice_id]);

                        $temp = [];
                        foreach ($invoice_items as $invoice_item) {
                            $invoice_item['title'] = (@$item_titles[$invoice_item['order_item_id']])?$item_titles[$invoice_item['order_item_id']]:'';
                            $temp[] = $invoice_item;
                        }
                        $invoice_items = $temp;

                        $this->_exclude_keys($invoice_items,$this->split_item->exclude_keys,true);
                        $this->_sql_to_vue($invoice_items,$this->split_item->keys,true);

                        $invoice['items'] = $invoice_items;

                        $split_payments = $this->split_payment->search(['order_id'=>$id]);
                        if($split_payments) {
                            $invoice_payment_ids = array_column(array_values(array_filter($split_payments,function($single) use ($invoice_id) {
                                return $invoice_id == $single['split_id'];
                            })),'payment_id');
                            $invoice_payments = [];
                            foreach ($result['payments'] as $payment) {
                                if(in_array($payment['id'],$invoice_payment_ids)) {
                                    $invoice_payments[] = $payment;
                                }
                            }
                            $invoice['payments'] = $invoice_payments;
                        }

                        $this->_exclude_keys($invoice,$this->split->exclude_keys);
                        $this->_sql_to_vue($invoice,$this->split->keys);*/

                        $params = [
                            'split'         =>  $invoice,
                            'item_titles'   =>  $item_titles,
                            'payments'      =>  $result['payments']
                        ];

                        $invoice = $this->_get_split($params);

                        $result['split'][] = $invoice;
                    }
                }
                //$result['payments'] = [];
            }

            foreach ($order_keys as $new=>$old) {
                change_array_key($old,$new,$result);
            }
            return $result;

        }else{

            return false;

        }

    }

    public function _get_order_payments($params) {

        $order_id = $params['order_id'];

        $filters = [];
        $filters['filter'] = ['status' => 1];
        $payment_methods = _get_module('core/payment_methods', '_search', $filters);
        $payments = _get_module('orders/payments', '_get_order_payments', ['order_id' => $order_id]);

        $result = false;
        if ($payments) {
            $temp = [];
            foreach ($payments as $payment) {
                $method_id = $payment['paymentMethodId'];
                $payment_method = array_values(array_filter($payment_methods, function ($single) use ($method_id) {
                    return $single['id'] == $method_id;
                }));
                $payment['cash'] = @$payment_method[0]['is_cash'] === '1';
                $payment_method_name = (@$payment_method[0]['title']) ? $payment_method[0]['title'] : '';
                $payment['paymentMethodName'] = $payment_method_name;
                unset($payment['tip']);
                $temp[] = $payment;
            }
            $result = $temp;
        }
        return $result;

    }

    public function _get_split($params) {

        _model('order_item','order_item');
        _model('split','split');
        _model('split_item','split_item');
        _model('split_payment','split_payment');

        if(@$params['split']) {
            $split = $params['split'];
        }else{
            $split_id = $params['split_id'];
            $split = $this->split->single(['id'=>$split_id]);
        }

        $item_titles = (@$params['item_titles'])?$params['item_titles']:[];
        $payments = (@$params['payments'])?$params['payments']:[];

        if($split) {

            $split_id = $split['id'];
            $order_id = $split['order_id'];

            $this->db->order_by('id','ASC');
            $invoice_items = $this->split_item->search(['split_id' => $split_id]);

            $temp = [];
            foreach ($invoice_items as $invoice_item) {
                if($item_titles) {
                    $invoice_item['title'] = (@$item_titles[$invoice_item['order_item_id']]) ? $item_titles[$invoice_item['order_item_id']] : '';
                }else{
                    $order_item = $this->order_item->single(['id'=>$invoice_item['order_item_id']]);
                    $invoice_item['title'] = (@$order_item['title']) ? $order_item['title'] : '';
                }

                $temp[] = $invoice_item;
            }
            $invoice_items = $temp;

            $this->_exclude_keys($invoice_items, $this->split_item->exclude_keys, true);
            $this->_sql_to_vue($invoice_items, $this->split_item->keys, true);

            $split['items'] = $invoice_items;

            $split_payments = $this->split_payment->search(['order_id' => $order_id]);
            if ($split_payments) {
                $invoice_payment_ids = array_column(array_values(array_filter($split_payments, function ($single) use ($split_id) {
                    return $split_id == $single['split_id'];
                })), 'payment_id');
                $invoice_payments = [];
                foreach ($payments as $payment) {
                    if (in_array($payment['id'], $invoice_payment_ids)) {
                        $invoice_payments[] = $payment;
                    }
                }
                $split['payments'] = $invoice_payments;
            }

            $this->_exclude_keys($split, $this->split->exclude_keys);
            $this->_sql_to_vue($split, $this->split->keys);

            return $split;
        }
    }

    public function _order_sources($params) {
        _model('order_source','source');

        $result = $this->source->search();
        if(@$params['exclude']) {
            $this->_exclude_keys($result,$this->source->exclude_keys,true);
        }
        if(@$params['convert']) {
            $this->_sql_to_vue($result,$this->source->keys,true);
        }
        return $result;
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

        $file_name = strtolower('so-'.$id.'-'.date('M').date('Y').'.pdf');
        $upload_path = _get_config('pdf_path') . 'so/';

        if(!file_exists($upload_path)) {
            mkdir($upload_path,0777,true);
        }

        if(!file_exists($upload_path.$file_name) || $force==true) {

            $so = $this->_single($params);

            //$status = ($so['orderStatus']=='Received')?'Received':false;

            $watermark_text = '';//($watermark_enabled)?$status:false;

            _vars('obj',$so);

            $pdf_data = _view('order_pdf');

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

            $result = $this->_single(['id'=>$id]);

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
        _model('orders/payment_refund','payment_refund');
        _model('orders/clover_payment','clover_payment');
        $id = _input('id');

        if($id) {

            $result = $this->_single(['id'=>$id]);

            if ($result) {

                $result['invoiceUrl'] = null;

                $result['pdfUrl'] = base_url("orders/pdf/$id");
                $order_id = $result['id'];
                $refund_payments = $this->payment_refund->search(['order_id' => $order_id]);
                $refundPayments = [];
                if($refund_payments){
                    foreach ($refund_payments as &$r){
                        $refundPayments[] = [
                            'paymentMethodId' => $r['payment_method_id'],
                            'amount'          => $r['amount'],
                        ];
                    }
                }
                $result['refundPayments'] = $refundPayments;
                $result['cloverPayment'] = [];
                if(ALLOW_CLOVER_PAYMENT){
                    $cloverPayments = $this->clover_payment->single(['order_id'=>$order_id]);
                  /*   if(@$cloverPayments){
                        foreach($cloverPayments as &$c){
                            $c['row'] = unserialize(($c['row']));
                        }
                    } */
                    if(@$cloverPayments){
                        $cloverPayments['row'] = unserialize(($cloverPayments['row']));
                        $result['cloverPayment'] = $cloverPayments;
                    }

                }
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

    private function _generate_invoice($order_id) {

        _model('orders/order_item','order_item');

        _model('orders/invoice','invoice');
        _model('orders/invoice_item','invoice_item');

        $so = $this->{$this->model}->single(['id'=>$order_id]);
        $so_items = $this->order_item->search(['order_id'=>$order_id]);

        if($so && $so_items) {

            $invoice_data = $so;

            $invoice_data['order_id'] = $invoice_data['id'];
            unset($invoice_data['id']);
            $invoice_data['added'] = sql_now_datetime();
            $invoice_data['order_no'] = _get_ref('inv');

            $this->invoice->insert($invoice_data);
            $invoice_id = $this->invoice->insert_id();

            if($invoice_id) {

                _update_ref('inv');

                foreach ($so_items as $item) {

                    $item['added'] = sql_now_datetime();
                    $item['inv_id'] = $invoice_id;
                    unset($item['order_id']);
                    $this->invoice_item->insert($item);

                }

            }
            return true;

        }
        return false;

    }

    public function _install() {
        return true;
    }

    public function _uninstall() {
        return true;
    }

    public function _order_get(){
        _model('order_item','order_item');

        $id = _input('id');
        $orders = [];
        if($id){
            $params = [];
            $params['filter'] = [
                'customer_id'=>  $id,
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

            _response_data('orders',$orders);
            return true;
        }
    }
    public function csv(){}

    protected function _load_files() {

        if(_get_method()=='index') {
            _load_plugin(['moment','dt']);
        }

        if(_get_method()=='add' || _get_method()=='edit') {
            _load_plugin(['moment','vue_multiselect','datepicker']);
            _page_script_override('orders/orders-form');
        }

    }
}
