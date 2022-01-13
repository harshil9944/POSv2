<?php
defined('BASEPATH') OR exit('No direct script access allowed');
include_once __DIR__ . '../../config/constants.php';
class Salutation extends MY_Model
{
    public function __construct() {
        $this->table = CONTACT_SALUTATION_TABLE;
    }

    public function get_list() {

        $default_filter = [];
        $result = $this->search($default_filter);
        return $result;

    }

    public function get_active_list() {

        $default_filter = [];
        $result = $this->search($default_filter);
        return $result;

    }

}
