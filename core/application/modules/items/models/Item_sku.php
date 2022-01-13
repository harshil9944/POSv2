<?php
defined('BASEPATH') OR exit('No direct script access allowed');
include_once __DIR__ . '../../config/constants.php';
class Item_sku extends MY_Model
{
    public $keys;
    public $exclude_keys;
    public function __construct() {
        $this->table = ITEM_SKU_TABLE;

        //Key = Vue and Value = MySQL
        $this->keys = [
            'skuId'=>'id',
            'itemId'=>'item_id',
            'isVeg'=>'is_veg',
            'name'=>'title',
            'sku'=>'sku',
            'upc'=>'upc',
            'ean'=>'ean',
            'weight'=>'weight',
            'reorderLevel'=>'reorder_level'
        ];
        $this->exclude_keys = ['item_id'];
    }

    public function get_list() {

        $default_filter = [];
        $result = $this->search($default_filter);
        return $result;

    }

    public function sku_exists($sku) {
        $filter = ['sku'=>$sku];
        if($this->single($filter)) {
            return true;
        }else{
            return false;
        }
    }
}
