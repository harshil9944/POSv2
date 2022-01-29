<?php
defined('BASEPATH') OR exit('No direct script access allowed');
include_once __DIR__ . '../../config/constants.php';
class Register extends MY_Model
{
    public $keys;
    public $exclude_keys;
    public function __construct() {
        $this->table = REGISTER_TABLE;
        $this->migration_table = REGISTER_MIGRATION_TABLE;

        //Key = Vue and Value = MySQL
        $this->keys = [
            'id'=>'id',
            'code'=>'code',
            'outletId'=>'outlet_id',
            'title'=>'title',
            'primary'=>'primary',
            'key'=>'key',
            'type'=>'type',
            'status'=>'status',
            'added'=>'added'
        ];
        $this->exclude_keys = ['added'];
    }

    public function get_list() {

        $default_filter = [];
        $result = $this->search($default_filter);
        return $result;

    }

}
