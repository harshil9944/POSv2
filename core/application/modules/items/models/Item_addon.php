<?php
defined('BASEPATH') OR exit('No direct script access allowed');
include_once __DIR__ . '../../config/constants.php';
class Item_addon extends MY_Model
{
    public $keys;
    public $exclude_keys;
    public function __construct() {
        $this->table = ITEM_TABLE;

        //Key = Vue and Value = MySQL
        $this->keys = [
            'id'=>'id',
            'code'=>'code',
            'type'=>'type',
            'categoryId'=>'category_id',
            'name'=>'title',
            'taxable'=>'taxable',
            'printLocation'=>'print_location',
            'unit'=>'unit_id',
            'purchaseUnit'=>'purchase_unit_id',
            'saleUnit'=>'sale_unit_id',
            'manufacturer'=>'manufacturer',
            'preferredVendor'=>'vendor_id',
            'image'=>'image',
            'icon'=>'icon',
            'hasSpiceLevel'=>'has_spice_level',
            'status'=>'status',
            'createdBy'=>'created_by',
            'added'=>'added'
        ];
        $this->exclude_keys = ['added'];
    }

    public function get_list($params=[]) {

       

        $filter = array_merge($params['filter'],['is_addon'=>1]);
        $limit = (isset($params['limit']) && is_int($params['limit']))?$params['limit']:_get_setting('global_limit',50);
        $offset = (isset($params['offset']) && is_int($params['offset']))?$params['offset']:0;
        $orders = (isset($params['orders']) && is_array($params['orders']))?$params['orders']:[];
        $and_likes = (isset($params['and_likes']) && is_array($params['and_likes']))?$params['and_likes']:[];
        $or_likes = (isset($params['or_likes']) && is_array($params['or_likes']))?$params['or_likes']:[];
        /*$exclude = false;
        $convert = false;
        if(isset($params['exclude'])) {
            if(is_array($params['exclude'])) {
                $exclude = $params['exclude'];
            }elseif ($params['exclude']===true) {
                $exclude = $this->exclude_keys;
            }
        }
        if(isset($params['convert'])) {
            if(is_array($params['convert'])) {
                $convert = $params['convert'];
            }elseif ($params['convert']===true) {
                $convert = $this->keys;
            }
        }*/
        if($and_likes) {
            foreach ($and_likes as $field=>$value) {
                $this->like($field,$value);
            }
        }
        if($or_likes) {
            foreach ($or_likes as $field=>$value) {
                $this->or_like($field,$value);
            }
        }
        if($orders) {
            foreach ($orders as $order) {
                $this->order_by($order['order_by'],$order['order']);
            }
        }
      

        return $this->search($filter,$limit,$offset);

    }

    public function get_list_count($params=[]) {


        $filter = array_merge($params['filter'],['is_addon'=>1]);
        $and_likes = (isset($params['and_likes']) && is_array($params['and_likes']))?$params['and_likes']:[];
        $or_likes = (isset($params['or_likes']) && is_array($params['or_likes']))?$params['or_likes']:[];

        if($and_likes) {
            foreach ($and_likes as $field=>$value) {
                $this->like($field,$value);
            }
        }
        if($or_likes) {
            foreach ($or_likes as $field=>$value) {
                $this->or_like($field,$value);
            }
        }
        $this->select('COUNT(*) as total_rows');

        return $this->single($filter);
    }

}
