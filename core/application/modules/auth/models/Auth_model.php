<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once __DIR__ . '../../config/constants.php';
class Auth_model extends MY_Model
{
    public function __construct() {
        $this->table = USER_TABLE;
    }

    public function login($email,$password) {

        _helper('password');

        $filters = [
            'email'     =>  $email,
            'password'  =>  hash_password($password)
        ];

        $this->left_join(USER_GROUP_TABLE,USER_GROUP_TABLE.'.id='.USER_TABLE.'.group_id');
        $this->select(USER_TABLE.'.*,'.USER_GROUP_TABLE.'.rank,'.USER_GROUP_TABLE.'.panel_login');
        $user = $this->single($filters);

        if($user) {
            return $user;
        }else{
            return false;
        }

    }

    public function get_redirect($user) {

        $redirect = _get_session('redirect',false);
        _set_session('redirect',NULL);
        if(!$redirect) {
            if ($user['default_page']) {
                $redirect = $user['default_page'];
            } else {
                _model('users/group');

                $group = $this->group->single(['id' => $user['group_id']]);

                if ($group) {
                    $redirect = $group['default_page'];
                } else {
                    return false;
                }
            }
        }
        return $redirect;
    }

    public function get_forgot_user($email) {

        $filters = [
            'email'     =>  $email,
        ];

        $user = $this->single($filters);

        if($user) {
            return $user;
        }else{
            return false;
        }

    }

    public function generate_forgot($user_id) {

        $code = get_guid();

        $data = [
            'user_id'   =>  $user_id,
            'code'      =>  $code,
            'added'     =>  sql_now_datetime()
        ];

        $updated = $this->replace($data,USER_FORGOT_TABLE);
        if($updated) {
            return $code;
        }else{
            return false;
        }

    }
}
