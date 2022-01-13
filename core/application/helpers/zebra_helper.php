<?php
if ( ! function_exists('_resize_crop_center')) {
    function _resize_crop_center($params=[]) {
        $obj =& get_instance();
        $obj->load->library('zebra');
        return $obj->zebra->resize_crop_center($params);
    }
}
if( ! function_exists('_get_image_cache_name')){
    function _get_image_cache_name($file_name,$thumb_marker){
        $ext=substr($file_name,strrpos($file_name,'.'));
        $file_name=substr($file_name,0,strrpos($file_name,'.'));
        return $file_name.$thumb_marker.$ext;
    }
}
