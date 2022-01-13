<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Reports extends MY_Controller {

    public $module = 'reports';
    public $model = '';
    public $singular = 'Report';
    public $plural = 'Reports';
    public $language = 'reports/reports';
    public $edit_form = '';
    public function __construct()
    {
        parent::__construct();
        //_model($this->model);
        $params = [
            'migration_path' => REPORT_MIGRATION_PATH,
            'migration_table' => REPORT_MIGRATION_TABLE
        ];
        $this->init_migration($params);
    }
    public function index() {

        $this->view = false;
        $this->_redirect('reports/orders');

    }

    public function _get_menu() {

        $menus = [];

        $reports = [];

        $reports[] = [
            'name'	    =>  'Orders',
            'class'     =>  '',
            'group'     =>  '',
            'icon'      =>  'basket-loaded',
            'path'      =>  'reports/orders',
            'module'    =>  'reports/orders',
            'children'  =>  []
        ];

        $reports[] = [
            'name'	    =>  'Sales',
            'class'     =>  '',
            'group'     =>  '',
            'icon'      =>  'basket-loaded',
            'path'      =>  'reports/sales',
            'module'    =>  'reports/sales',
            'children'  =>  []
        ];

        $reports[] = [
            'name'	    =>  'Items',
            'class'     =>  '',
            'group'     =>  '',
            'icon'      =>  'basket-loaded',
            'path'      =>  'reports/items',
            'module'    =>  'reports/items',
            'children'  =>  []
        ];

        $reports[] = [
            'name'	    =>  'POS Sessions',
            'class'     =>  '',
            'group'     =>  '',
            'icon'      =>  'basket-loaded',
            'path'      =>  'reports/sessions',
            'module'    =>  'reports/sessions',
            'children'  =>  []
        ];

        $menus[] = array(
            'id'        => 'menu-reports',
            'class'     => '',
            'icon'      => 'si si-bar-chart',
            'group'     => 'module',
            'name'      => 'Reports',
            'path'      => 'reports',
            'module'    => 'reports',
            'priority'  => 5,
            'children'  => $reports
        );

        return $menus;

    }

    public function _install() {
        return true;
    }

    public function _uninstall() {
        return true;
    }

    protected function _load_files() {

    }
}
