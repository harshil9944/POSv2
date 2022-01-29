<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once __DIR__ . '../../config/constants.php';
class Employee_shift extends MY_Model
{
    public function __construct() {
        $this->table = EMPLOYEE_SHIFT_TABLE;
        parent::__construct();

        //Key = Vue and Value = MySQL
        $this->keys = [
            'id'=>'id',
            'outletId'=>'outlet_id',
            'employeeID'=>'employee_id',
            'sessionId'=>'session_id',
            'openingRegisterId'=>'opening_register_id',
            'closeRegisterId'=>'close_register_id',
            'take_out'=>'take_out',
            'startShift'=>'start_shift',
            'endShift'=>'end_shift',
        ];
    }
}
