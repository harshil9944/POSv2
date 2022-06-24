<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Customers_api extends API_Controller {

    public $model = 'customer';
    public $module = 'contacts/customers_api';
    public $singular = 'Customer';
    public $plural = 'Customers';
    public function __construct()
    {
        parent::__construct();
        _model($this->model);
        _model(['forgot']);
    }

    public function _get_forgot_user($params) {
        $email = $params['email'];
        $filters = [
            'email'     =>  $email,
            'status'    =>  1
        ];

        $user = $this->{$this->model}->single($filters);
        if($user) {
            return $user;
        }else{
            return false;
        }

    }

    public function _generate_forgot($params) {

        $user_id = $params['user_id'];
        $code = get_guid();
        $data = [
            'user_id'   =>  $user_id,
            'code'      =>  $code,
            'created_at'     =>  sql_now_datetime(), 
            'updated_at'     =>  sql_now_datetime(),
            'deleted_at'     =>  sql_now_datetime(),
        ];

        $updated = $this->forgot->replace($data);
        if($updated) {
            return $code;
        }else{
            return false;
        }

    }

    public function _process_retrieve($params) {

        _helper('password');

        $code = $params['code'];
        $password = $params['password'];

        //Forgot Expiration Time in minutes
        $fet = _get_setting('forgot_expiration_time',1440);

        $filters = [
            'code'  =>  $code,
            //'NOW()<'=>  "DATE_ADD(added, INTERVAL $fet MINUTE)"
        ];

        //$query = "SELECT * FROM $forgot_table WHERE `code`=`$code`";

        $this->forgot->select("*,NOW()<DATE_ADD(added, INTERVAL $fet MINUTE) as is_valid_date");
        $forgot = $this->forgot->single($filters);

        if($forgot) {
            $is_valid_date = $forgot['is_valid_date'];
            $forgot_id = $forgot['id'];
            if($is_valid_date=='1') {
                $user_id = $forgot['user_id'];

                $update = [
                    'password'  =>  hash_password($password),
                ];
                $filter = ['id'=>$user_id];

                $this->{$this->model}->update($update,$filter);
                $return_value = true;
                /*dump_exit($this->{$this->model}->affected_rows());
                if($this->{$this->model}->affected_rows()) {
                    $return_value = true;
                }else{
                    _vars('retrieve_err_msg','There was a problem while performing your reset process. Please try again');
                    $return_value = false;
                }*/
                $this->forgot->delete(['id'=>$forgot_id]);
                return $return_value;

            }else{
                //Details found but expired
                _vars('retrieve_err_msg','Details not found or might have expired. Please try again');
                return false;
            }
        }else{
            //Details not found
            _vars('retrieve_err_msg','Details not found or might have expired. Please try again');
            return false;
        }

    }

    public function _is_available($params) {
        $type = $params['type'];
        $value = $params['value'];
        $allowed_availability = ['mobile','email'];
        if(in_array($type,$allowed_availability)) {
            $filter = [$type => $value];
            $customers = $this->{$this->model}->search($filter);
            return ($customers)?false:true;
        }
        return false;
    }
}
