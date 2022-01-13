<?php
defined('BASEPATH') OR exit('No direct script access allowed');
include_once __DIR__ . '../../config/constants.php';
class Item_feature_value extends MY_Model
{
    public function __construct() {
        $this->table = FEATURE_VALUE_TABLE;

        //Key = Vue and Value = MySQL
        $this->keys = [
            'id'=>'id',
            'itemId'=>'item_id',
            'featureId'=>'feature_id',
            'title'=>'title'
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
