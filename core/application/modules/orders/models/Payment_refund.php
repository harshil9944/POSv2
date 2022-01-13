<?php
defined('BASEPATH') OR exit('No direct script access allowed');
include_once __DIR__ . '../../config/constants.php';
include_once __DIR__ . '../../config/constants.php';
class Payment_refund extends MY_Model
{
    public $keys;
    public $exclude_keys;
    public function __construct() {
        $this->table = ORDER_PAYMENT_REFUND_TABLE;

        //Key = Vue and Value = MySQL
        $this->keys = [
            'id'=>'id',
            'orderId'=>'order_id',
            'paymentId'=>'payment_id',
            'paymentMethodId'=>'payment_method_id',
            'amount'=>'amount',
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
