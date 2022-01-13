<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once __DIR__ . '../../config/constants.php';
class Employee_warehouse extends MY_Model
{
    public function __construct() {
        parent::__construct();
        $this->table = EMPLOYEE_TO_WAREHOUSE_TABLE;

        //Key = Vue and Value = MySQL
        $this->keys = [
            'userId'=>'user_id',
            'warehouseId'=>'warehouse_id',
            'added'=>'added'
        ];
        $this->exclude_keys = ['added'];
    }

}
