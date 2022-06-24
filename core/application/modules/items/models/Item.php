<?php
defined('BASEPATH') OR exit('No direct script access allowed');
include_once __DIR__ . '../../config/constants.php';
class Item extends MY_Model
{
    public $keys;
    public $exclude_keys;
    public function __construct() {
        $this->table = ITEM_TABLE;
        $this->migration_table = ITEM_MIGRATION_TABLE;

        //Key = Vue and Value = MySQL
        $this->keys = [
            'id'=>'id',
            'code'=>'code',
            'type'=>'type',
            'parent'=>'parent',
            'outletId'=>'outlet_id',
            'categoryId'=>'category_id',
            'title'=>'title',
            'taxable'=>'taxable',
            'printLocation'=>'print_location',
            'unitId'=>'unit_id',
            'image'=>'image',
            'icon'=>'icon',
            'hasSpiceLevel'=>'has_spice_level',
            'isAddon'=>'is_addon',
            'isVeg'=>'is_veg',
            'rate'=>'rate',
            'posStatus'=>'pos_status',
            'webStatus'=>'web_status',
            'appStatus'=>'app_status',
            'createdBy'=>'created_by',
            'added'=>'added'
        ];
        $this->exclude_keys = ['added'];
    }

    public function get_list($params=[]) {


        $filter = array_merge($params['filter'],['is_addon'=>0]);
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
       $this->select('*,(SELECT COUNT(*) FROM itm_item `ii1` WHERE `ii1`.parent=`itm_item`.id AND `ii1`.type="variant") AS `variant_count`');

        return $this->search($filter,$limit,$offset);

    }

    public function get_list_count($params=[]) {


        $filter = array_merge($params['filter'],['is_addon'=>0]);
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

   
    public function getVariants($itemId){
        return $this->search(['parent'=>$itemId,'type'=>'variant'])??[];
    }

    public function hasVariants($itemId){
      return count($this->getVariants($itemId)) > 0 ? true:false;
    }

    public function getItemTypeAttribute($itemId){
        return $this->hasVariants($itemId) ? 'With Variants':'Single';
    }

    public function getParentItem($itemId){
        return $this->single(['id'=>$itemId,'type'=>'product']);
    }

    public function getOptionalVariants($itemId){
        return $this->search(['parent'=>$itemId,'type'=>'optional','is_addon'=>1])??[];
    }
    public function hasOptionalVariants($itemId){
      return count($this->getVariants($itemId)) > 0 ? true:false;
    }
}
