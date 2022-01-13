<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Route extends MY_Model
{
    public function __construct()
    {
        $this->table = ROUTE_TABLE;
    }

    public function get_list($only_active=true) {

        $filter = [];
        if($only_active) {
            $filter['status'] = 1;
        }

        $this->order_by('slug');
        return $this->search($filter);

    }

    public function get_controllers() {

        $controllers = [];

        $paths = [];
        $paths['system'] = APPPATH . 'controllers';
        $paths['modules'] = MODULES_PATH . '';

        $ignore_controllers = _get_config('ignore_route_controllers');

        foreach ($paths as $key=>$path) {
            $modules = $this->get_path_controllers($path,$key);
            if($key=='modules') {
                foreach ($modules as $module => $path_controllers) {
                    if(!in_array(strtolower($module),$ignore_controllers)) {
                        foreach ($path_controllers as $controller => $path_controller) {
                            foreach ($path_controller as $method) {
                                $name = strtolower($controller);
                                $current_module_name = $name;
                                if (strtolower($method) != 'index') {
                                    $name .= '/' . strtolower($method);
                                }
                                if ($module == $current_module_name) {
                                    $controllers[] = $name;
                                } else {
                                    $controllers[] = $module . '/' . $name;
                                }
                            }
                        }
                    }
                }
            }else{
                $path_controllers = $modules;
                foreach ($path_controllers as $controller => $path_controller) {
                    if(!in_array(strtolower($controller),$ignore_controllers)) {
                        foreach ($path_controller as $method) {
                            $name = strtolower($controller);
                            if (strtolower($method) != 'index') {
                                $name .= '/' . strtolower($method);
                            }
                            $controllers[] = $name;
                        }
                    }
                }
            }
        }
        return $controllers;

    }
    public function get_path_controllers($path,$type='system') {

        $controllers = [];

        $it = new RecursiveDirectoryIterator($path);
        $allowed=array("php");
        foreach(new RecursiveIteratorIterator($it) as $file) {
            if(in_array(substr($file, strrpos($file, '.') + 1),$allowed)) {

                /*$dir = dirname($file);//explode('\\',dirname($file));
                $dirname = basename($dir);//$dir[count($dir)-1];*/

                $dir = explode(_get_config('dir_separator'),dirname($file));
                $dirname = $dir[count($dir)-1];

                if($dirname=='controllers') {
                    $controller_name = basename($file, EXT);

                    // Load the controller file in memory if it's not load already
                    if(!class_exists($controller_name)) {
                        $this->load->file($file);
                    }

                    // Add the controllername to the array with its methods
                    $aMethods = get_class_methods($controller_name);
                    $aUserMethods = array();
                    if($aMethods){
                        foreach($aMethods as $method) {
                            if($method[0] != '_' && $method != 'get_instance' && $method != $controller_name) {
                                $aUserMethods[] = $method;
                            }
                        }
                    }
                    if($type=='modules') {
                        $module = $dir[count($dir)-2];
                        $controllers[$module][$controller_name] = $aUserMethods;
                    }else{
                        $controllers[$controller_name] = $aUserMethods;
                    }
                }
            }
        }
        return $controllers;

    }

    public function refresh() {
        $controllers = $this->get_controllers();
        $route_list = $this->search();
        $ignore_methods = _get_config('ignore_route_methods');
        $routes=array();
        $delete_routes=array();
        if($route_list) {
            foreach($route_list as $row) {
                $routes[]=$row['slug'];
                $delete_routes[$row['slug']]=$row['slug'];
            }
        }
        foreach($controllers as $row) {
            if(!in_array($row,$routes) && !in_array($row,$ignore_methods)) {
                $insert = array( 'slug' => $row );
                $this->insert($insert);
            }else{
                if(!in_array($row,$ignore_methods)) {
                    unset($delete_routes[$row]);
                }
            }
        }
        foreach($delete_routes as $key=>$value){
            $filter = ['slug'=>$value];
            $this->delete($filter);
        }
    }
}
