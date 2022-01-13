<?php
defined('BASEPATH') OR exit('No direct script access allowed');
include_once __DIR__ . '../../config/constants.php';
class State extends MY_Model
{
    public function __construct() {
        $this->table = SYS_STATE_TABLE;
    }

    public function get_list() {

        $default_filter = [];
        $this->order_by('name');
        $result = $this->search($default_filter);
        return $result;

    }

}
