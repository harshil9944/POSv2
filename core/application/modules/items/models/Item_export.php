<?php
defined('BASEPATH') OR exit('No direct script access allowed');
include_once __DIR__ . '../../config/constants.php';
class Item_export extends MY_Model
{
    public function __construct() {
        $this->table = ITEM_CATEGORY_TABLE;

        //Key = Vue and Value = MySQL
        $this->keys = [
            'id'=>'id',
            'title'=>'title',
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
