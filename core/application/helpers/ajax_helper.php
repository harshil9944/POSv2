<?php
if ( ! function_exists('_response_data')){
    function _response_data($key,$value){
        $ajax_data = _get_var('ajax_response_data');
        $ajax_data[$key] = $value;
        _vars('ajax_response_data',$ajax_data);
    }
}
if ( ! function_exists('_get_response_data')){
    function _get_response_data($key=''){
        $ajax_data = _get_var('ajax_response_data');
        if($key!='' && isset($ajax_data[$key])) {
            return $ajax_data[$key];
        }else{
            return $ajax_data;
        }
    }
}