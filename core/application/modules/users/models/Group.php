<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include_once __DIR__ . '../../config/constants.php';
class Group extends MY_Model
{
    public function __construct() {
        $this->table = USER_GROUP_TABLE;
    }

    public function get_list() {
        $this->order_by('id');
        return $this->search();
    }

    public function get_permissions($group_id='') {

        _model(['route','permission']);

        if(!$group_id) {
            $group_id = _get_session('group_id');
        }
        $group = $this->single(['id'=>$group_id]);
        $result = [];
        $result['page'] = [];
        $result['sub'] = [];
        if($group) {
            $is_admin = ($group['is_admin']) ? true : false;

            if ($is_admin) {
                $permissions = $this->route->get_list();
                $sub_permissions = $this->permission->search(['status'=>1]);
                if ($permissions) {
                    foreach ($permissions as $permission) {
                        $result['page'][] = $permission['slug'];
                    }
                }
                if ($sub_permissions) {
                    foreach ($sub_permissions as $permission) {
                        $result['sub'][] = $permission['code'];
                    }
                }

            } else {
                $permissions = unserialize($group['permissions']);
                $sub_permissions = unserialize($group['sub_permissions']);
                $result['page'] = ($permissions) ? $permissions : [];
                $result['sub'] = ($sub_permissions) ? $sub_permissions : [];
            }
        }
        return $result;
    }

}
