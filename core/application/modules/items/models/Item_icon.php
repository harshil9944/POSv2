<?php
defined('BASEPATH') OR exit('No direct script access allowed');
include_once __DIR__ . '../../config/constants.php';
class Item_icon extends MY_Model
{
    public function __construct() {
        $this->table = ITEM_ICON_TABLE;

        //Key = Vue and Value = MySQL
        $this->keys = [
            'id'=>'id',
            'title'=>'title',
            'added'=>'added'
        ];
        $this->exclude_keys = ['added'];

    }

}
