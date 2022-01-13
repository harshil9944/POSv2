<?php
defined('BASEPATH') OR exit('No direct script access allowed');
include_once __DIR__ . '../../config/constants.php';
class Item_addon extends MY_Model
{
    public $keys;
    public $exclude_keys;
    public function __construct() {
        $this->table = ITEM_ADDON_TABLE;

        //Key = Vue and Value = MySQL
        $this->keys = [
            'id'=>'id',
            'itemId'=>'item_id',
            'skuId'=>'sku_id',
            'addonItemId'=>'addon_item_id',
            'title'=>'title',
            'salePrice'=>'sale_price',
            'added'=>'added'
        ];
        $this->exclude_keys = ['added'];
    }
}
