<?php
defined('BASEPATH') OR exit('No direct script access allowed');
include_once __DIR__ . '../../config/constants.php';
class Split_item extends MY_Model
{
    public $keys;
    public $exclude_keys;
    public function __construct() {
        $this->table = ORDER_SPLIT_ITEM_TABLE;

        //Key = Vue and Value = MySQL
        $this->keys = [
            'id'=>'id',
            'splitId'=>'split_id',
            'orderId'=>'order_id',
            'orderItemId'=>'order_item_id',
            'quantity'=>'quantity',
            'rate'=>'rate',
            'amount'=>'amount',
            'added'=>'added',
        ];
        $this->exclude_keys = ['added'];
    }

}
