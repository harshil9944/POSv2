<?php
defined('BASEPATH') OR exit('No direct script access allowed');
include_once __DIR__ . '../../config/constants.php';
class Pos_register_session extends MY_Model
{
    public function __construct() {
        $this->table = POS_REGISTER_SESSION_TABLE;
        $this->keys = [
            'id'            =>  'id',
            'sessionId'    =>  'session_id',
            'registerId'    =>  'register_id',
            'openingUserId' =>  'opening_user_id',
            'closingUserId' =>  'closing_user_id',
            'openingDate'   =>  'opening_date',
            'closingDate'   =>  'closing_date',
            'openingCash'   =>  'opening_cash',
            'closingCash'   =>  'closing_cash',
            'takeOut'       =>  'take_out',
            'openingNote'   =>  'opening_note',
            'closingNote'   =>  'closing_note',
            'status'        =>  'status',
            'added'         =>  'added',
        ];
        $this->exclude_keys = ['added'];
    }

    public function get_open($params = []) {
        $session_id = $params['session_id'];
        $register_id = $params['register_id'];

        $filter = [
            'session_id'       =>  $session_id,
            'register_id'       =>  $register_id,
            'status'            =>  'Open',
            'closing_user_id'   =>  null,
            'closing_date'      =>  null

        ];
        $this->order_by('id','DESC');
        $result = $this->single($filter);

        return ($result)?$result:false;

    }
}
