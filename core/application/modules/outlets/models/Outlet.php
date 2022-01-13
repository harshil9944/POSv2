<?php
defined('BASEPATH') OR exit('No direct script access allowed');
include_once __DIR__ . '../../config/constants.php';
class Outlet extends MY_Model
{
    public $keys;
    public $exclude_keys;
    public function __construct() {
        $this->table = OUTLET_TABLE;
        $this->migration_table = OUTLET_MIGRATION_TABLE;

        //Key = Vue and Value = MySQL
        $this->keys = [
            'id'=>'id',
            'code'=>'code',
            'vendorId'=>'vendor_id',
            'customerId'=>'customer_id',
            'type'=>'type',
            'name'=>'title',
            'address1'=>'address_1',
            'address2'=>'address_2',
            'city'=>'city',
            'stateId'=>'state_id',
            'zipCode'=>'zipcode',
            'countryId'=>'country_id',
            'phone'=>'phone',
            'email'=>'email',
            'status'=>'status',
            'createdBy'=>'created_by',
            'added'=>'added'
        ];
        $this->exclude_keys = ['added','created_by'];
    }

    public function get_list() {

        $default_filter = ['type'=>'outlet'];
        $result = $this->search($default_filter);
        return $result;

    }

}
