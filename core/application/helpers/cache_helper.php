<?php
if ( ! function_exists('_set_cache')) {
    function _set_cache($key,$value,$time=86400) {
        $obj =& get_instance();
        return $obj->cache->file->save($key,$value,$time);
    }
}
if ( ! function_exists('_get_cache')) {
    function _get_cache($key) {
        $obj =& get_instance();
        return $obj->cache->file->get($key);
    }
}
if ( ! function_exists('_delete_cache')) {
    function _delete_cache($key) {
        $obj =& get_instance();
        return $obj->cache->file->delete($key);
    }
}
if ( ! function_exists('_clear_cache')) {
    function _clear_cache($module) {
        $function = "_clear_" . $module . "_cache";
        return $function();
    }
}
if ( ! function_exists('_clear_item_cache')) {
    function _clear_item_cache() {
        _delete_cache('web_items');
        _delete_cache('pos_pre_items');
        _delete_cache('pos_items');
        _delete_cache('web_categories');
        _delete_cache('pos_categories');
        _delete_cache('pos_pre_categories');
        _delete_cache('pos_icons');
    }
}