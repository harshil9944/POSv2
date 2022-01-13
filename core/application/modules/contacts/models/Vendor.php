<?php
defined('BASEPATH') OR exit('No direct script access allowed');
include_once __DIR__ . '../../config/constants.php';
class Vendor extends MY_Model
{
    public $keys;
    public $exclude_keys;
    public function __construct() {
        $this->table = CONTACT_VENDOR_TABLE;

        $this->keys = [
            'id'=>'id',
            'vendorId'=>'vendor_id',
            'salutation'=>'salutation_id',
            'firstName'=>'first_name',
            'lastName'=>'last_name',
            'companyName'=>'company_name',
            'displayName'=>'display_name',
            'email'=>'email',
            'additionalEmails'=>'additional_emails',
            'phone'=>'phone',
            'baddressId'=>'baddress_id',
            'saddressId'=>'saddress_id',
            'designation'=>'designation',
            'department'=>'department',
            'currencyId'=>'currency_id',
            'currencyCode'=>'currency_code',
            'priceListId'=>'price_list_id',
            'paymentTerm'=>'payment_terms',
            'notes'=>'notes',
            'status'=>'status',
            'added'=>'added'
        ];
        $this->exclude_keys = ['status','deleted','added','baddress_id','saddress_id'];
    }

    public function get_list() {

        $default_filter = ['deleted'=>0];
        $result = $this->search($default_filter);
        return $result;

    }

}
