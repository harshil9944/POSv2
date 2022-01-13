<?php
defined('BASEPATH') OR exit('No direct script access allowed');
include_once __DIR__ . '../../config/constants.php';
class Item_note extends MY_Model
{
    public $keys;
    public $exclude_keys;
    public function __construct() {
        $this->table = ITEM_NOTES_TABLE;

        //Key = Vue and Value = MySQL
        $this->keys = [
            'id'=>'id',
            'itemId'=>'item_id',
            'title'=>'title',
            'added'=>'added'
        ];
        $this->exclude_keys = ['added'];
    }
}
