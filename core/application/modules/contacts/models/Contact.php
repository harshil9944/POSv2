<?php
defined('BASEPATH') OR exit('No direct script access allowed');
include_once __DIR__ . '../../config/constants.php';
class Contact extends MY_Model
{
    public function __construct() {
        $this->migration_table = CONTACT_MIGRATION_TABLE;
    }
}
