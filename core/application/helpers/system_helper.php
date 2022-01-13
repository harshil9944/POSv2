<?php

/*Loader Start*/
if (!function_exists('_model')) {
    function _model($model, $name = '', $db_conn = FALSE)
    {
        $obj =& get_instance();
        $obj->load->model($model, $name, $db_conn);
    }
}
if (!function_exists('_library')) {
    function _library($library, $params = NULL, $object_name = NULL)
    {
        $obj =& get_instance();
        $obj->load->library($library, $params, $object_name);
    }
}
if (!function_exists('_helper')) {
    function _helper($helpers = array())
    {
        $obj =& get_instance();
        $obj->load->helper($helpers);
    }
}
if (!function_exists('_language')) {
    function _language($files, $lang = '')
    {
        $obj =& get_instance();
        $obj->load->language($files, $lang);
    }
}
if (!function_exists('_line')) {
    function _line($line, $log_errors = TRUE)
    {
        $obj =& get_instance();
        return $obj->lang->line($line, $log_errors);
    }
}
if (!function_exists('_eline')) {
    function _eline($line, $log_errors = TRUE)
    {
        echo _line($line,$log_errors);
    }
}
if (!function_exists('_view')) {
    function _view($view, $vars = array(), $instance=NULL)
    {
        if($instance===NULL) {
            $obj =& get_instance();
        }else{
            $obj =& $instance;
        }
        return $obj->load->view($view, $vars, true);
    }
}
if (!function_exists('_eview')) {
    function _eview($view, $vars = array())
    {
        $obj =& get_instance();
        $result = $obj->load->view($view, $vars, true);
        echo $result;
    }
}
if (!function_exists('_ebase_url')) {
    function _ebase_url($uri = '', $protocol = NULL)
    {
        echo base_url($uri, $protocol);
    }
}
if (!function_exists('_easset_url')) {
    function _easset_url($string)
    {
        echo asset_url($string);
    }
}
if (!function_exists('_is_loaded')) {
    function _is_loaded($value)
    {
        $obj =& get_instance();
        return $obj->load->is_loaded($value);
    }
}
if (!function_exists('_vars')) {
    function _vars($vars, $val = '')
    {
        $obj =& get_instance();
        $obj->load->vars($vars, $val);
    }
}
if (!function_exists('_get_var')) {
    function _get_var($key, $false_value = NULL)
    {
        $obj =& get_instance();
        $var = $obj->load->get_var($key);
        if ($var != NULL) {
            return $var;
        } else {
            return $false_value;
        }
    }
}
if (!function_exists('_get_vars')) {
    function _get_vars($false_value = NULL)
    {
        $obj =& get_instance();
        $vars = $obj->load->get_vars();
        if ($vars != NULL) {
            return $vars;
        } else {
            return $false_value;
        }
    }
}
if ( ! function_exists('_get_module')){
    function _get_module($module,$method='',$params=[]) {
        if($method!='') {
            $module_array = explode('/',$module);
            $module_array[] = $method;
            $module = implode('/',$module_array);
        }

        $old_module = _get_module_name();

        $result = modules::run($module,$params);
        _set_module_name($old_module);
        return $result;
    }
}
if (!function_exists('_get_class')) {
    function _get_class()
    {
        $obj =& get_instance();
        return $obj->router->class;
    }
}
if (!function_exists('_get_method')) {
    function _get_method($allow_index = true)
    {
        $obj =& get_instance();
        $class = $obj->router->class;
        $method = $obj->router->method;
        if ($allow_index) {
            return $method;
        } else {
            return ($method == 'index') ? $class : $method;
        }
    }
}
if(!function_exists('_uri_segment')) {
    function _uri_segment($number = 0)
    {
        $CI =& get_instance();
        return $CI->uri->segment($number);
    }
}
if(!function_exists('_uri_string')) {
    function _uri_string($key_max=3) {
        $CI =& get_instance();
        $string = $CI->uri->uri_string();
        $slugs = explode('/', $string);
        $slug = '';
        if ($slugs) {
            $first = true;
            foreach ($slugs as $key => $str) {
                if ($key < $key_max) {
                    if ($first) {
                        $first = false;
                        $slug .= $str;
                    } else {
                        $slug .= '/' . $str;
                    }
                }
            }
        }
        return $slug;
    }
}
if (!function_exists('_get_module_name')) {
    function _get_module_name()
    {
        $obj =& get_instance();
        return $obj->router->fetch_module();
    }
}
if (!function_exists('_set_module_name')) {
    function _set_module_name($module)
    {
        $obj =& get_instance();
        $obj->router->module = $module;
        return true;
    }
}
if (!function_exists('_load_settings')) {
    function _load_settings()
    {
        $obj =& get_instance();
        $settings = $obj->db->get(SETTING_TABLE);
        $settings = $settings->result_array();
        $data = array();
        foreach ($settings as $setting) {
            if ($setting['serialized'] == 1) {
                $data[$setting['code']][$setting['title']] = unserialize($setting['value']);
            } else {
                $data[$setting['code']][$setting['title']] = $setting['value'];
            }
        }
        _vars('db_settings', $data);
    }
}
if (!function_exists('_get_setting')) {
    function _get_setting($key, $false_value = '', $code = 'general')
    {
        $code = (!$code) ? 'general' : $code;
        $settings = _get_var('db_settings', false);
        if (!$settings) {
            _load_settings();
        }
        $settings = _get_var('db_settings', false);
        if ($settings) {
            if (isset($settings[$code][$key])) {
                return $settings[$code][$key];
            } else {
                return $false_value;
            }
        } else {
            return $false_value;
        }
    }
}
if (!function_exists('_get_code_settings')) {
    function _get_code_settings($code)
    {

        $settings = _get_var('db_settings', false);
        if (!$settings) {
            _load_settings();
        }
        $settings = _get_var('db_settings', false);
        if ($settings) {
            if (isset($settings[$code])) {
                return $settings[$code];
            } else {
                return false;
            }
        } else {
            return false;
        }

    }
}
if (!function_exists('_set_setting')) {
    function _set_setting($key, $value, $code = 'general', $refresh_settings = true)
    {
        $code = (!$code) ? 'general' : $code;
        $obj =& get_instance();
        $obj->db->where('title', $key);
        $obj->db->where('code', $code);
        $result = $obj->db->get(SETTING_TABLE);
        if ($result->num_rows() > 0) {
            $obj->db->where('title', $key);
            $obj->db->where('code', $code);

            if (is_array($value)) {
                $data = array(
                    'value' => serialize($value),
                    'serialized' => 1
                );
            } else {
                $data = array(
                    'value' => $value,
                    'serialized' => 0
                );
            }
            $return = $obj->db->update(SETTING_TABLE, $data);
        } else {
            if (is_array($value)) {
                $data = array(
                    'code' => $code,
                    'title' => $key,
                    'value' => serialize($value),
                    'serialized' => 1
                );
            } else {
                $data = array(
                    'code' => $code,
                    'title' => $key,
                    'value' => $value,
                    'serialized' => 0
                );
            }

            $obj->db->insert(SETTING_TABLE, $data);
            $return = $obj->db->insert_id();
        }
        if ($refresh_settings) {
            _load_settings();
        }
        return $return;
    }
}
if (!function_exists('_set_code_settings')) {
    function _set_code_settings($data, $code, $refresh_settings = true)
    {

        foreach ($data as $key => $value) {
            _set_setting($key, $value, $code, false);
        }
        if ($refresh_settings) {
            _load_settings();
        }
        return true;

    }
}
/*Loader Ends*/

/*Config Start*/
if (!function_exists('_load_config')) {
    function _load_config($file = '', $use_sections = FALSE, $fail_gracefully = FALSE)
    {
        $obj =& get_instance();
        $obj->config->load($file, $use_sections, $fail_gracefully);
    }
}
if (!function_exists('_get_config')) {
    function _get_config($item, $index = '')
    {
        $obj =& get_instance();
        return $obj->config->item($item, $index);
    }
}
if (!function_exists('_set_config')) {
    function _set_config($item, $value)
    {
        $obj =& get_instance();
        $obj->config->set_item($item, $value);
    }
}
/*Config End*/

/*Session Starts*/
if (!function_exists('_get_session')) {
    function _get_session($key, $false_value = NULL)
    {
        $obj =& get_instance();
        $var = $obj->session->userdata($key);
        if ($var) {
            return $var;
        } else {
            return $false_value;
        }
    }
}
if (!function_exists('_set_session')) {
    function _set_session($data, $value = NULL)
    {
        $obj =& get_instance();
        $obj->session->set_userdata($data, $value);
    }
}
if (!function_exists('_unset_session')) {
    function _unset_session($key)
    {
        $obj =& get_instance();
        $obj->session->unset_userdata($key);
    }
}
if (!function_exists('_destroy_session')) {
    function _destroy_session()
    {
        $obj =& get_instance();
        $obj->session->sess_destroy();
    }
}
/*Session Ends*/

/* Javascript Variables Start */
if (!function_exists('_set_js_namespace')) {
    function _set_js_namespace($namespace)
    {
        _vars('js_namespace',$namespace);
    }
}
if (!function_exists('_get_js_namespace')) {
    function _get_js_namespace()
    {
        return _get_var('js_namespace','erpData');
    }
}
if (!function_exists('_set_js_var')) {
    function _set_js_var($key, $value, $type = 's', $location = 'h')
    {
        $vars = _get_var('js_vars');
        if (!isset($vars[$location][$key])) {
            $vars[$location][$key] = [
                'value' => $value,
                'type' => $type
            ];
            _vars('js_vars', $vars);
        }
    }
}
if (!function_exists('_get_js_var')) {
    function _get_js_var($key)
    {
        $locations = ['h', 'f'];
        $vars = _get_var('js_vars');
        foreach ($locations as $location) {
            if (find_key($location, $vars)) {
                return $vars[$location][$key];
            }
        }
    }
}
if (!function_exists('_get_js_vars')) {
    function _get_js_vars($location)
    {
        $vars = _get_var('js_vars');
        $namespace = _get_js_namespace();
        $js_string = "";
        if (is_array($vars) && array_key_exists($location, $vars)) {
            $location_vars = $vars[$location];
            if ($location_vars) {
                $js_string = "window.$namespace={";
                foreach ($location_vars as $key => $var) {
                    switch ($var['type']) {
                        case 's':
                            {
                                $value = $var['value'];
                                $js_string .= "$key:'$value',";
                                break;
                            }
                        case 'n':
                            {
                                $value = $var['value'];
                                $js_string .= "$key:$value,";
                                break;
                            }
                        case 'b':
                        {
                            $value = ($var['value'])?'true':'false';
                            $js_string .= "$key:$value,";
                            break;
                        }
                        case 'j':
                            {
                                $value = json_encode($var['value']);
                                $js_string .= "$key:$value,";
                                break;
                            }
                        default:
                            {
                                break;
                            }
                    }
                }
                $js_string = substr_replace($js_string ,"",-1);
                $js_string .= "}";
            }
        }
        return $js_string;
    }
}
if (!function_exists('_ejs_vars')) {
    function _ejs_vars($location)
    {
        $js_vars = _get_js_vars($location);
        if ($js_vars) {
            echo '<script>' . $js_vars . '</script>';
        }
    }
}
/* Javascript Variables End */

/*Timezone Starts*/
if (!function_exists('_set_timezone')) {
    function _set_timezone($timezone = '', $timezone_value = '')
    {
        $timezone = ($timezone == '') ? _get_config('default_timezone') : $timezone;
        $timezone_value = ($timezone_value == '') ? _get_config('default_timezone_value') : $timezone_value;

        if ($timezone_value != '') {
            _db_query("SET SESSION time_zone = '$timezone_value'");
        }
        if ($timezone != '') {
            date_default_timezone_set($timezone);
        }
    }
}
/*Timezone Ends*/

/*Input Starts*/
if (!function_exists('_input')) {
    function _input($index = NULL, $xss_clean = NULL)
    {
        $request_method = _get_request_method(true);
        if(_input_server('HTTP_CONVERT_PAYLOAD')=='array') {
            if ($request_method == 'GET') {
                $payload = _input_get('payload');
            } elseif ($request_method == 'POST') {
                $payload = _input_post('payload');
            } else {
                $payload = _input_stream('payload');
            }
            $payload = json_decode($payload,true);
            return (@$payload[$index])?$payload[$index]:'';
        }else {
            if ($request_method == 'GET') {
                return _input_get($index, $xss_clean);
            } elseif ($request_method == 'POST') {
                return _input_post($index, $xss_clean);
            } else {
                return _input_stream($index, $xss_clean);
            }
        }
    }
}
if (!function_exists('_input_get')) {
    function _input_get($index = NULL, $xss_clean = NULL)
    {
        $obj =& get_instance();
        return $obj->input->get_post($index, $xss_clean);
    }
}
if (!function_exists('_input_post')) {
    function _input_post($index = NULL, $xss_clean = NULL)
    {
        $obj =& get_instance();
        return $obj->input->post_get($index, $xss_clean);
    }
}
if (!function_exists('_input_stream')) {
    function _input_stream($index = NULL, $xss_clean = NULL)
    {
        $obj =& get_instance();
        return $obj->input->input_stream($index, $xss_clean);
    }
}
if (!function_exists('_input_header')) {
    function _input_header($index, $xss_clean = FALSE)
    {
        $obj =& get_instance();
        return $obj->input->get_request_header($index, $xss_clean);
    }
}
if (!function_exists('_input_cookie')) {
    function _input_cookie($index = NULL, $xss_clean = NULL)
    {
        $obj =& get_instance();
        return $obj->input->cookie($index, $xss_clean);
    }
}
if (!function_exists('_input_valid_ip')) {
    function _input_valid_ip($ip,$which='')
    {
        $obj =& get_instance();
        return $obj->input->valid_ip($ip,$which);
    }
}
if (!function_exists('_input_server')) {
    function _input_server($index = NULL, $xss_clean = NULL)
    {
        $obj =& get_instance();
        return $obj->input->server($index, $xss_clean);
    }
}
if (!function_exists('_input_client_ip')) {
    function _input_client_ip()
    {
        $obj =& get_instance();
        return $obj->input->ip_address();
    }
}
if (!function_exists('_set_cookie')) {
    function _set_cookie($name, $value = '', $expire = '', $domain = '', $path = '/', $prefix = '', $secure = NULL, $httponly = NULL)
    {
        $obj =& get_instance();
        return $obj->input->set_cookie($name, $value, $expire, $domain, $path, $prefix, $secure, $httponly);
    }
}
if (!function_exists('_get_request_method')) {
    function _get_request_method($upper=false)
    {
        $obj =& get_instance();
        return $obj->input->method($upper);
    }
}
if (!function_exists('_is_post_request')) {
    function _is_post_request()
    {
        $obj =& get_instance();
        return ($obj->input->method(TRUE) == 'POST') ? TRUE : FALSE;
    }
}
if (!function_exists('_is_ajax_request')) {
    function _is_ajax_request()
    {
        $obj =& get_instance();
        return ($obj->input->is_ajax_request()) ? TRUE : FALSE;
    }
}
if ( ! function_exists('_validate_header')){
    function _validate_header($var){
        $valid = true;
        $variable = 'HTTP_' . strtoupper($var);
        if(!_input_server($variable)) {
            return false;
        }
        return $valid;
    }
}
if ( ! function_exists('_validate_post')){
    function _validate_post($var){
        $valid = true;
        if(!_input_post($var) && !_input_get($var)) {
            return false;
        }
        return $valid;
    }
}
/*Input Ends*/

/*Dump Starts*/
if (!function_exists('dump')) {
    function dump($var, $label = 'Dump', $echo = TRUE)
    {
        // Store dump in variable
        ob_start();
        var_dump($var);
        $output = ob_get_clean();

        // Add formatting
        $output = preg_replace("/\]\=\>\n(\s+)/m", "] => ", $output);
        $output = '<pre style="background: #FFFEEF; color: #000; border: 1px dotted #000; padding: 10px; margin: 10px 0; text-align: left;">' . $label . ' => ' . $output . '</pre>';

        // Output
        if ($echo == TRUE) {
            echo $output;
        } else {
            return $output;
        }
    }
}
if (!function_exists('dump_exit')) {
    function dump_exit($var, $label = 'Dump', $echo = TRUE)
    {
        dump($var, $label, $echo);
        exit;
    }
}
if (!function_exists('dd')) {
    function dd($var, $label = 'Dump', $echo = TRUE)
    {
        dump_exit($var, $label, $echo);
    }
}
/*Dump Ends*/

/*Database Starts*/
if (!function_exists('_db_query')) {
    function _db_query($query)
    {
        $obj =& get_instance();
        return $obj->db->query($query);
    }
}
if (!function_exists('_db_get_query')) {
    function _db_get_query($query,$single=false)
    {
        $obj =& get_instance();
        $result = $obj->db->query($query);
        if($result){
            if($result->num_rows()){
                if($single) {
                    return $result->row_array();
                }
                return $result->result_array();
            }else{
                return FALSE;
            }
        }else{
            return FALSE;
        }
    }
}
if (!function_exists('_db_escape')) {
    function _db_escape($value)
    {
        $obj =& get_instance();
        return $obj->db->escape($value);
    }
}
if (!function_exists('_db_trans_start')) {
    function _db_trans_start()
    {
        $obj =& get_instance();
        return $obj->db->trans_start();
    }
}
if (!function_exists('_db_trans_complete')) {
    function _db_trans_complete()
    {
        $obj =& get_instance();
        return $obj->db->trans_complete();
    }
}
if (!function_exists('_db_trans_status')) {
    function _db_trans_status()
    {
        $obj =& get_instance();
        return $obj->db->trans_status();
    }
}
if (!function_exists('_db_trans_commit')) {
    function _db_trans_commit()
    {
        $obj =& get_instance();
        return $obj->db->trans_commit();
    }
}
if (!function_exists('_db_trans_rollback')) {
    function _db_trans_rollback()
    {
        $obj =& get_instance();
        return $obj->db->trans_rollback();
    }
}
/*Database Ends*/

/* App Start */
if (!function_exists('_get_app_version')) {
    function _get_app_version()
    {
        return _get_config('app_version');
    }
}
/* App End */

/* Permission Start */
if (!function_exists('_can')) {
    function _can($module,$type='sub')
    {
        $result = _get_var('permissions', []);
        if (!$result) {
            _load_permissions();
        }
        $result = _get_var('permissions', []);
        $permissions = [];
        if($type=='page') {
            $permissions = $result['page'];
        }elseif($type=='sub') {
            $permissions = $result['sub'];
        }
        return in_array($module,$permissions);
    }
}
if (!function_exists('_load_permissions')) {
    function _load_permissions()
    {
        $permissions = _get_module('users/groups','_permissions');
        _vars('permissions', $permissions);
    }
}
if (!function_exists('_can_write')) {
    function _can_write($module = '')
    {
        $obj =& get_instance();

        if ($module == '') {
            $module = _get_class() . '/' . _get_method();
        }

        return $obj->permissions->check_access($module, 'write');
    }
}
if (!function_exists('_validate_access')) {
    function _validate_access_redirect($module, $type, $redirect = 'unauthorised')
    {
        $obj =& get_instance();
        $result = $obj->permissions->check_access($module, $type);
        if ($result) {
            return true;
        } else {
            redirect($redirect);
        }
    }
}
/* Permission End */

/* Login Start */
if ( ! function_exists('_logged_in')){
    function _logged_in(){
        if(_get_session('logged_in')) {
            return true;
        }else{
            return false;
        }
    }
}
if ( ! function_exists('_get_user_id')){
    function _get_user_id(){
        return _get_session('user_id');
    }
}
if ( ! function_exists('_get_group_id')){
    function _get_group_id(){
        return _get_session('group_id');
    }
}
if ( ! function_exists('_set_ajax_key')){
    function _set_ajax_key() {
        $obj =& get_instance();
        $obj->load->model('key');
        return $obj->key->generate();
    }
}
if ( ! function_exists('_get_ajax_key')){
    function _get_ajax_key($user_id) {
        $obj =& get_instance();
        $obj->load->model('key');
        return $obj->key->get($user_id);
    }
}
/* Login End */
/* PDF Start */
if ( ! function_exists('_generate_pdf')){
    function _generate_pdf($html,$file,$params=[]){
        $mpdf = new \Mpdf\Mpdf(['mode' => 'utf-8', 'format' => 'A4']);
        if(isset($params['footer_html']) && $params['footer_html']) {
            $mpdf->SetHTMLFooter($params['footer_html']);
        }
        if(isset($params['watermark']) && $params['watermark']) {
            $mpdf->SetWatermarkText($params['watermark'],0.05);
            $mpdf->showWatermarkText = true;
        }
        /*$mpdf->dpi = 300;
        $mpdf->img_dpi = 300;
        $mpdf->SetWatermarkText('GGAC',0.1);
        $mpdf->showWatermarkText = true;*/
        ini_set("pcre.backtrack_limit", "50000000");
        $mpdf->WriteHTML($html);
        return $mpdf->Output($file,'F');
    }
}
/* PDF End */
/* ERP Specific Start */
if ( ! function_exists('_update_ref')) {
    function _update_ref($code,$value=false) {
        $obj =& get_instance();
        $obj->load->model('reference');
        return $obj->reference->update_code($code,$value);
    }
}
if ( ! function_exists('_get_ref')) {
    function _get_ref($code,$format=1,$total_digits=4) {
        $obj =& get_instance();
        $obj->load->model('reference');
        return $obj->reference->get($code,$format,$total_digits);
    }
}
if ( ! function_exists('_excel_to_array')) {
    function _excel_to_array($file) {

        $result = [];
        //$inputFileType = 'Xls';
        $inputFileType = 'Xlsx';
        //    $inputFileType = 'Xml';
        //    $inputFileType = 'Ods';
        //    $inputFileType = 'Slk';
        //    $inputFileType = 'Gnumeric';
        //    $inputFileType = 'Csv';

        /**  Create a new Reader of the type defined in $inputFileType  **/
        $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader($inputFileType);
        /**  Load $inputFileName to a Spreadsheet Object  **/
        $spreadsheet = $reader->load($file);

        $sheets = $spreadsheet->getSheetNames();

        if($sheets) {
            foreach ($sheets as $sheet) {
                $rows = $spreadsheet->getSheetByName($sheet)->toArray('', true, true, true);

                $header = array_shift($rows);
                $data = toKeyedRows($rows, $header);

                $result[strtolower($sheet)] = $data;
            }
        }
        return $result;
    }
}
/* ERP Specific End */
if (! function_exists('dsRound')) {
    function dsRound($value,$decimal=null){
        if(empty($decimal)) { $decimal = 2; }
        return number_format((float)$value, $decimal, '.', '');
    }
}
if (! function_exists('dsMoneyRound')) {
    function dsMoneyRound($value) {
        $curr_code = 'USD';//config('project.default_currency_code','USD');
        $locale = 'en_US';//config('project.default_locale','en_US');
        $fmt = new NumberFormatter($locale, NumberFormatter::CURRENCY);
        return numfmt_format_currency($fmt, $value, $curr_code);
    }
}