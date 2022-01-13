<?php
defined('BASEPATH') OR exit('No direct script access allowed');
include_once __DIR__ . '../../config/constants.php';
class Currency extends MY_Model
{
    public function __construct() {
        $this->table = SYS_APP_CURRENCY_TABLE;
    }

    public function get_list() {

        $default_filter = [];
        $result = $this->search($default_filter);
        return $result;

    }

}
