<?php
defined('BASEPATH') OR exit('No direct script access allowed');
include_once __DIR__ . '../../config/constants.php';
class Split extends MY_Model
{
    public $keys;
    public $exclude_keys;
    public function __construct() {
        $this->table = ORDER_SPLIT_TABLE;

        //Key = Vue and Value = MySQL
        $this->keys = [
            'id'=>'id',
            'title'=>'title',
            'orderNo'=>'order_no',
            'orderId'=>'order_id',
            'customerId'=>'customer_id',
            'billingName'=>'billing_name',
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
            'gratuityTotal'=>'gratuity_total',
            'gratuityRate'=>'gratuity_rate',
            'grandTotal'=>'grand_total',
            'added'=>'added'
        ];
        $this->exclude_keys = ['added'];
    }

}
