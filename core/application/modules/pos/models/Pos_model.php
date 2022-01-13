<?php
defined('BASEPATH') OR exit('No direct script access allowed');
include_once __DIR__ . '../../config/constants.php';
class Pos_model extends MY_Model
{
    public function __construct() {
        $this->table = '';
        $this->migration_table = POS_MIGRATION_TABLE;
    }
}
