<?php
defined('BASEPATH') OR exit('No direct script access allowed');
include_once __DIR__ . '../../config/constants.php';
class Order_address extends MY_Model
{
    public $keys;
    public $exclude_keys;
    public function __construct() {

        $this->table = ORDER_ADDRESS_TABLE;

        //Key = Vue and Value = MySQL
        $this->keys = [
            'id'=>'id',
            'orderId'=>'order_id',
            'address1'=>'address1',
            'address2'=>'address2',
            'city'=>'city',
            'state'=>'state',
            'country'=>'country',
            'zipCode'=>'zip_code',
            'added'=>'added'
        ];
        $this->exclude_keys = ['added'];
    }

}
