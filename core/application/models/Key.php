<?php
defined('BASEPATH') OR exit('No direct script access allowed');
include_once __DIR__ . '../../config/constants.php';
class Key extends MY_Model
{
    public function __construct() {
        $this->table = AJAX_KEY_TABLE;
    }

    public function generate() {

        $key = $this->generate_key();
        $user_id = _get_session('user_id',0);

        if($user_id!=0) {

            $existing = $this->single(['user_id'=>$user_id]);

            if($existing) {
                return $existing['key'];
            }else{

                $insert = [
                    'user_id' => $user_id,
                    'key' => $key,
                    'level' => 10,
                    'ip_addresses' => (_input_valid_ip(_input_client_ip()))?_input_client_ip():null,
                    'date_created' => sql_now_datetime()
                ];

                $this->clear($user_id);

                if ($this->insert($insert)) {
                    return $key;
                } else {
                    return false;
                }
            }
        }else{
            return false;
        }

    }

    public function clear($user_id) {
        $this->delete(['user_id' => $user_id]);
    }

    public function get($user_id) {

        $result = $this->single(['user_id'=>$user_id]);
        if($result) {
            return $result['key'];
        }else{
            return '';
        }
    }

    private function generate_key() {

        do {
            $key = get_guid();
            $result = $this->search(['key'=>$key]);
            $key = (!$result)?$key:false;
        }while($key==false);

        return $key;
    }
}
