<?php defined('BASEPATH') OR exit('No direct script access allowed.');
if( ! function_exists('get_sum')) {
    function get_sum($a = 0,$b=0) {
        return $a + $b;
    }
}
if( ! function_exists('get_multiply')) {
    function get_multiply($a = 0,$b=0) {
        return $a * $b;
    }
}
if( ! function_exists('get_divide')) {
    function get_divide($a=0,$b=1) {
        return $a / $b;
    }
}
if( ! function_exists('get_subtract')) {
    function get_subtract($a =0 ,$b = 0) {
        return $a - $b;
    }
}
if( ! function_exists('get_percentage')) {
    function get_percentage($a = 0,$b = 0) {
        return $a * $b / 100;
    }
}
if( ! function_exists('get_area_of_circle')) {
    function get_area_of_circle($number = 1, $type = 'radius') {
        if(!is_numeric($number) || $number <= 0)
            return 0;
        switch($type) {
            case 'radius':
            default:
              $radius = $number;

              break;

            case 'diameter':
              $radius = $number / 2;

              break;
          }


        return pi() * $radius * $radius;

    }
}
if( ! function_exists('get_area_of_rectangle')) {
    function get_area_of_rectangle($length = 1, $width = 1) {
        if(!is_numeric($length) || $length <= 0)
            return 0;
        if(!is_numeric($width) || $width <= 0)
            return 0;
        return $length * $width;
    }
}
if( ! function_exists('get_area_of_triangle')) {
    function get_area_of_triangle($height = 1, $base = 1) {
        if(!is_numeric($height) || $height <= 0)
            return 0;
        if(!is_numeric($base) || $base <= 0)
            return 0;
        return $height * $base / 2;
    }
}
if( ! function_exists('get_area_of_square')) {
    function get_area_of_square($side = 0) {
        if(!is_numeric($side) || $side <= 0)
            return 0;
        return $side * $side;
    }
}
if( ! function_exists('get_area_of_circle')) {
    function get_area_of_circle($number = 1, $type = 'radius') {
        if(!is_numeric($number) || $number <= 0)
            return 0;
        switch($type) {
            case 'radius':
            default:
              $radius = $number;

              break;

            case 'diameter':
              $radius = $number / 2;

              break;
          }


        return pi() * $radius * $radius;

    }
}
if( ! function_exists('get_area_of_rectangle')) {
    function get_area_of_rectangle($length = 1, $width = 1) {
        if(!is_numeric($length) || $length <= 0)
            return 0;
        if(!is_numeric($width) || $width <= 0)
            return 0;
        return $length * $width;
    }
}
if( ! function_exists('get_area_of_triangle')) {
    function get_area_of_triangle($height = 1, $base = 1) {
        if(!is_numeric($height) || $height <= 0)
            return 0;
        if(!is_numeric($base) || $base <= 0)
            return 0;
        return $height * $base / 2;
    }
}
