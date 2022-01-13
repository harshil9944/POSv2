<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Contacts extends MY_Controller {

    public $module = 'contacts';
    public $model = 'contact';
    public $singular = 'Contact';
    public $plural = 'Contacts';
    public $language = 'contacts/contacts';
    public $edit_form = '';
    public function __construct()
    {

        parent::__construct();
        _model($this->model);
        $params = [
            'migration_path' => CONTACT_MIGRATION_PATH,
            'migration_table' => CONTACT_MIGRATION_TABLE
        ];
        $this->init_migration($params);
    }

    public function _get_menu() {

        $menus = [];

        $contacts = [];
        $contacts[] = array(
            'name'	    =>  'Customers',
            'class'     =>  '',
            'group'     =>  '',
            'icon'      =>  'basket-loaded',
            'path'      =>  'contacts/customers',
            'module'    =>  'contacts',
            'children'  =>  []
        );
        if(ALLOW_CUSTOMER_GROUP) {
            $contacts[] = array(
                'name'     => 'Customer Groups',
                'class'    => '',
                'group'    => '',
                'icon'     => 'basket-loaded',
                'path'     => 'contacts/customer_groups',
                'module'   => 'contacts',
                'children' => []
            );
        }

        $contacts[] = array(
            'name'	    =>  'Vendors',
            'class'     =>  '',
            'group'     =>  '',
            'icon'      =>  'basket-loaded',
            'path'      =>  'contacts/vendors',
            'module'    =>  'contacts',
            'children'  =>  []
        );

        $menus[] = array(
            'id'        => 'menu-contacts',
            'class'     => '',
            'icon'      => 'si si-users',
            'group'     => 'module',
            'name'      => 'Contacts',
            'path'      => 'contacts',
            'module'    => 'contacts',
            'priority'  => 1,
            'children'  => $contacts
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
