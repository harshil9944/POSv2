<?php
defined('BASEPATH') OR exit('No direct script access allowed');
include_once __DIR__ . '../../config/constants.php';
class Item_stock extends MY_Model
{
    public $keys;
    public function __construct() {
        $this->table = ITEM_STOCK_TABLE;

        //Key = Vue and Value = MySQL
        $this->keys = [
            'item_id'=>'item_id',
            'sku_id'=>'sku_id',
            'warehouse_id'=>'warehouse_id',
            'quantity_in_hand'=>'quantity_in_hand',
            'updated'=>'updated'
        ];
    }

    public function update_soh($item_id,$sku_id,$warehouse_id) {

        //$sql = "SELECT SUM(ii.quantity) as stock_on_hand FROM itm_inventory ii WHERE ii.item_id=$item_id AND ii.sku_id=$sku_id AND ii.warehouse_id=$warehouse_id";

        $filter = [
            'item_id'       =>  $item_id,
            'sku_id'        =>  $sku_id,
            'warehouse_id'  =>  $warehouse_id
        ];
        $this->select_sum('quantity','stock_on_hand');
        $result = $this->single($filter,ITEM_INVENTORY_TABLE);

        $stock_on_hand = 0.00;
        if($result) {
            $stock_on_hand = $result['stock_on_hand'];
        }
        $data = [
            'item_id'       =>  $item_id,
            'sku_id'        =>  $sku_id,
            'warehouse_id'  =>  $warehouse_id,
            'on_hand'       =>  $stock_on_hand,
            'modified'      =>  sql_now_datetime()
        ];
        if(!$this->single($filter)) {
            $this->insert($data);
        }else{
            $this->update($data,$filter);
        }
        return true;
    }

    public function get_list() {

        $default_filter = [];
        $result = $this->search($default_filter);
        return $result;

    }

}
