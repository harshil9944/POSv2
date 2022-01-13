<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Core extends CORE_Controller {

	public function _get_menu() {
        $menus = [];

        $menus[] = array(
            'id'        =>  'menu-dashboard',
            'class'     =>  '',
            'icon'      =>  'si si-home',
            'group'     =>  'core',
            'name'      =>  'Dashboard',
            'path'      =>  'dashboard',
            'module'    =>  '',
            'priority'  =>  0,
            'children'  =>  []
        );

        $menus[] = array(
            'id'        => 'menu-modules',
            'class'     => '',
            'icon'      => 'si si-grid',
            'group'     => 'settings',
            'name'      => 'Modules',
            'path'      => 'plugins',
            'module'    => '',
            'priority'  => 10,
            'children'  => []
        );

        $menus[] = array(
            'id'        => 'menu-routes',
            'class'     => '',
            'icon'      => 'si si-refresh',
            'group'     => 'settings',
            'name'      => 'Routes',
            'path'      => 'routes',
            'module'    => '',
            'priority'  => 10,
            'children'  => []
        );

        $menus[] = array(
            'id'        => 'menu-permissions',
            'class'     => '',
            'icon'      => 'si si-refresh',
            'group'     => 'settings',
            'name'      => 'Permissions',
            'path'      => 'permissions',
            'module'    => '',
            'priority'  => 10,
            'children'  => []
        );

        /*$menus[] = array(
            'id'        => 'menu-units',
            'class'     => '',
            'icon'      => 'si si-refresh',
            'group'     => 'settings',
            'name'      => 'Units',
            'path'      => 'core/units',
            'module'    => '',
            'priority'  => 10,
            'children'  => []
        );*/

        return $menus;

	}

}
