<?php
if (!function_exists('_execute_plugin')) {
    function _execute_plugin() {
        $plugin = _get_var('plugins');
        if($plugin) {
            if (is_array($plugin)) {
                foreach ($plugin as $item) {
                    $function = '_load_'.$item;
                    if (function_exists($function)) {
                        call_user_func($function);
                    }
                }
            } else {
                $function = '_load_'.$plugin;
                if (function_exists($function)) {
                    call_user_func($function);
                }
            }
        }
    }
}
if (!function_exists('_load_plugin')) {
    function _load_plugin($plugin) {

        if($plugin) {
            $plugins = _get_var('plugins',[]);
            if (is_array($plugin)) {
                foreach ($plugin as $item) {
                    $plugins[] = $item;
                }
            } else {
                $plugins[] = $plugin;
            }
            $plugins = array_unique($plugins);
            _vars('plugins',$plugins);
        }
    }
}
if (!function_exists('_load_dt')) {
    function _load_dt() {

        /*_enqueue_style('vendors/datatables.net-dt/css/jquery.dataTables.min.css');
        _enqueue_style('vendors/datatables.net-responsive-dt/css/responsive.dataTables.min.css');*/
        _enqueue_style('assets/plugins/datatables/dataTables.bootstrap4.css');

        _enqueue_script('assets/plugins/datatables/jquery.dataTables.min.js');
        _enqueue_script('assets/plugins/datatables/dataTables.bootstrap4.min.js');
        //_enqueue_script('assets/js/datatables.min.js');

    }
}
if (!function_exists('_load_vue')) {
    function _load_vue() {

        if(ENVIRONMENT=='development') {
            _enqueue_script('vendors/vue/vue.js','footer',0);
        }else{
            _enqueue_script('vendors/vue/vue.min.js','footer',0);
        }

    }
}
if (!function_exists('_load_parsley')) {
    function _load_parsley() {

        _enqueue_script('vendors/parsley/dist/parsley.min.js','footer','0');

    }
}
if (!function_exists('_load_toast')) {
    function _load_toast() {

        _enqueue_script('vendors/jquery-toast-plugin/dist/jquery.toast.min.js','footer',0);
        _enqueue_style('vendors/jquery-toast-plugin/dist/jquery.toast.min.css');

    }
}
if (!function_exists('_load_dropzone')) {
    function _load_dropzone() {

        _enqueue_script('vendors/dropzone/dist/dropzone.js','footer',0);
        _enqueue_style('vendors/dropzone/dist/dropzone.css');

    }
}
if (!function_exists('_load_vue_multiselect')) {
    function _load_vue_multiselect() {

        _enqueue_script('vendors/vue-multiselect/vue-multiselect.min.js','footer',0);
        _enqueue_style('vendors/vue-multiselect/vue-multiselect.min.css');

    }
}
if (!function_exists('_load_select2')) {
    function _load_select2() {

        _enqueue_script('vendors/select2/js/select2.min.js','footer',0);
        _enqueue_style('vendors/select2/css/select2.min.css');

    }
}
if (!function_exists('_load_form_plugins')) {
    function _load_form_plugins() {

        _enqueue_script('assets/js/cb/be_forms_plugins.min.js','footer',0);

    }
}
if (!function_exists('_load_datepicker')) {
    function _load_datepicker() {

        _enqueue_script('assets/js/vuejs-datepicker.min.js','footer',0);

    }
}
if (!function_exists('_load_vue_autocomplete')) {
    function _load_vue_autocomplete() {

        _enqueue_script('vendors/vue-autocomplete/VueBootstrapTypeahead.umd.min.js','footer',0);

    }
}
if (!function_exists('_load_vue_datepicker')) {
    function _load_vue_datepicker() {

        _enqueue_script('vendors/vue-datepicker/vuejs-datepicker.min.js','footer',0);

    }
}
if (!function_exists('_load_vue_taginput')) {
    function _load_vue_taginput() {

        _enqueue_script('vendors/vue-tags-input/vue-tags-input.min.js','footer',2);

    }
}
if (!function_exists('_load_moment')) {
    function _load_moment() {

        _enqueue_script('vendors/moment/moment.min.js','footer',0);

    }
}
if (!function_exists('_load_tag_input')) {
    function _load_tag_input() {

        _enqueue_style('vendors/jquery-tags-input/jquery.tagsinput.min.css','header',0);
        _enqueue_script('vendors/jquery-tags-input/jquery.tagsinput.min.js','footer',0);

    }
}
if (!function_exists('_load_notify')) {
    function _load_notify() {

        _enqueue_script('assets/plugins/bootstrap-notify/bootstrap-notify.min.js','footer',2);

    }
}
