<?php
defined('BASEPATH') OR exit('No direct script access allowed');
include_once __DIR__ . '../../config/constants.php';
class Wapi_model extends MY_Model
{
    public function __construct() {
        $this->table = '';
        $this->migration_table = WAPI_MIGRATION_TABLE;
    }
}
