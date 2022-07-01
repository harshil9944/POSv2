<?php
defined('BASEPATH') OR exit('No direct script access allowed');
include_once __DIR__ . '../../config/constants.php';
class Order_item extends MY_Model
{
    public $keys;
    public $exclude_keys;
    public function __construct() {

        $this->table = ORDER_ITEM_TABLE;

        //Key = Vue and Value = MySQL
        $this->keys = [
            'id'=>'id',
            'type'=>'type',
            'partNo'=>'part_no',
            'printedQty'=>'printed_qty',
            'printLocation'=>'print_location',
            'orderId'=>'order_id',
            'itemId'=>'item_id',
            'parentId'=>'parent_id',
            'taxable'=>'taxable',
            'unitId'=>'unit_id',
            'title'=>'title',
            'quantity'=>'quantity',
            'lastModifyQty'=>'last_modify_qty',
            'rate'=>'rate',
            'amount'=>'amount',
            'orderItemNotes'=>'notes',
            'hasSpiceLevel'=>'has_spice_level',
            'spiceLevel'=>'spice_level',
            'added'=>'added'
        ];
        $this->exclude_keys = ['added'];
    }

}
