<?php
defined('BASEPATH') OR exit('No direct script access allowed');
include_once __DIR__ . '../../config/constants.php';
class Order_source extends MY_Model
{
    public $keys;
    public $exclude_keys;
    public function __construct() {

        $this->table = ORDER_SOURCE_TABLE;

        //Key = Vue and Value = MySQL
        $this->keys = [
            'id'=>'id',
            'title'=>'title',
            'printLabel'=>'print_label',
            'showInSummary'=>'show_in_summary',
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
