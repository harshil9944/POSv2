<?php
defined('BASEPATH') OR exit('No direct script access allowed');
include_once __DIR__ . '../../config/constants.php';
class Payment_description extends MY_Model
{
    public $keys;
    public $exclude_keys;
    public function __construct() {
        $this->table = ORDER_PAYMENT_DESC_TABLE;

        //Key = Vue and Value = MySQL
        $this->keys = [
            'id'=>'id',
            'paymentId'=>'payment_id',
            'transactionId'=>'transaction_id',
            'raw_data'=>'raw_data',
            'added'=>'added'
        ];
        $this->exclude_keys = ['added'];
    }
}
