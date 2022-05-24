<?php
defined('BASEPATH') OR exit('No direct script access allowed');
include_once __DIR__ . '../../config/constants.php';
class Promotion_reward_product extends MY_Model
{
    public $keys;
    public $exclude_keys;
    public function __construct() {
        $this->table = PROMOTIONS_REW_PRODUCT_TABLE;

        //Key = Vue and Value = MySQL
        $this->keys = [
            'id'=>'id',
            'rewardId'=>'reward_id',
            'promotionId'=>'promotion_id',
            'itemId'=>'item_id',
            'type'=>'type',
            'updated'=>'updated',
            'added'=>'added'
        ];
        $this->exclude_keys = ['added','updated'];
    }

}
