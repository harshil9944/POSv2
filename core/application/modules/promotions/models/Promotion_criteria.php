<?php
defined('BASEPATH') OR exit('No direct script access allowed');
include_once __DIR__ . '../../config/constants.php';
class Promotion_criteria extends MY_Model
{
    public $keys;
    public $exclude_keys;
    public function __construct() {
        $this->table = PROMOTIONS_CRITERIA_TABLE;

        //Key = Vue and Value = MySQL
        $this->keys = [
            'id'=>'id',
            'promotionId'=>'promotion_id',
            'productType'=>'product_type',
            'qtyType'=>'qty_type',
            'minQty'=>'min_qty',
            'maxQty'=>'max_qty',
            'minAmount'=>'min_amount',
            'updated'=>'updated',
            'added'=>'added'
        ];
        $this->exclude_keys = ['added','updated'];
    }

}
