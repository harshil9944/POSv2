<?php
/**
 * Created by PhpStorm.
 * User: chint
 * Date: 06-03-2019
 * Time: 18:57
 */

class Auth
{
    public function login($username,$password) {
        return true;
        /*$obj =& get_instance();
        _model(array('customer','key'));

        $user_id = $obj->customer->login($username,$password);
        _vars('customer_id',$user_id);
        $api_key_variable = _get_config('rest_key_name');
        $key_name = 'HTTP_' . strtoupper(str_replace('-', '_', $api_key_variable));

        $key = _input_server($key_name);

        if($user_id && $key) {

            if($obj->key->verify($key,$user_id)) {
                return true;
            }else{
                return $this->validate_web_auth_user($username,$password);
            }

        }else{
            return $this->validate_web_auth_user($username,$password);
        }*/

    }

}
