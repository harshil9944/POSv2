<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Plugin extends MY_Model
{
	public function __construct()
	{
		parent::__construct();
		$this->table = MODULE_TABLE;
	}
	public function getList()
	{
	    _helper(array('file'));

		$modules = array();
		$it = new RecursiveDirectoryIterator(MODULES_PATH);
		$allowed=array("xml");
		foreach(new RecursiveIteratorIterator($it) as $file) {
            $file_ext = pathinfo($file, PATHINFO_EXTENSION);
			if(in_array($file_ext,$allowed)) {
                $file_info = new SplFileInfo($file);
                $module=@simplexml_load_file($file);
                $fileName = basename($file_info->getFilename(),'.'.$file_ext);
				if($module->code==$fileName){
					$modules[] = array(
						'code'			=>	$module->code,
						'title'			=>	$module->title,
						'description'	=>	$module->description,
						'version'		=>	$module->version,
						'developer'		=>	$module->developer,
					);
				}
			}
		}
		return $modules;
	}
	private function getXMLFileData($module)
	{
		$file = MODULES_PATH.'/'.$module.'/'.$module.'.xml';
		$module=simplexml_load_file($file);

		return $module;
	}
	public function install($module)
	{
		$xml_data = $this->getXMLFileData($module);

		$data = array(
		    'type'  =>  'user',
			'code'	=>	$xml_data->code,
            'status'=>  1,
		);

		return $this->replace($data);
	}
	public function uninstall($module)
	{
		$xml_data = $this->getXMLFileData($module);

		$filter = array(
			'code'	=>	$xml_data->code,
		);

		return $this->delete($filter);
	}
	public function is_installed($code,$db=true)
	{
		if($db){
			$filter = array(
				'code'	=>	$code,
			);
			//echo $code.': '. count($this->contents->get($data)).'<br/>';
			if($this->count($filter)){
				return true;
			}else{
				return false;
			}
		}
	}
	public function getMenu()
	{
		$data = array(
			'status'	=>	'1'
		);
		$modules = $this->contents->get($data);
		if($modules){
			$menus = array();
			foreach($modules as $module) {
				$code = $module['code'];
				$model = $module['code'].'_model';
				$this->load->model(strtolower($code).'/'.strtolower($model));
				$module_menu = $this->{$model}->menu();
				if($module_menu) {
                    $menus[] = $module_menu;
                }
			}
			return $menus;
		}else{
			return false;
		}
	}
}
