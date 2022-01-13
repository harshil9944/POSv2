<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once __DIR__ . '../../config/constants.php';
class Notification extends MY_Model
{
    public function __construct() {
        parent::__construct();
        $this->table = NOTIFICATION_TABLE;
        $this->migration_table = NOTIFICATION_MIGRATION_TABLE;
        //Key = Vue and Value = MySQL
        $this->keys = [
            'id'=>'id',
            'typeId'=>'type_id',
            'userId'=>'user_id',
            'url'=>'url',
            'title'=>'title',
            'data'=>'data',
            'readAt'=>'read_at',
            'deleted'=>'deleted',
            'deletedAt'=>'deleted_at',
            'added'=>'added',
        ];
        $this->exclude_keys = [];
    }
}
