<?php
defined('BASEPATH') OR exit('No direct script access allowed');
include_once __DIR__ . '../../config/constants.php';
class Unit extends MY_Model
{
    public function __construct() {
        $this->table = SYS_UNIT_TABLE;

        //Key = Vue and Value = MySQL
        $this->keys = [
            'id'=>'id',
            'parent'=>'parent',
            'code'=>'code',
            'title'=>'title',
            'value'=>'value',
            'added'=>'added'
        ];
    }

    public function get_list() {

        $default_filter = [];
        $result = $this->search($default_filter);
        return $result;

    }

}
