<?php
if ( ! function_exists('_set_template')){
    function _set_template($template){
        _vars('template','templates/'.$template);
    }
}
if ( ! function_exists('_get_template')){
    function _get_template(){
        return _get_var('template');
    }
}
if ( ! function_exists('_set_additional_component')){
    function _set_additional_component($view,$place='inside'){
        $additional_component = _get_var('additional_component');
        $additional_component[$place][] = $view;
        _vars('additional_component',$additional_component);
        return true;
    }
}
if ( ! function_exists('_get_additional_component')) {
    function _get_additional_component($place='inside'){
        $additional_component = _get_var('additional_component');
        $components = '';
        if(@$additional_component[$place]){
            foreach ($additional_component[$place] as $value) {
                $components .= _view($value);
            }
        }
        return $components;
    }
}
if ( ! function_exists('_set_layout')){
    function _set_layout($layout){
        _vars('layout',$layout);
        return true;
    }
}
if ( ! function_exists('_get_layout')){
    function _get_layout(){
        return _get_var('layout');
    }
}
if ( ! function_exists('_set_layout_type')){
    function _set_layout_type($type){
        if($type=='wide') { $class = 'container-fluid'; }else{ $class = 'container'; }
        _vars('layout_class',$class);
        return true;
    }
}
if ( ! function_exists('_get_layout_type')){
    function _get_layout_type(){
        return _get_var('layout_class','container');
    }
}
if ( ! function_exists('_get_page_content')){
    function _get_page_content(){
        return _get_var('content');
    }
}
if ( ! function_exists('_get_page_title')){
    function _get_page_title(){
        if(!_get_var('page_title')) {
            $title = _get_setting('company_name');
            if($title){
                return $title;
            }else{
                return _get_config('app_title');
            }
        }else{
            return _get_var('page_title');
        }
    }
}
if ( ! function_exists('_set_page_title')){
    function _set_page_title($title) {
        $suffix = _get_setting('company_name',_get_config('app_title'));
        _vars('page_title',$title . ' | ' . $suffix);
    }
}
if ( ! function_exists('_get_page_heading')){
    function _get_page_heading(){
        if(!_get_var('page_heading')) {
            return ucwords(_get_class());
        }else{
            return _get_var('page_heading');
        }
    }
}
if ( ! function_exists('_set_page_heading')){
    function _set_page_heading($title) {
        _vars('page_heading',$title);
    }
}
if ( ! function_exists('_enqueue_script')){
    function _enqueue_script($script,$position='footer',$priority=3,$condition=false) {
        $scripts = _get_var('scripts',array());
        $position = ($position!='header')?'footer':$position;

        $scripts[$position][$priority][] = array(
            'script'    =>  (string)$script,
            'type'      =>  'text/javascript',
            'cond'      =>  $condition
        );
        _vars('scripts',$scripts);
    }
}
if ( ! function_exists('_enqueue_cdn_script')){
    function _enqueue_cdn_script($script,$position='footer',$priority=3,$condition=false) {
        $scripts = _get_var('scripts',array());
        $position = ($position!='header')?'footer':$position;

        $scripts[$position][$priority][] = array(
            'script'    =>  (string)$script,
            'type'      =>  'cdn',
            'cond'      =>  $condition
        );
        _vars('scripts',$scripts);
    }
}
if ( ! function_exists('_enqueue_module_script')){
    function _enqueue_module_script($script,$position='footer',$priority=3,$condition=false) {
        $scripts = _get_var('scripts',array());
        $position = ($position!='header')?'footer':$position;

        $scripts[$position][$priority][] = array(
            'script'    =>  (string)$script,
            'type'      =>  'module',
            'cond'      =>  $condition
        );
        _vars('scripts',$scripts);
    }
}
if ( ! function_exists('_enqueue_style')){
    function _enqueue_style($style , $position = 'header' , $priority = 3) {
        $styles = _get_var('styles',array());

        $position = ($position!='header')?'footer':$position;
        $styles[$position][$priority][] = (string)$style;

        _vars('styles',$styles);
    }
}
if ( ! function_exists('_get_scripts')){
    function _get_scripts($position) {
        $output = '';
        $scripts = _get_var('scripts',array());

        for($priority=0;$priority<=5;$priority++) {
            if(isset($scripts[ $position ][ $priority ]) && $scripts[ $position ][ $priority ]) {
                foreach ( $scripts[ $position ][ $priority ] as $script ) {
                    if($script['cond']) {
                        $output .= $script['script'] . "\n";
                    }else{
                        if($script['type'] == 'cdn') {
                            $output .= '<script type="text/javascript" crossorigin="anonymous" src="' . $script['script'] . '"></script>' . "\n";
                        } else {
                            $file_path = asset_url() . $script['script'] . '?v=' . _get_app_version();
                            $output .= '<script type="' . $script['type'] . '" crossorigin="anonymous" src="' . $file_path . '"></script>' . "\n";
                        }
                    }
                }
            }
        }

        return $output;
    }
}
if ( ! function_exists('_get_styles')){
    function _get_styles($position) {
        $output = '';
        $styles = _get_var('styles',array());

        for($priority=0;$priority<=5;$priority++) {
            if(isset($styles[ $position ][ $priority ]) && $styles[ $position ][ $priority ]) {
                foreach ( $styles[ $position ][ $priority ] as $style ) {
                    $file_path = asset_url() . $style . '?v=' . _get_app_version();
                    $output .= '<link rel="stylesheet" crossorigin="anonymous" href="' . $file_path . '" />' . "\n";
                }
            }
        }

        return $output;
    }
}
if ( ! function_exists('_load_page_scripts')) {
    function _load_page_scripts($type,$dir,$class='',$class_method='')
    {

        if ($type == 'style') {
            $location = 'header';
            $method = 'style';
            if ($_SERVER['CI_ENV'] == 'development') {
                $ext = '.css';
            } else {
                $ext = '.min.css';
            }
        } elseif ($type == 'script') {
            $location = 'footer';
            $method = 'script';
            if ($_SERVER['CI_ENV'] == 'development') {
                $ext = '.js';
            } else {
                $ext = '.js';
            }
        } else {
            return false;
        }

        if (!$class && !$class_method) {
            $active_page_method = strtolower(_get_method());
            $active_page_class = strtolower(_get_class());
        } else {
            $active_page_method = $method;
            $active_page_class = $class;
        }

        $method_name = '_enqueue_' . $method;
        $override = _get_var("page_{$type}_override", false);

        if ($override) {
            $file_url = "{$dir}/{$override}{$ext}";
            $file_path = FCPATH . $file_url;
        } else {
            $module_name = _get_module_name();
            if ($module_name) {
                $active_page_class = "{$module_name}/{$active_page_class}";
            }
            if ($active_page_method == 'index') {
                $file_url = "{$dir}/{$active_page_class}{$ext}";
                $file_path = FCPATH . $file_url;
            } else {
                $file_url = "{$dir}/{$active_page_class}-{$active_page_method}{$ext}";
                $file_path = FCPATH . $file_url;
                if (!file_exists($file_path)) {
                    $file_url = "{$dir}/{$active_page_class}{$ext}";
                    $file_path = FCPATH . $file_url;
                }
            }
        }
        if ($type == 'script') {
            //dump($file_url);
            //dump($file_path);
            //dump_exit(file_exists($file_path));
        }
        if (file_exists($file_path)) {
            $file_url = str_replace(REMOVE_PAGE_SCRIPTS_PATH,'',$file_url);
            $method_name($file_url, $location, 4);
        }
    }
}
if ( ! function_exists('_page_script_override')) {
    function _page_script_override($file) {
        _vars('page_script_override',$file);
    }
}
if ( ! function_exists('_page_style_override')) {
    function _page_style_override($file) {
        _vars('page_style_override',$file);
    }
}
if ( ! function_exists('_get_message_html')){
    function _get_message_html($clear=true) {
        $allowed_types = _get_config('message_types');
        foreach ($allowed_types as $type=>$type_value) {
            $item = $type . '_message';
            $value = _get_session($item,false);
            if($value!=false) {
                if($clear) {
                    _clear_message();
                }
                $message = array(
                    'type'      =>  $type,
                    'message'   =>  $value,
                    'icon'      =>  $type_value['icon'],
                    'title'     =>  $type_value['title'],
                    'class'     =>  $type_value['class']
                );
                return _view('components/message',$message);
            }
        }
        if($clear) {
            _clear_message();
        }

        return '';
    }
}
if ( ! function_exists('_get_message')) {
    function _get_message($clear=true){
        $allowed_types = array('success','warning','info','error');
        foreach ($allowed_types as $type) {
            $item = $type . '_message';
            $value = _get_session($item,false);
            if($value!=false) {
                if($clear) {
                    _clear_message();
                }
                return array(
                    'type'  =>  $type,
                    'value' =>  $value
                );
            }
        }
        if($clear) {
            _clear_message();
        }
        return '';
    }
}
if ( ! function_exists('_set_message')) {
    function _set_message($value,$message_type){
        _clear_message();
        $types = _get_config('message_types');
        $allowed_types = array();
        foreach ($types as $type=>$type_value) {
            $allowed_types[] = $type;
        }
        if(in_array($message_type,$allowed_types)) {
            $item = $message_type . '_message';
            _set_session($item, $value);
        }
    }
}
if ( ! function_exists('_clear_message')) {
    function _clear_message() {
        $allowed_types = _get_config('message_types');
        foreach ($allowed_types as $type=>$type_value) {
            $item = $type . '_message';
            _unset_session($item);
        }
    }
}
if ( ! function_exists('_show_link')){
    function _show_link($url,$tooltip='Show Details') {
        return '<a class="btn btn-sm btn-secondary js-tooltip-enabled mr-2" data-toggle="tooltip" title="'.$tooltip.'" data-original-title="'. $tooltip . '" href="'. $url . '"><i class="fas fa-info-circle"></i></a>';
    }
}
if ( ! function_exists('_edit_vue_link')){
    function _edit_vue_link($function="handleEdit",$tooltip='Edit') {
        return '<a class="mr-5" data-toggle="tooltip" @click.prevent="'.$function.'" data-original-title="'. $tooltip . '" href="#"><i class="icon-pencil"></i></a>';
    }
}
if ( ! function_exists('_edit_link')){
    function _edit_link($url,$tooltip='Edit') {
        return '<a class="btn btn-sm btn-secondary js-tooltip-enabled mr-2" data-toggle="tooltip" data-original-title="'. $tooltip . '" href="'. $url . '"><i class="fas fa-edit"></i></a>';
    }
}
if ( ! function_exists('_edit_vue_link')){
    function _edit_vue_link($function="handleEdit",$tooltip='Edit') {
        return '<a class="btn btn-sm btn-secondary js-tooltip-enabled mr-2" data-toggle="tooltip" @click.prevent="'.$function.'" data-original-title="'. $tooltip . '" href="#"><i class="fas fa-edit"></i></a>';
    }
}
if ( ! function_exists('_delete_link')){
    function _delete_link($url,$tooltip='Delete') {
        return '<a class="btn btn-sm btn-secondary js-tooltip-enabled mr-2 delete-link" data-toggle="tooltip" data-original-title="'. $tooltip . '" href="'. $url . '"><i class="fa fa-trash"></i></a>';
    }
}
if ( ! function_exists('_vue_delete_link')){
    function _vue_delete_link($function="handleRemove",$tooltip='Delete') {
        return '<a class="btn btn-sm btn-secondary js-tooltip-enabled mr-2" data-toggle="tooltip" @click.prevent="'.$function.'" data-original-title="'. $tooltip . '" href="#"><i class="fa fa-trash"></i></a>';
    }
}
if ( ! function_exists('_vue_text_link')){
    function _vue_text_link($text,$function,$tooltip='') {
        return '<a class="js-tooltip-enabled" data-toggle="tooltip" @click.prevent="'.$function.'" data-original-title="'. $tooltip . '" href="#">'.$text.'</a>';
    }
}
if ( ! function_exists('_vue_button_link')){
    function _vue_button_link($icon,$function,$tooltip='') {
        return '<a class="btn btn-sm btn-secondary js-tooltip-enabled mr-2" data-toggle="tooltip" @click.prevent="'.$function.'" data-original-title="'. $tooltip . '" href="#"><i class="' . $icon . '"></i></a>';
    }
}
