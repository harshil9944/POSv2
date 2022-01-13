<?php
defined('BASEPATH') OR exit('No direct script access allowed');
include_once __DIR__ . '../../config/constants.php';
class Item_group extends MY_Model
{
    public function __construct() {
        _model('item');
        $this->table = ITEM_TABLE;

        $this->keys = $this->item->keys;
        $this->exclude_keys = $this->item->exclude_keys;

        /*//Key = Vue and Value = MySQL
        $this->keys = [
            'id'=>'id',
            'type'=>'type',
            'name'=>'title',
            'unit'=>'unit_id',
            'purchaseUnit'=>'purchase_unit_id',
            'saleUnit'=>'sale_unit_id',
            'manufacturer'=>'manufacturer',
            'preferredVendor'=>'vendor_id',
            'status'=>'status',
            'created_by'=>'created_by',
            'added'=>'added'
        ];
        $this->exclude_keys = ['added','created_by','status','vendor_id'];*/

    }

    public function get_list($params=[]) {

        $search_by_vendors = (isset($params['search_in_vendors']) && $params['search_in_vendors'])?true:false;
        if($search_by_vendors) {
            _model('contacts/vendor','vendor');
        }

        $filter = $params['filter'];
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
        $this->left_join(ITEM_SKU_TABLE, ITEM_TABLE.'.id='.ITEM_SKU_TABLE.'.item_id');
        if($search_by_vendors) {
            $this->left_join(CONTACT_VENDOR_TABLE,ITEM_TABLE.'.vendor_id='.CONTACT_VENDOR_TABLE.'.id');
        }
        $this->group_by(ITEM_TABLE.'.id');
        //$this->left_join(ITEM_STOCK_TABLE, ITEM_TABLE.'.id='.ITEM_STOCK_TABLE.'.item_id');
        $this->select('*,'. ITEM_TABLE.'.title as title,'. ITEM_TABLE.'.id as id,(SELECT SUM(`is`.on_hand) FROM itm_stock `is` WHERE `is`.item_id=`itm_item`.id AND `is`.sku_id=`itm_item_sku`.id) AS `on_hand`');

        return $this->search($filter,$limit,$offset);

    }

    public function get_list_count($params=[]) {

        $search_by_vendors = (isset($params['search_in_vendors']) && $params['search_in_vendors'])?true:false;
        if($search_by_vendors) {
            _model('contacts/vendor','vendor');
        }

        $filter = $params['filter'];
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

        $this->left_join(ITEM_SKU_TABLE, ITEM_TABLE.'.id='.ITEM_SKU_TABLE.'.item_id');
        if($search_by_vendors) {
            $this->left_join(CONTACT_VENDOR_TABLE,ITEM_TABLE.'.vendor_id='.CONTACT_VENDOR_TABLE.'.id');
        }
        $this->group_by(ITEM_TABLE.'.id');
        //$this->left_join(ITEM_STOCK_TABLE, ITEM_TABLE.'.id='.ITEM_STOCK_TABLE.'.item_id');
        $this->select('COUNT(*) as total_rows');

        return $this->single($filter);
    }
}
