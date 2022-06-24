<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once __DIR__ . '../../config/constants.php';
class Forgot extends MY_Model
{
    public function __construct() {
        $this->table = USER_FORGOT_TABLE;
    }

}
