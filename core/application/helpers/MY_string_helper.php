<?php defined('BASEPATH') OR exit('No direct script access allowed.');
if ( ! function_exists('get_guid')){
    function get_guid(){
        mt_srand((double)microtime()*10000);//optional for php 4.2.0 and up.
        $charid = strtolower(md5(uniqid(rand(), true)));
        $hyphen = chr(45);// "-"
        $hyphen='';
        $chr_123 = chr(123);
        $chr_125 = chr(125);
        $chr_123='';
        $chr_125='';
        $uuid = $chr_123
            .substr($charid, 0, 8).$hyphen
            .substr($charid, 8, 4).$hyphen
            .substr($charid,12, 4).$hyphen
            .substr($charid,16, 4).$hyphen
            .substr($charid,20,12)
            .$chr_125;
        return $uuid;
    }
}
if ( ! function_exists('get_public_image_url')){
    function get_public_image_url($img){
        return 'uploads/'.$img;
    }
}
if ( ! function_exists('time_elapsed_string')){
    function time_elapsed_string($datetime, $full = false) {
        $now = new DateTime;
        $ago = new DateTime($datetime);

        if($now>$ago){
            $text = ' ago';
        }else{
            $text = ' left';
        }


        $diff = $now->diff($ago);

        $diff->w = floor($diff->d / 7);
        $diff->d -= $diff->w * 7;

        $string = array(
            'y' => 'year',
            'm' => 'month',
            'w' => 'week',
            'd' => 'day',
            'h' => 'hour',
            'i' => 'minute',
            's' => 'second',
        );
        foreach ($string as $k => &$v) {
            if ($diff->$k) {
                $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
            } else {
                unset($string[$k]);
            }
        }

        if (!$full) $string = array_slice($string, 0, 1);

        return $string ? implode(', ', $string) . $text : 'just now';
    }
}
if ( ! function_exists('sql_date_difference')){
    function sql_date_difference($datetime1, $datetime2,$format='i') {

        $datetime1 = new DateTime($datetime1);
        $datetime2 = new DateTime($datetime2);
        //$interval = $datetime1->diff($datetime2);

        //return $interval->{$format};

        return ($datetime1==$datetime2)?false:true;
    }
}
if ( ! function_exists('sql_date_smaller')){
    function sql_date_smaller($datetime1, $datetime2) {

        $datetime1 = new DateTime($datetime1);
        $datetime2 = new DateTime($datetime2);

        return ($datetime1<$datetime2)?true:false;
    }
}
if( ! function_exists('rename_resize_image')){
    function rename_resize_image($file_name,$thumb_marker){
        $ext=substr($file_name,strrpos($file_name,'.'));
        $file_name=substr($file_name,0,strrpos($file_name,'.'));
        return $file_name.$thumb_marker.$ext;
    }
}
if( ! function_exists('custom_date_format')) {
    function custom_date_format($date,$format="d/m/Y h:i A") {
        return date($format, strtotime($date));
    }
}
if( ! function_exists('get_age')) {
    function get_age($date) {
        $from = new DateTime($date);
        $to   = new DateTime('today');

        $diff = $from->diff($to);
        $year = $diff->y;
        if($year==0) {
            return $diff->m . 'm';
        }else{
            return $year;
        }
    }
}
if( ! function_exists('convert_to_mysql_datetime')) {
    function convert_to_mysql_datetime($string,$format) {
        $date = DateTime::createFromFormat($format, $string);
        $mysql_date_string = $date->format('Y-m-d H:i:s');
        return $mysql_date_string;
    }
}
if( ! function_exists('convert_to_mysql_date')) {
    function convert_to_mysql_date($string,$format="d/m/Y") {
        $date = DateTime::createFromFormat($format, $string);
        $mysql_date_string = $date->format('Y-m-d');
        return $mysql_date_string;
    }
}
if( ! function_exists('convert_to_mysql_time')) {
    function convert_to_mysql_time($string,$format="h:i A") {
        $date = DateTime::createFromFormat( $format, $string);
        $formatted = $date->format( 'H:i:s');
        return $formatted;
    }
}
if( ! function_exists('convert_from_mysql_time')) {
    function convert_from_mysql_time($string,$format="H:i:s") {
        $date = DateTime::createFromFormat( $format, $string);
        return $date->format( 'h:i A');
    }
}
if( ! function_exists('sql_now_datetime')) {
    function sql_now_datetime() {
        return date('Y-m-d H:i:s');
    }
}
if( ! function_exists('sql_now_date')) {
    function sql_now_date() {
        return date('Y-m-d');
    }
}
if( ! function_exists('custom_money_format')) {
    function custom_money_format($num,$sym='$ ') {
        $nums = explode(".",$num);
        if(count($nums)>2){
            return "0";
        }else{
            if(count($nums)==1){
                $nums[1]="00";
            }else{
                if(strlen($nums[1])==1){
                    $nums[1] = $nums[1].'0';
                }
            }
            $num = $nums[0];
            $explrestunits = "" ;
            if(strlen($num)>3){
                $lastthree = substr($num, strlen($num)-3, strlen($num));
                $restunits = substr($num, 0, strlen($num)-3);
                $restunits = (strlen($restunits)%2 == 1)?"0".$restunits:$restunits;
                $expunit = str_split($restunits, 2);
                for($i=0; $i<sizeof($expunit); $i++){

                    if($i==0)
                    {
                        $explrestunits .= (int)$expunit[$i].",";
                    }else{
                        $explrestunits .= $expunit[$i].",";
                    }
                }
                $thecash = $explrestunits.$lastthree;
            } else {
                $thecash = $num;
            }
            return $sym.$thecash.".".$nums[1];
        }
    }
}
if( ! function_exists('get_key_paths')) {
    function get_key_paths( array $tree, $glue = '-' ) {
        $paths = array();
        foreach ( $tree as $key => &$mixed ) {
            if ( is_array( $mixed ) ) {
                $results = get_key_paths( $mixed, $glue );
                foreach ( $results as $k => &$v ) {
                    //$paths[$key . $glue . $k] = $v;
                    $paths[ $key . $glue . $k ] = $v;
                }
                unset( $results );
            } else {
                $paths[ $key ] = $mixed;
            }
        }

        return $paths;
    }
}
if( ! function_exists('get_array_var')) {
    function get_array_var($array,$index,$text=false) {
        if ( isset( $array[$index] ) && $array[$index] ) {
            return $array[$index];
        } else {
            return $text;
        }
    }
}
if( ! function_exists('get_select_array')) {
    function get_select_array($data=array(),$id_field='id',$value_field='value',$include_select=true,$select_id='',$select_value='Select',$additional_fields=[]) {
        $result = [];
        if($include_select) {
            $result[] = [
                'id'    =>  $select_id,
                'value' =>  $select_value
            ];
        }
        if($data) {
            foreach($data as $row) {
                $temp = [
                    'id'    =>  $row[$id_field],
                    'value' =>  $row[$value_field]
                ];
                if(count($additional_fields)) {
                    foreach ($additional_fields as $field) {
                        if(isset($row[$field])) {
                            $temp[$field] = $row[$field];
                        }
                    }
                }
                $result[] = $temp;
            }
        }
        return $result;
    }
}
if( ! function_exists('get_status_array')) {
    function get_status_array($enable_value='Enabled',$disable_value='Disabled',$enable_id=1,$disable_id=0) {

        $result = [];
        $result[] = ['id'=>$enable_id,'value'=>$enable_value];
        $result[] = ['id'=>$disable_id,'value'=>$disable_value];

        return $result;
    }
}
if( ! function_exists('get_index_id_array')) {
    function get_index_id_array($array,$index_field,$multiple_indexes=false) {
        $temp = array();
        foreach ($array as $item) {
            if($multiple_indexes) {
                $temp[$item[$index_field]][] = $item;
            }else{
                $temp[$item[$index_field]] = $item;
            }
        }
        return $temp;
    }
}
if (! function_exists('filter_array_keys')) {
    function filter_array_keys($array,$exclude_array=[])
    {
        $result = $array;
        if(count($array)) {
            foreach ($array as $key=>$value) {
                if(in_array($key,$exclude_array)) {
                    unset($result[$key]);
                }
            }
        }
        return $result;
    }
}
if( ! function_exists('change_array_key')) {
    function change_array_key($old,$new,&$array) {
        if($old!=$new) {
            if(array_key_exists($old,$array)) {
                $value = $array[$old];
                $array[$new] = $value;
                unset($array[$old]);
            }
        }
    }
}
if( ! function_exists('sksort')) {
    function sksort(&$array, $subkey="id", $sort_ascending=false) {

            $temp_array = [];
            if (is_array($array) && count($array))
                $temp_array[key($array)] = array_shift($array);

            foreach($array as $key => $val){
                $offset = 0;
                $found = false;
                foreach($temp_array as $tmp_key => $tmp_val)
                {
                    if(!$found and strtolower($val[$subkey]) > strtolower($tmp_val[$subkey]))
                    {
                        $temp_array = array_merge(    (array)array_slice($temp_array,0,$offset),
                            array($key => $val),
                            array_slice($temp_array,$offset)
                        );
                        $found = true;
                    }
                    $offset++;
                }
                if(!$found) $temp_array = array_merge($temp_array, array($key => $val));
            }

            if ($sort_ascending) $array = array_reverse($temp_array);

            else $array = $temp_array;
        }
}
if( ! function_exists('convert_bool')) {
    function convert_bool($string) {
        return filter_var($string,FILTER_VALIDATE_BOOLEAN);
    }
}
if( ! function_exists('find_key')) {
    function find_key($keySearch,$array) {
        if($array) {
            foreach ($array as $key => $item) {
                if ($key == $keySearch) {
                    return true;
                } elseif (is_array($item) && find_key($keySearch, $item)) {
                    return true;
                }
            }
        }
        return false;
    }
}
if( ! function_exists('toKeyedRows')) {
    function toKeyedRows(array $rows, array $header) : array
    {
        array_walk($header,function($value,$key){ $value = $value?:$key; });

        array_walk(
            $rows,
            function(&$row)use($header)
            {
                $row = array_combine($header,$row);
            }
        );

        return $rows;
    }
}
if( ! function_exists('base64ToPNG')) {
    function base64ToPNG($code,$file,$replace=true) {

        $image_parts = explode(";base64,", $code);
        $image_base64 = base64_decode($image_parts[1]);

        if (strpos($file, '.png') == false) {
            $file .= '.png';
        }
        if(file_exists($file) && $replace) {
            unlink($file);
        }
        return file_put_contents($file, $image_base64);
    }
}

