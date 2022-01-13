<?php
defined('BASEPATH') OR exit('No direct script access allowed');
include_once __DIR__ . '../../config/constants.php';
class Item_price extends MY_Model
{
    public $keys;
    public $exclude_keys;
    public function __construct() {
        $this->table = ITEM_PRICE_TABLE;

        //Key = Vue and Value = MySQL
        $this->keys = [
            'itemId'=>'item_id',
            'skuId'=>'sku_id',
            'unitId'=>'unit_id',
            'conversionRate'=>'conversion_rate',
            'purchaseCurrency'=>'purchase_currency',
            'saleCurrency'=>'sale_currency',
            'purchasePrice'=>'purchase_price',
            'salePrice'=>'sale_price',
            'added'=>'added'
        ];
        $this->exclude_keys = ['added','purchase_currency','sale_currency'];
    }

    public function get_list() {

        $default_filter = [];
        $result = $this->search($default_filter);
        return $result;

    }

    public function get_sale_price($item_id,$sku_id,$unit_id) {
        return $this->get_price($item_id,$sku_id,$unit_id,'sale_price');
    }

    public function get_purchase_price($item_id,$sku_id,$unit_id) {
        return $this->get_price($item_id,$sku_id,$unit_id,'purchase_price');
    }

    public function get_price($item_id,$sku_id,$unit_id,$type) {
        $filter = [
            'item_id'   =>  $item_id,
            'sku_id'    =>  $sku_id,
            'unit_id'   =>  $unit_id
        ];
        $result = $this->single($filter);
        return ($result)?$result[$type]:0;
    }
}
