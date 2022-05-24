<?php
defined( 'BASEPATH' ) OR exit( 'No direct script access allowed' );
include_once __DIR__ . '../../config/constants.php';
class Clover_payment extends MY_Model {
    public $keys;
    public $exclude_keys;
    public function __construct() {
        $this->table = ORDER_CLOVER_PAYMENT;

        //Key = Vue and Value = MySQL
        $this->keys = [
            'id'        => 'id',
            'orderId'   => 'order_id',
            'paymentId' => 'payment_id',
            'row'       => 'row',
            'added'     => 'added',
        ];
        $this->exclude_keys = [];
    }

}
