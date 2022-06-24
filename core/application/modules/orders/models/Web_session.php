<?php
defined('BASEPATH') OR exit('No direct script access allowed');
include_once __DIR__ . '../../config/constants.php';
class Web_session extends MY_Model
{
    public function __construct() {
        $this->table = WEB_SESSION_TABLE;
    }

    public function generate($user_id,$data=[]) {

        $key = $this->generate_key();

        if($user_id) {

            $existing = $this->single(['user_id'=>$user_id]);

            if($existing) {
                return $existing;
            }else{

                $insert = [
                    'user_id' => $user_id,
                    'key' => $key,
                    'ip_address' => (_input_valid_ip(_input_client_ip()))?_input_client_ip():null,
                    'timestamp' => time(),
                ];
                if($data) {
                    $insert['data'] = json_encode($data);
                }

                $this->clear($user_id);

                if ($this->insert($insert)) {
                    return $this->single(['user_id'=>$user_id]);
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
