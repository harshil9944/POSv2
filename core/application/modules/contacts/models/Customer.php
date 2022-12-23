<?php
defined('BASEPATH') OR exit('No direct script access allowed');
include_once __DIR__ . '../../config/constants.php';
class Customer extends MY_Model
{
    public $keys;
    public $exclude_keys;
    public function __construct() {
        $this->table = CONTACT_CUSTOMER_TABLE;

        $this->keys = [
            'id'=>'id',
            'customerId'=>'customer_id',
            'groupId'=>'group_id',
            'firstName'=>'first_name',
            'lastName'=>'last_name',
            'displayName'=>'display_name',
            'email'=>'email',
            'phone'=>'phone',
            'password'=>'password',
            'defaultAddressId'=>'default_address_id',
            'memberNumber'=>'member_number',
            'fullVaccinated'=>'full_vaccinated',
            'notes'=>'notes',
            'status'=>'status',
            'added'=>'added'
        ];
        $this->exclude_keys = ['status','added','password'];
    }

    public function get_list() {

        $default_filter = ['deleted'=>0];
        $result = $this->search($default_filter);
        return $result;

    }
    public function get_list_count($params=[]) {
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
        $this->select('COUNT(*) as total_rows');
        return $this->single($filter);
    }

}
