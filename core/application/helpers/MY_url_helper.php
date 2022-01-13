<?php defined('BASEPATH') OR exit('No direct script access allowed.');
if ( ! function_exists('asset_url'))
{
    function asset_url($string='')
    {
        return get_instance()->config->item('asset_url') . $string;
    }
}
