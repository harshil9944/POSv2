<?php
defined('BASEPATH') OR exit('No direct script access allowed');
include_once __DIR__ . '../../config/constants.php';
class Payment_method extends MY_Model
{
    public function __construct() {
        $this->table = SYS_PAYMENT_METHOD_TABLE;

        //Key = Vue and Value = MySQL
        $this->keys = [
            'id'=>'id',
            'code'=>'code',
            'type'=>'type',
            'autoDiscountValue'=>'auto_discount_value',
            'posEnabled'=>'pos_enabled',
            'webEnabled'=>'web_enabled',
            'appEnabled'=>'app_enabled',
            'isCash'=>'is_cash',
            'autofillAmount'=>'autofill_amount',
            'title'=>'title',
            'status'=>'status',
            'added'=>'added'
        ];
    }

    public function get_list() {

        $default_filter = [];
        $result = $this->search($default_filter);
        return $result;

    }

}
