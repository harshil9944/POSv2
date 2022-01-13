<?php
defined('BASEPATH') OR exit('No direct script access allowed');
include_once __DIR__ . '../../config/constants.php';
class Template extends MY_Model
{
    public $keys;
    public $exclude_keys;
    public function __construct() {
        $this->table = TEMPLATES_TABLE;

        //Key = Vue and Value = MySQL
        $this->keys = [
            'id'=>'id',
            'title'=>'title',
            'added'=>'added'
        ];
        $this->exclude_keys = ['added'];
    }

}
