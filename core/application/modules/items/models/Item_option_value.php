<?php
defined('BASEPATH') OR exit('No direct script access allowed');
include_once __DIR__ . '../../config/constants.php';
class Item_option_value extends MY_Model
{
    public function __construct() {
        $this->table = OPTION_VALUE_TABLE;

        //Key = Vue and Value = MySQL
        $this->keys = [
            'id'=>'id',
            'itemId'=>'item_id',
            'optionId'=>'option_id',
            'text'=>'title'
        ];
        $this->exclude_keys = [];

    }

    public function get_list() {

        $default_filter = [];
        $this->order_by('title');
        $result = $this->search($default_filter);
        return $result;

    }
}
