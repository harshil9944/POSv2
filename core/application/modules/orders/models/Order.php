<?php
defined('BASEPATH') OR exit('No direct script access allowed');
include_once __DIR__ . '../../config/constants.php';
class Order extends MY_Model
{
    public $keys;
    public $exclude_keys;
    public function __construct() {
        $this->table = ORDER_TABLE;
        $this->migration_table = ORDER_MIGRATION_TABLE;

        //Key = Vue and Value = MySQL
        $this->keys = [
            'id'=>'id',
            'type'=>'type',
            'splitType'=>'split_type',
            'customerId'=>'customer_id',
            'sourceId'=>'source_id',
            'billingName'=>'billing_name',
            'address1'=>'address1',
            'address2'=>'address2',
            'city'=>'city',
            'state'=>'state',
            'country'=>'country',
            'zipCode'=>'zip_code',
            'warehouseId'=>'warehouse_id',
            'registerId'=>'register_id',
            'sessionId'=>'session_id',
            'salesPersonId'=>'salesperson_id',
            'orderNo'=>'order_no',
            'sessionOrderNo'=>'session_order_no',
            'extOrderNo'=>'ext_order_no',
            'referenceNo'=>'reference_no',
            'date'=>'order_date',
            'expectedDeliveryDate'=>'expected_delivery_date',
            'gratuityTotal'=>'gratuity_total',
            'gratuityRate'=>'gratuity_rate',
            'seatUsed'=>'seat_used',
            'subTotal'=>'sub_total',
            'taxTotal'=>'tax_total',
            'taxRate'=>'tax_rate',
            'change'=>'change',
            'tip'=>'tip',
            'promotionTotal'=>'promotion_total',
            'discount'=>'discount',
            'discountValue'=>'discount_value',
            'discountType'=>'discount_type',
            'freightTotal'=>'freight_total',
            'dutyTotal'=>'duty_total',
            'adjustment'=>'adjustment',
            'grandTotal'=>'grand_total',
            'orderStatus'=>'order_status',
            'cancelled'=>'cancelled',
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
