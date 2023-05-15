<?php
defined('BASEPATH') OR exit('No direct script access allowed');
include_once __DIR__ . '../../config/constants.php';
class Booking_status extends MY_Model
{
    public $keys;
    public $exclude_keys;
    public function __construct() {
        $this->table = BOOKINGS_STATUS_TABLE;

        //Key = Vue and Value = MySQL
        $this->keys = [
            'id'=>'id',
            'tableId'=>'table_id',
            'userId'=>'user_id',
            'sessionStart'=>'session_start',
            'sessionEnd'=>'session_end',
        ];
        $this->exclude_keys = [];
    }

    /*public function get_list($params=[]) {

        $allowed_groups = _get_setting('allowed_employee_groups',[]);

        if($allowed_groups) {

            $filter = $params['filter'];
            $limit = (isset($params['limit']) && is_int($params['limit'])) ? $params['limit'] : _get_setting('global_limit', 50);
            $offset = (isset($params['offset']) && is_int($params['offset'])) ? $params['offset'] : 0;
            $orders = (isset($params['orders']) && is_array($params['orders'])) ? $params['orders'] : [];
            $and_likes = (isset($params['and_likes']) && is_array($params['and_likes'])) ? $params['and_likes'] : [];
            $or_likes = (isset($params['or_likes']) && is_array($params['or_likes'])) ? $params['or_likes'] : [];
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
            /*if ($and_likes) {
                foreach ($and_likes as $field => $value) {
                    $this->like($field, $value);
                }
            }
            if ($or_likes) {
                foreach ($or_likes as $field => $value) {
                    $this->or_like($field, $value);
                }
            }
            if ($orders) {
                foreach ($orders as $order) {
                    $this->order_by($order['order_by'], $order['order']);
                }
            }

            $this->where_in('group_id', $allowed_groups);

            $this->select('*');

            return $this->search($filter, $limit, $offset);
        }
        return [];

    }

    public function get_list_count($params=[]) {

        $allowed_groups = _get_setting('allowed_employee_groups',[]);

        if($allowed_groups) {

            $filter = $params['filter'];
            $and_likes = (isset($params['and_likes']) && is_array($params['and_likes'])) ? $params['and_likes'] : [];
            $or_likes = (isset($params['or_likes']) && is_array($params['or_likes'])) ? $params['or_likes'] : [];

            if ($and_likes) {
                foreach ($and_likes as $field => $value) {
                    $this->like($field, $value);
                }
            }
            if ($or_likes) {
                foreach ($or_likes as $field => $value) {
                    $this->or_like($field, $value);
                }
            }

            $this->where_in('group_id', $allowed_groups);

            $this->select('COUNT(*) as total_rows');

            return $this->single($filter);
        }
        return [];
    }*/

}
