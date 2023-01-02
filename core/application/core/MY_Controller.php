<?php
class MY_Controller extends MX_Controller
{
    public static $instance;
    public $view = true;
    public $migration_enabled = false;
    public $module = '';
    public $model = '';
    public $check_authentication = true;
    public $is_rest_request = false;
    public $singular = '';
    public $plural = '';
    public $language = '';
    public $layout = '';
    public $redirect = '';
    public $is_installed = false;
    public $form_xtemplate = null;
	function __construct () {

	    $this->autoload = [
	        'config'    =>  ['sys_config','app_config'],
	        'helper'    =>  ['system','cache','template','plugin','url','string','ajax'],
            'libraries' =>  ['database','session','brahma'],
        ];
        $this->load->driver('cache');
        parent::__construct();

        if(!$this->module) {
            $this->view = false;
            die('Module Name is not defined for ' . _get_class());
        }

        //TODO Make this variable validate key from Database
        $this->is_rest_request = _get_var('key',false);
        if($this->view) {
            $this->view = !$this->is_rest_request;
        }

        //Login Redirect Start
        if(!$this->is_rest_request) {
            if ($this->check_authentication) {
                $route = _get_class();
                if (_get_method() !== 'index') {
                    $route .= '/' . _get_method();
                }
                if (!in_array($route, _get_config('permission_exclusion_pages'))) {
                    if (!_logged_in()) {
                        $this->view = false;
                        redirect(LOGIN_ROUTE);
                    }else{
                        $url_array = $this->uri->segment_array();
                        $segments = [];
                        for($i=1;$i<=count($url_array);$i++){
                            if(!is_numeric($url_array[$i])) {
                                $segments[] = $url_array[$i];
                            }
                        }
                        $segments = implode('/',$segments);
                        if($segments && !_can($segments,'page')) {
                            $userId = _get_session('user_id');
                            $this->view = false;
                            log_message('error',"Permission to access $segments is denied for $userId.");
                            $this->_redirect('unauthorised','refresh');
                        }
                    }
                }
            }
            if(!_is_ajax_request()) {
                $message = _get_message_html();
                if($message) {
                    _vars('message', $message);
                }
            }
        }
        //Login Redirect End

        if($this->model){ _model($this->model); }
        if($this->language) {
            _language($this->language);
        }else{
            log_message('debug','Language not found for ' . get_class($this));
        }

        if($this->module) {
            if(@$this->plugin) {
                $this->is_installed = $this->plugin->is_installed($this->module);
            }
        }

        if (method_exists($this, '_load_files')) {
            $route = $this->module;
            if(_get_method()!='index') {
                $route .= '/' . _get_method();
            }
            /*dump($this->module);
            dump(_uri_string());
            dump_exit($route);*/

            //TODO do something about it
            $uri_string = _uri_string();
            if($this->module == 'dashboard') {
                $uri_string = 'dashboard';
            }

            if($route == $uri_string || $route == _uri_string(2)) {
                //log_message('error','Load Files called');
                $this->_load_files();
            }
        }
	}

    public function _upgrade_available() {
        $this->view = false;
        if($this->migration_enabled) {
            $migration = 'migration';

            $current_version = $this->_get_version();
            $migrations = $this->{$migration}->find_migrations();
            $available_version = 0;
            foreach ($migrations as $version => $migration) {
                if ($available_version < $version) {
                    $available_version = $version;
                }
            }
            if ($available_version) {
                if ($current_version < $available_version) {
                    return true;
                } else {
                    return false;
                }
            } else {
                return false;
            }
        }else{
            return false;
        }
    }

    public function _upgrade() {
        $this->view = false;
        if($this->migration_enabled) {
            $migration = 'migration';
            _library($migration);
            $mig_table = $this->{$migration}->_migration_table;
            $record = _db_get_query("SELECT * FROM $mig_table");
            if(!$record) {
                _db_query("INSERT INTO $mig_table VALUES(0);");
            }

            if ($this->{$migration}->latest() === FALSE) {
                log_message('error', $this->{$migration}->error_string());
            }
        }
        return true;
    }

    public function _get_version() {
        if($this->migration_enabled) {
            if($this->model) {
                if (method_exists($this->{$this->model}, 'get_version')) {
                    return $this->{$this->model}->get_version();
                }
            }
        }
        return 0;
    }

    public function _sql_to_vue(&$obj,$keys=[],$array=false) {
        if(!$keys) {
            $keys = $this->{$this->model}->keys;
        }
        if($array) {
            $temp=[];
            foreach ($obj as $value) {
                foreach ($keys as $vue => $sql) {
                    change_array_key($sql, $vue, $value);
                }
                $temp[] = $value;
            }
            $obj = $temp;
        }else{
            foreach ($keys as $vue => $sql) {
                change_array_key($sql, $vue, $obj);
            }
        }
    }

    public function _vue_to_sql(&$obj,$keys=[],$array=false) {
        if(!$keys) {
            $keys = $this->{$this->model}->keys;
        }
        if($array) {
            $temp=[];
            foreach ($obj as $value) {
                foreach ($keys as $vue => $sql) {
                    change_array_key($vue,$sql,$value);
                }
                $temp[] = $value;
            }
            $obj = $temp;
        }else{
            foreach ($keys as $vue => $sql) {
                change_array_key($vue,$sql,$obj);
            }
        }
    }

    public function _exclude_keys(&$obj,$keys=[],$array=false) {
        if(!$keys) {
            $keys = $this->{$this->model}->exclude_keys;
        }
        if($array) {
            $temp=[];
            foreach ($obj as $value) {
                foreach ($keys as $key) {
                    if (isset($value[$key])) {
                        unset($value[$key]);
                    }
                }
                $temp[] = $value;
            }
            $obj = $temp;
        }else{
            foreach ($keys as $key) {
                if (isset($obj[$key])) {
                    unset($obj[$key]);
                }
            }
        }
    }

    public function _find($params=[]) {
        $exclude = false;
        $convert = false;
        if(isset($params['exclude'])) {
            if(is_array($params['exclude'])) {
                $exclude = $params['exclude'];
            }elseif ($params['exclude']===true) {
                $exclude = $this->{$this->model}->exclude_keys;
            }
        }
        if(isset($params['convert'])) {
            if(is_array($params['convert'])) {
                $convert = $params['convert'];
            }elseif ($params['convert']===true) {
                $convert = $this->{$this->model}->keys;
            }
        }

        $result = $this->{$this->model}->single($params['filter']);
        if($result) {
            if ($exclude) {
                $this->_exclude_keys($result, $exclude);
            }
            if ($convert) {
                $this->_sql_to_vue($result, $convert);
            }
        }
        return $result;
    }

    public function _search($params=[]) {

        $filter = (@$params['filter'])?$params['filter']:[];
        $limit = (isset($params['limit']) && is_int($params['limit']))?$params['limit']:_get_setting('global_limit',50);
        $offset = (isset($params['offset']) && is_int($params['offset']))?$params['offset']:0;
        $orders = (isset($params['orders']) && is_array($params['orders']))?$params['orders']:[];
        $where_ins = (isset($params['where_ins']) && is_array($params['where_ins']))?$params['where_ins']:[];
        $or_where_ins = (isset($params['or_where_ins']) && is_array($params['or_where_ins']))?$params['or_where_ins']:[];
        $and_likes = (isset($params['and_likes']) && is_array($params['and_likes']))?$params['and_likes']:[];
        $or_likes = (isset($params['or_likes']) && is_array($params['or_likes']))?$params['or_likes']:[];
        $exclude = false;
        $convert = false;
        if(isset($params['exclude'])) {
            if(is_array($params['exclude'])) {
                $exclude = $params['exclude'];
            }elseif ($params['exclude']===true) {
                $exclude = $this->{$this->model}->exclude_keys;
            }
        }
        if(isset($params['convert'])) {
            if(is_array($params['convert'])) {
                $convert = $params['convert'];
            }elseif ($params['convert']===true) {
                $convert = $this->{$this->model}->keys;
            }
        }
        if($and_likes) {
            foreach ($and_likes as $field=>$value) {
                $this->{$this->model}->like($field,$value);
            }
        }
        if($or_likes) {
            foreach ($or_likes as $field=>$value) {
                $this->{$this->model}->or_like($field,$value);
            }
        }
        if($orders) {
            foreach ($orders as $order) {
                $this->{$this->model}->order_by($order['order_by'],$order['order']);
            }
        }
        if($where_ins) {
            foreach ($where_ins as $where) {
                $this->{$this->model}->where_in($where['field'],$where['values']);
            }
        }
        if($or_where_ins) {
            $this->{$this->model}->gs();
            foreach ($or_where_ins as $or_where) {
                $this->{$this->model}->or_where_in($or_where['field'],$or_where['values']);
            }
            $this->{$this->model}->ge();
        }
        $records = $this->{$this->model}->search($filter,$limit,$offset);
        if($records) {
            $temp = [];
            foreach ($records as $single) {
                if($exclude) {
                    $this->_exclude_keys($single, $exclude);
                }
                if($convert) {
                    $this->_sql_to_vue($single, $convert);
                }
                $temp[] = $single;
            }
            $records = $temp;
        }
        return $records;

    }

    public function _search_count($params=[]) {

        /*$filter = $params['filter'];
        $this->{$this->model}->select('COUNT(*) as total');
        $count = $this->{$this->model}->single($filter);
        return $count['total'];*/
        $result = $this->_search($params);
        return ($result)?count($result):0;

    }

    public function _insert($params=[]) {
	    $insert = $params['data'];
        if($this->{$this->model}->insert($insert)) {
            return $this->{$this->model}->insert_id();
        }else{
            return false;
        }
    }

    public function _update($params=[]) {
        $update = $params['data'];
        $filter = $params['filter'];
        return $this->{$this->model}->update($update,$filter);
    }

    public function _soft_remove($params=[]) {
        $update = ['deleted'=>1,'deleted_at'=>sql_now_datetime()];
        $filter = ['id'=>$params['id']];
        return $this->{$this->model}->update($update,$filter);
    }

    public function _remove($params=[]) {
        $id = $params['id'];
        $filter = ['id'=>$id];
        return $this->{$this->model}->delete($filter);
    }

    protected function init_migration($params) {
        $migration_params = [
            'migration_enabled' => _get_config('migration_enabled'),
            'migration_type' => _get_config('migration_type'),
            'migration_path' => $params['migration_path'],
            'migration_table' => $params['migration_table']
        ];
        _library('migration',$migration_params);
        $this->migration->__construct($migration_params);
        $this->migration_enabled = true;
    }

    public function _redirect($uri = '', $method = 'auto', $view=false, $code = NULL) {
        $this->view = $view;
        redirect($uri,$method,$code);
    }

    protected function _add() {

        _language($this->language);
        _set_js_var('back_url',base_url($this->module),'s');
        _set_js_var('mode','add','s');
        _set_page_heading('New ' . $this->singular);
        _set_page_title('New ' . $this->singular);
        if($this->form_xtemplate) {
            _set_additional_component($this->form_xtemplate, 'outside');
        }
        _set_layout($this->layout);

    }

    protected function _edit($id) {

        _language($this->language);
        _set_js_var('back_url',base_url($this->module),'s');
        _set_js_var('mode','edit','s');
        _set_js_var('id',$id,'s');
        _set_page_heading('Edit ' . $this->singular);
        if($this->form_xtemplate) {
            _set_additional_component($this->form_xtemplate, 'outside');
        }
        _set_layout($this->layout);

    }

	private function set_titles() {

	    switch (_get_method()) {
            case 'index': {
                if(!_get_var('page_title') && $this->plural) { _set_page_title($this->plural); }
                if(!_get_var('page_heading') && $this->plural) { _set_page_heading($this->plural); }
                break;
            }
            case 'add': {
                $heading = 'New ' . $this->singular;
                if(!_get_var('page_title') && $this->singular) { _set_page_title($heading); }
                if(!_get_var('page_heading') && $this->singular) { _set_page_heading($heading); }
                break;
            }
            case 'edit': {
                $heading = 'Update ' . $this->singular;
                if(!_get_var('page_title') && $this->singular) { _set_page_title($heading); }
                if(!_get_var('page_heading') && $this->singular) { _set_page_heading($heading); }
                break;
            }
        }
    }

    protected function set_redirect($redirect) {
	    $this->redirect = $redirect;
    }

	public function __destruct() {

	    if($this->redirect) {
	        redirect($this->redirect,'refresh');
        }
        parent::__destruct();
        if(_get_class()!='action' && $this->view) {
            if (!_get_template()) {
                _set_template(DEFAULT_TEMPLATE);
            }
            if (!_get_layout()) {
                log_message('error','Layout was not set for ' . _get_class() . '/' . _get_method());
                show_404();
            }

            $this->_core_scripts();
            _execute_plugin();
            _load_page_scripts('style', PAGE_STYLES_PATH);
            _load_page_scripts('script', PAGE_SCRIPTS_PATH);

            _vars('content', _view(_get_layout()));
            _eview(_get_template());
        }
        die();

	}
	private function _core_scripts() {

        $permissions = _get_var('permissions', []);
        if (!$permissions) {
            _load_permissions();
        }

        $permissions = (@$permissions['sub'])?$permissions['sub']:[];

	    _set_js_var('action',base_url('action'),'s');
	    _set_js_var('key',_set_ajax_key(),'s');
	    _set_js_var('userId',_get_session('user_id'),'s');
	    _set_js_var('loggedIn',_logged_in(),'b');
	    _set_js_var('permissions',$permissions,'j');
	    _set_js_var('nPublicKey',NOTIFICATION_PUBLIC_KEY,'s');
	    _set_js_var('notificationLoadDelay',_get_setting('notification_load_delay',4000),'n');
	    _set_js_var('currencySign',_get_setting('currency_sign',''),'s');
	    _set_js_var('mDateFormat',_get_setting('m_date_format','DD/MM/YYYY'),'s');
	    _set_js_var('mDateTimeFormat',_get_setting('m_datetime_format','DD/MM/YYYY HH:mma'),'s');
        _set_js_var('mTimeFormat',_get_setting('m_time_format','HH:mma'),'s');
	    _set_js_var('imgCacheUrl',_get_config('global_image_cache_url'),'s');
	    _set_js_var('noImgUrl',asset_url('assets/img/no-image.jpg'),'s');
        _set_js_var('assetUrl',asset_url(),'s');

	    $user = _get_module('users','_find',['filter'=>['id'=>_get_session('user_id')],'exclude'=>true,'convert'=>true]);
        _set_js_var('user',$user,'j');

        _enqueue_style('assets/css/brahma.min.css','header',4);
        _enqueue_style('assets/css/helper.min.css','header',5);
        _enqueue_style('assets/css/custom.css','header',5);

        _enqueue_script('assets/js/brahma.core.min.js','header',0);
        _load_plugin(['vue']);
        _enqueue_script('assets/js/brahma.app.min.js','footer',0);
        _load_plugin(['parsley','notify']);
        _enqueue_script('vendors/bootstrap-vue/bootstrap-vue.min.js','footer',1);
        _enqueue_script('assets/js/common.js','footer',4);
        _enqueue_script('assets/js/notification.js','footer',4);

    }
	public function _clear($redirect=true)
	{
		$this->session->set_userdata('dstoken','');
		$this->session->set_userdata('logged_in', '');
		$this->session->set_userdata('user_id','');
		$this->session->set_userdata('username','');
		$this->session->set_userdata('user_role','');
		$this->session->set_userdata('user_status','');
		$this->session->set_userdata('user_fullname','');
		$this->session->set_userdata('user_img','');
		setcookie("dsadmintoken", "", time()-3600);
		if($redirect){
			$this->_redirect('login');
		}
	}
}
