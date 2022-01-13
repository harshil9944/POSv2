<?php
defined('BASEPATH') OR exit('No direct script access allowed');
include_once __DIR__ . '../../config/constants.php';
class Customer_group extends MY_Model
{
    public $keys;
    public $exclude_keys;
    public function __construct() {
        $this->table = CONTACT_CUSTOMER_GROUPS_TABLE;

        $this->keys = [
            'id'=>'id',
            'title'=>'title',
            'posDiscount'=>'pos_discount',
            'webDiscount'=>'web_discount',
            'appDiscount'=>'app_discount',
            'status'=>'status',
            'added'=>'added'
        ];
        $this->exclude_keys = ['added'];
    }
}
