<?php
defined('BASEPATH') OR exit('No direct script access allowed');
include_once __DIR__ . '../../config/constants.php';
class Item_inventory extends MY_Model
{
    public $keys;
    public function __construct() {
        $this->table = ITEM_INVENTORY_TABLE;

        //Key = Vue and Value = MySQL
        $this->keys = [
            'order_id'=>'order_id',
            'item_id'=>'item_id',
            'sku_id'=>'sku_id',
            'warehouse_id'=>'warehouse_id',
            'reason'=>'reason',
            'date'=>'date',
            'openingStock'=>'quantity',
            'rate'=>'rate',
            'openingStockValue'=>'amount',
            'created_by'=>'created_by',
            'added'=>'added'
        ];
    }

    public function update_opening($data) {
        $filter = [
            'item_id'       =>  $data['item_id'],
            'sku_id'        =>  $data['sku_id'],
            'warehouse_id'  =>  $data['warehouse_id'],
            'reason'        =>  'opening'
        ];
        if(!$this->single($filter)){
            $this->insert($data);
        }else{
            $this->update($data,$filter);
        }
    }

    public function update_inventory($data) {
        $filter = [
            'item_id'       =>  $data['item_id'],
            'sku_id'        =>  $data['sku_id'],
            'warehouse_id'  =>  $data['warehouse_id'],
            'reason'        =>  $data['reason']
        ];
        if($data['order_id']) {
            $filter['order_id'] = $data['order_id'];
        }

        if(!$this->single($filter)) {
            $this->insert($data);
        }else{
            $this->update($data,$filter);
        }
    }

    public function get_list() {

        $default_filter = [];
        $result = $this->search($default_filter);
        return $result;

    }

}
