<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once __DIR__ . '../../config/constants.php';
class Employee_register extends MY_Model
{
    public function __construct() {
        parent::__construct();
        $this->table = EMPLOYEE_TO_REGISTER_TABLE;

        //Key = Vue and Value = MySQL
        $this->keys = [
            'userId'=>'user_id',
            'registerId'=>'register_id',
            'added'=>'added'
        ];
        $this->exclude_keys = ['added'];
    }

}
