<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Notifications extends MY_Controller {

    public $module = 'notifications';
    public $model = 'notification';
    public $singular = 'Notification';
    public $plural = 'Notifications';
    public $language = 'notifications/notifications';
    public $edit_form = '';
    public function __construct()
    {
        parent::__construct();
        _model($this->model);
        $params = [
            'migration_path' => NOTIFICATION_MIGRATION_PATH,
            'migration_table' => NOTIFICATION_MIGRATION_TABLE
        ];
        $this->init_migration($params);
    }

    public function index() {

        _library('table');

        $filters = [];
        $filters['filter'] = ['deleted'=>0];
        $filters['orders'] = [['order_by'=>'added','order'=>'DESC']];
        $collection = $this->_search($filters);
        $body = [];
        if($collection) {
            foreach ($collection as $single) {
                $body[] = [
                    ['data'=>$single['id'],'class'=>'text-center w-50p'],
                    $single['title'],
                    $single['data'],
                    ($single['read_at'])?true:false
                ];
            }
        }

        $heading = [
            ['data'=>'ID','class'=>'text-center w-70p'],
            'Title',
            'Description',
            'Read',
        ];

        _vars('table_heading',$heading);
        _vars('table_body',$body);
        $table = _view(DATA_TABLE_PATH);

        $page = [
            'singular'  =>  $this->singular,
            'plural'    =>  $this->plural,
            'add_url'   =>  false,
            'table'     =>  $table
        ];
        _vars('page_data',$page);

        _set_additional_component(LIST_XTEMPLATE_PATH,'outside');

        _set_page_heading($this->plural);
        _set_layout(LIST_VIEW_PATH);
    }

    function _populate_get() {
        $notifications = [];
        if($this->is_installed) {
            $params = [];
            $notifications = $this->_get_top_list($params);
        }
        _response_data('notifications',$notifications);
        return true;
    }

    public function _web_push_post() {

        _model('webpush');

        $obj = _input('obj');

        if($obj) {
            $json = json_decode(stripslashes($obj),true);

            $insert = [
                'endpoint'          =>  $json['endpoint'],
                'expiration_time'   =>  sql_now_datetime(),
                'key_p256db'        =>  (@$json['keys']['p256dh'])?$json['keys']['p256dh']:null,
                'key_auth'          =>  (@$json['keys']['auth'])?$json['keys']['auth']:null,
                'status'            =>  1,
                'added'             =>  sql_now_datetime()
            ];
            if($this->webpush->insert($insert)) {
                $id = $this->webpush->insert_id();
                _response_data('id',$id);
                return true;
            }

        }

        return false;
    }

    public function _broadcast($params=[]) {

        if($params) {
            _library('webpush_lib');

            $this->webpush_lib->broadcast_notifications($params);
        }
    }

    public function _set($params=[]) {
        $data = $params['data'];
        //TODO Notification types to be added
        $data['type_id'] = 0;
        //TODO Specific user notification if user_id is set
        //Set to zero means show to all users or and read by any user
        $data['user_id'] = (isset($data['user_id']) && $data['user_id'])?$data['user_id']:0;
        $data['added'] = sql_now_datetime();
        $data['read_at'] = null;

        if($this->{$this->model}->insert($data)){
            return $this->{$this->model}->insert_id();
        }
        return false;
    }

    public function _get_top_list($params=[]) {

        $filters = [];
        $filters['filter'] = (isset($params['filter']) && is_array($params['filter']))?$params['filter']:[];
        $filters['limit'] = _get_setting('top_notifications_limit',10);
        $filters['convert'] = true;
        $filters['exclude'] = true;
        $filters['orders'] = [['order_by'=>'added','order'=>'DESC']];

        $notifications = $this->_search($filters);

        if($notifications) {
            $temp = [];
            foreach ($notifications as $notification) {
                $notification['addedAgo'] = time_elapsed_string($notification['added']);
                $temp[] = $notification;
            }
            $notifications = $temp;
        }
        return $notifications;
    }

    public function _set_read($params=[]) {
        $id = $params['id'];
        $update = ['read_at'=>sql_now_datetime()];
        $filter = ['id'=>$id];

        return $this->{$this->model}->update($update,$filter);
    }

    public function _get_menu() {

        $menus = [];
        $menus[] = array(
            'id'        => 'menu-notifications',
            'class'     => '',
            'icon'      => 'si si-calculator',
            'group'     => 'module',
            'name'      => $this->plural,
            'path'      => 'notifications',
            'module'    => 'notifications',
            'priority'  => 1,
            'children'  => []
        );

        return $menus;

    }

    public function _install() {
        return true;
    }

    public function _uninstall() {
        return true;
    }
}
