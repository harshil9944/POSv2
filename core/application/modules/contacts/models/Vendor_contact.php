<?php
defined('BASEPATH') OR exit('No direct script access allowed');
include_once __DIR__ . '../../config/constants.php';
class Vendor_contact extends MY_Model
{
    public $keys;
    public $exclude_keys;
    public function __construct() {
        $this->table = CONTACT_VENDOR_ADDITIONAL_CONTACT_TABLE;

        $this->keys = [
            'id'=>'id',
            'contactId'=>'contact_id',
            'salutationId'=>'salutation_id',
            'firstName'=>'first_name',
            'lastName'=>'last_name',
            'email'=>'email',
            'phone'=>'phone',
            'departmentId'=>'department_id',
            'added'=>'added',
        ];
        $this->exclude_keys = ['added'];
    }

    public function get_list() {

        $default_filter = [];
        $result = $this->search($default_filter);
        return $result;

    }

}
