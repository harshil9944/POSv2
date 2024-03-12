<?php
defined('BASEPATH') OR exit('No direct script access allowed');
include_once __DIR__ . '../../config/constants.php';
class Email_queue extends MY_Model
{
    public $keys;
    public $exclude_keys;
    public function __construct() {
        $this->table = ORDER_EMAIL_QUEUE;

        $this->keys = [
            'id'=>'id',
            'orderId'=>'order_id',
            'added'=>'added',
        ];
        $this->exclude_keys = [];
    }

}
