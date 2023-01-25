<?php
defined('BASEPATH') OR exit('No direct script access allowed');
include_once __DIR__ . '../../config/constants.php';
class Promotion extends MY_Model
{
    public $keys;
    public $exclude_keys;
    public function __construct() {
        $this->table = PROMOTIONS_TABLE;
        $this->migration_table = PROMOTIONS_MIGRATION_TABLE;

        //Key = Vue and Value = MySQL
        $this->keys = [
            'id'=>'id',
            'title'=>'title',
            'description'=>'description',
            'offerCriteria'=>'offer_criteria',
            'offerType'=>'offer_type',
            'startDate'=>'start_date',
            'endDate'=>'end_date',
            'startTime'=>'start_time',
            'endTime'=>'end_time',
            'offerDays'=>'offer_days',
            'offerCustomDays'=>'offer_custom_days',
            'customerType'=>'customer_type',
            'status'=>'status',
            'updated'=>'updated',
            'added'=>'added'
        ];
        $this->exclude_keys = ['added','updated'];
    }

}
