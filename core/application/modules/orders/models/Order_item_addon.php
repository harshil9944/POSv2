<?php
defined('BASEPATH') OR exit('No direct script access allowed');
include_once __DIR__ . '../../config/constants.php';
class Order_item_addon extends MY_Model
{
    public $keys;
    public $exclude_keys;
    public function __construct() {
        $this->table = ORDER_ITEM_ADDON_TABLE;

        //Key = Vue and Value = MySQL
        $this->keys = [
            'id'=>'id',
            'orderItemId'=>'order_item_id',
            'itemId'=>'item_id',
            'skuId'=>'sku_id',
            'addonItemId'=>'addon_item_id',
            'title'=>'title',
            'quantity'=>'quantity',
            'salePrice'=>'sale_price',
            'added'=>'added'
        ];
        $this->exclude_keys = ['added'];
    }
}
