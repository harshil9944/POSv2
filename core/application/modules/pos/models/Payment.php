<?php
defined('BASEPATH') OR exit('No direct script access allowed');
include_once __DIR__ . '../../config/constants.php';
include_once __DIR__ . '../../config/constants.php';
class Payment extends MY_Model
{
    public $keys;
    public $exclude_keys;
    public function __construct() {
        $this->table = ORDER_PAYMENT_TABLE;

        //Key = Vue and Value = MySQL
        $this->keys = [
            'id'=>'id',
            'orderId'=>'order_id',
            'paymentMethodId'=>'payment_method_id',
            'customerId'=>'customer_id',
            'orderNo'=>'order_no',
            'referenceNo'=>'reference_no',
            'date'=>'payment_date',
            'amount'=>'amount',
            'notes'=>'notes',
            'added'=>'added'
        ];
        $this->exclude_keys = ['added'];
    }

    public function get_list() {

        $default_filter = [];
        $result = $this->search($default_filter);
        return $result;

    }

}
