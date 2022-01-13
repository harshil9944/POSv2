<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Permission extends MY_Model
{
    public function __construct()
    {
        $this->table = PERMISSION_TABLE;
    }

}
