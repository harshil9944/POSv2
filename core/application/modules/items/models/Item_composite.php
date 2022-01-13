<?php
defined('BASEPATH') OR exit('No direct script access allowed');
include_once __DIR__ . '../../config/constants.php';
class Item_composite extends MY_Model
{
    public function __construct() {
        $this->table = ITEM_TABLE;
        $this->migration_table = ITEM_MIGRATION_TABLE;
    }

    public function get_list() {

        $default_filter = [];
        $result = $this->search($default_filter);
        return $result;

    }

}
