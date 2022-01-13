<?php
defined('BASEPATH') OR exit('No direct script access allowed');
include_once __DIR__ . '../../config/constants.php';
class Customer_address extends MY_Model
{
    public $keys;
    public $exclude_keys;
    public function __construct() {
        $this->table = CONTACT_CUSTOMER_ADDRESS_TABLE;

        $this->keys = [
            'id'=>'id',
            'title'=>'title',
            'customerId'=>'customer_id',
            'address1'=>'address1',
            'address2'=>'address2',
            'cityId'=>'city_id',
            'stateId'=>'state_id',
            'zipCode'=>'zip_code',
            'countryId'=>'country_id',
            'added'=>'added'

        ];
        $this->exclude_keys = ['added'];
    }

    public function get_list() {

        $default_filter = [];
        $result = $this->search($default_filter);
        return $result;

    }

}
