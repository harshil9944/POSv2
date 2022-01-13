<?php
defined('BASEPATH') OR exit('No direct script access allowed');
include_once __DIR__ . '../../config/constants.php';
class Printer extends MY_Model
{
    public $keys;
    public $exclude_keys;
    public function __construct() {
        $this->table = PRINTERS_TABLE;
        $this->migration_table = PRINTERS_MIGRATION_TABLE;

        //Key = Vue and Value = MySQL
        $this->keys = [
            'id'=>'id',
            'title'=>'title',
            'status'=>'status',
            'type'=>'type',
            'address' =>'address',
            'openCashDrawer'=>'open_cash_drawer',
            'added'=>'added'
        ];
        $this->exclude_keys = ['added'];
    }
    public function get_list() {

        $default_filter = [];
        $this->order_by('title');
        $result = $this->search($default_filter);
        return $result;

    }

}
