<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once __DIR__ . '../../config/constants.php';
class User extends MY_Model
{
    public function __construct() {
        $this->table = USER_TABLE;

        $this->keys = [
            'id'=>'id',
            'groupId'=>'group_id',
            'firstName'=>'first_name',
            'lastName'=>'last_name',
            'gender'=>'gender',
            'image'=>'image',
            'dob'=>'dob',
            'theme'=>'theme',
            'mobile'=>'mobile',
            'email'=>'email',
            'password'=>'password',
            'defaultPage'=>'default_page',
            'status'=>'status',
            'deleted'=>'deleted',
            'added'=>'added'
        ];
        $this->exclude_keys = ['added','deleted','password'];
    }
}
