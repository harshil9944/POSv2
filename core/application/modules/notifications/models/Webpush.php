<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once __DIR__ . '../../config/constants.php';
class Webpush extends MY_Model
{
    public function __construct() {
        parent::__construct();
        $this->table = NOTIFICATION_WEBPUSH_TABLE;
        //Key = Vue and Value = MySQL
        $this->keys = [
            'id'=>'id',
            'endpoint'=>'endpoint',
            'key_p256db'=>'key_p256db',
            'key_auth'=>'key_auth',
            'expiration_time'=>'expiration_time',
            'status'=>'status',
            'added'=>'added',
        ];
        $this->exclude_keys = [];
    }
}
