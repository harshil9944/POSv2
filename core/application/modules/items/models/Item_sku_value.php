<?php
defined('BASEPATH') OR exit('No direct script access allowed');
include_once __DIR__ . '../../config/constants.php';
class Item_sku_value extends MY_Model
{
    public function __construct() {
        $this->table = SKU_VALUE_TABLE;

        //Key = Vue and Value = MySQL
        $this->keys = [
            'item_id'=>'item_id',
            'sku_id'=>'sku_id',
            'option_id'=>'option_id',
            'value_id'=>'value_id'
        ];
        $this->exclude_keys = [];

    }

    public function get_list() {

        $default_filter = [];
        $this->order_by('title');
        $result = $this->search($default_filter);
        return $result;

    }
}
