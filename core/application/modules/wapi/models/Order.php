<?php
defined('BASEPATH') OR exit('No direct script access allowed');
include_once __DIR__ . '../../config/constants.php';
class Order extends MY_Model
{
    public $keys;
    public $exclude_keys;
    public function __construct() {

        $this->table = ORDER_TABLE;

        //Key = Vue and Value = MySQL
        $this->keys = [
            'id'=>'id',
            'type'=>'type',
            'customerId'=>'customer_id',
            'sourceId'=>'source_id',
            'billingName'=>'billing_name',
            'billingAddress1'=>'billing_address1',
            'billingAddress2'=>'billing_address2',
            'billingCity'=>'billing_city',
            'billingState'=>'billing_state',
            'billingCountry'=>'billing_country',
            'billingZipCode'=>'billing_zip_code',
            'shippingName'=>'shipping_name',
            'shippingAddress1'=>'shipping_address1',
            'shippingAddress2'=>'shipping_address2',
            'shippingCity'=>'shipping_city',
            'shippingState'=>'shipping_state',
            'shippingCountry'=>'shipping_country',
            'shippingZipCode'=>'shipping_zip_code',
            'warehouseId'=>'warehouse_id',
            'registerId'=>'register_id',
            'sessionId'=>'session_id',
            'employeeId'=>'employee_id',
            'orderNo'=>'order_no',
            'sessionOrderNo'=>'session_order_no',
            'referenceNo'=>'reference_no',
            'date'=>'order_date',
            'subTotal'=>'sub_total',
            'taxTotal'=>'tax_total',
            'taxRate'=>'tax_rate',
            'change'=>'change',
            'tip'=>'tip',
            'discount'=>'discount',
            'discountValue'=>'discount_value',
            'discountType'=>'discount_type',
            'freightTotal'=>'freight_total',
            'dutyTotal'=>'duty_total',
            'adjustment'=>'adjustment',
            'grandTotal'=>'grand_total',
            'orderStatus'=>'order_status',
            'notes'=>'notes'
        ];
        $this->exclude_keys = ['added'];
    }

    public function get_list() {

        $default_filter = [];
        $result = $this->search($default_filter);
        return $result;

    }

}
