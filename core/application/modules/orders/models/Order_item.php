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
            'skuId'=>'sku_id',
            'taxable'=>'taxable',
            'baseUnitId'=>'unit_id',
            'saleUnitId'=>'sale_unit_id',
            'unit'=>'unit',
            'saleUnit'=>'sale_unit',
            'title'=>'title',
            'sku'=>'sku',
            'unitQuantity'=>'unit_quantity',
            'unitRate'=>'unit_rate',
            'quantity'=>'quantity',
            'rate'=>'rate',
            'freightTotal'=>'freight_total',
            'dutyTotal'=>'duty_total',
            'amount'=>'amount',
            'orderItemNotes'=>'notes',
            'hasSpiceLevel'=>'has_spice_level',
            'spiceLevel'=>'spice_level',
            'added'=>'added'
        ];
        $this->exclude_keys = ['added'];
    }

}
