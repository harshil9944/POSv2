<?php
defined('BASEPATH') OR exit('No direct script access allowed');
include_once __DIR__ . '../../config/constants.php';
class Batch extends MY_Model
{
    public $keys;
    public $exclude_keys;
    public function __construct() {
        $this->table = WAREHOUSE_BATCH_TABLE;

        //Key = Vue and Value = MySQL
        $this->keys = [
            'id'=>'id',
            'shelfId'=>'shelf_id',
            'title'=>'title',
            'manufBatchNo'=>'manufacturer_batch_no',
            'manufDate'=>'manufactured_date',
            'expiryDate'=>'expiry_date',
            'status'=>'status',
            'added'=>'added'
        ];
        $this->exclude_keys = ['added'];
    }

}
