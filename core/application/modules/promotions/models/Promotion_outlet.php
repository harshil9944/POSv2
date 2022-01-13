<?php
defined('BASEPATH') OR exit('No direct script access allowed');
include_once __DIR__ . '../../config/constants.php';
class Promotion_outlet extends MY_Model
{
    public $keys;
    public $exclude_keys;
    public function __construct() {
        $this->table = PROMOTIONS_OUTLET_TABLE;

        //Key = Vue and Value = MySQL
        $this->keys = [
            'promotionId'=>'promotion_id',
            'outletId'=>'outlet_id',
        ];
        $this->exclude_keys = [];
    }

}
