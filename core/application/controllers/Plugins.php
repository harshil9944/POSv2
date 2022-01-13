<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Plugins extends MY_Controller {

    public $plural = 'Modules';
    public $module = 'plugins';
	public function __construct()
    {
		parent::__construct();
		_model('plugin');
	}
	public function index()
	{
		_model('plugin');
        _library('table');

        $modules = $this->plugin->getList();
        $body=[];

		foreach($modules as $module){
			$module_class = strtolower($module['code']);
			$result = $this->plugin->single(['code'=>$module_class]);

            if($result) {
                $action = '<a href="#" class="text-danger" @click.prevent="handleUninstallPlugin(\''.$module_class.'\')">Uninstall</a>';
            }else {
                $action = '<a href="#" class="text-primary" @click.prevent="handleInstallPlugin(\''.$module_class.'\')">Install</a>';
            }

            $action_cell = [
                'class' =>  'text-center',
                'data'  =>  (isset($result['type']) && $result['type']=='core')?'':$action
            ];
            $this->load->module($module_class);
            $upgrade_available = false;
            $version = 0;

            if(method_exists($this->{$module_class},'_upgrade_available')) {
                $upgrade_available = $this->{$module_class}->_upgrade_available($module_class);
            }

            if(method_exists($this->{$module_class},'_get_version')) {
                $version = $this->{$module_class}->_get_version();
            }
            if($result) {
                if ($upgrade_available) {
                    $upgrade_btn = '<a href="#" @click.prevent="handleUpgradePlugin(\'' . $module_class . '\')">Upgrade</a>';
                } else {
                    $upgrade_btn = 'Up-to-date';
                }
            }else{
                $upgrade_btn = 'N/A';
            }
            $formatted_version = ($version)?str_pad($version, 3, '0', STR_PAD_LEFT):'';
            $body[] = [
                'title'			=>	$module['title'],
                'description'	=>	$module['description'],
                'version'		=>	$formatted_version,
                'update'        =>  $upgrade_btn,
                'developer'		=>	$module['developer'],
                $action_cell
            ];
		}

		$heading = [
            'Title',
            'Description',
            'Version',
            'Update',
            'Developer',
            ['data'=>'Action','class'=>'text-center w-50p']
        ];

        _vars('table_heading',$heading);
        _vars('table_body',$body);
        $table = _view(DATA_TABLE_PATH);

        $page = [
            'singular'  =>  'Module',
            'plural'    =>  'Modules',
            'add_url'   =>  false,
            'table'     =>  $table
        ];
        _vars('page_data',$page);

        _set_additional_component(LIST_XTEMPLATE_PATH,'outside');

        _set_page_title('Modules');
        _set_page_heading('Modules');
        _set_layout_type('wide');
        _set_layout(LIST_VIEW_PATH);
		//$this->common->get_page_data($this->data);

		//$this->data['content'] = $this->load->view('modules/list_view',$this->data,TRUE);
		//$this->load->view('template',$this->data);
	}

	public function _install_post() {

	    $plugin = _input('plugin');

        if($this->plugin->install($plugin)) {
            //Call Module Install Method
            _get_module($plugin,'_install');
        }
        _response_data('redirect',base_url('plugins'));
        return true;

		/*$this->load->model('modules_model');
		if($this->modules_model->install($moduleclass)){
			try {
				$code = $moduleclass;
				$model = $moduleclass.'_model';
				$this->load->model(strtolower($code).'/'.strtolower($model));
				$this->{$model}->install();
				$this->settings->set_message('Module was installed successfully.');
			} catch (Exception $e) {
				$this->modules_model->uninstall($code);
				$this->settings->set_message($e->getMessage(),'error');
			}
		}
		redirect('module');*/
	}
	public function _uninstall_post() {
	    $plugin = _input('plugin');
		/*$this->load->model('modules_model');
		if($this->modules_model->uninstall($moduleclass)){
			try {
				$code = $moduleclass;
				$model = $moduleclass.'_model';
				$this->load->model(strtolower($code).'/'.strtolower($model));
				$this->{$model}->uninstall();
				$this->settings->set_message('Module was uninstalled successfully.');
			} catch (Exception $e) {
				$this->settings->set_message($e->getMessage(),'error');
			}
		}
		redirect('module');*/
	}

	public function _upgrade_post() {

	    $plugin = _input('plugin');

	    _get_module($plugin,'_upgrade');
        _response_data('redirect',base_url('plugins'));
	    return true;

    }

    public function _load_files() {
        if(_get_method()=='index') {
            _load_plugin('dt');
            _page_script_override('plugins');
        }
    }
}
