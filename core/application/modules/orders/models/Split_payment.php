<?php
defined('BASEPATH') OR exit('No direct script access allowed');
include_once __DIR__ . '../../config/constants.php';
class Split_payment extends MY_Model
{
    public $keys;
    public $exclude_keys;
    public function __construct() {
        $this->table = ORDER_SPLIT_PAYMENT_TABLE;

        //Key = Vue and Value = MySQL
        $this->keys = [
            'id'=>'id',
            'splitId'=>'split_id',
            'orderId'=>'order_id',
            'paymentId'=>'payment_id',
        ];
        $this->exclude_keys = [];
    }

}
