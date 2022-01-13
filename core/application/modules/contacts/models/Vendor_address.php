<?php
defined('BASEPATH') OR exit('No direct script access allowed');
include_once __DIR__ . '../../config/constants.php';
class Vendor_address extends MY_Model
{
    public $keys;
    public $exclude_keys;
    public function __construct() {
        $this->table = CONTACT_VENDOR_ADDRESS_TABLE;

        $this->keys = [
            'zipCode'=>'zip_code',
        ];
        $this->exclude_keys = ['added'];
    }

    public function get_list() {

        $default_filter = [];
        $result = $this->search($default_filter);
        return $result;

    }

}
