<?php
defined('BASEPATH') OR exit('No direct script access allowed');
include_once __DIR__ . '../../config/constants.php';
class Shelf extends MY_Model
{
    public $keys;
    public $exclude_keys;
    public function __construct() {
        $this->table = WAREHOUSE_SHELF_TABLE;

        //Key = Vue and Value = MySQL
        $this->keys = [
            'id'=>'id',
            'warehouseId'=>'warehouse_id',
            'title'=>'title',
            'status'=>'status',
            'added'=>'added'
        ];
        $this->exclude_keys = ['added'];
    }

}
