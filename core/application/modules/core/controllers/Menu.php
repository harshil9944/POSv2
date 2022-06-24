<?php
defined( 'BASEPATH' ) OR exit( 'No direct script access allowed' );

class Menu extends CORE_Controller {

    public function index() {
        $groups = [];
        $groups['core'] = '';
        $groups['modules'] = 'Modules';
        $groups['settings'] = 'Settings';
        _vars( 'groups', $groups );

        $menus = $this->_get_menu_data();
        //dump_exit($menus);
        _vars( 'menus', $menus );

        return _view( 'menu_view', [], $this );
    }

    private function _get_menu_data() {

        _model( [ 'plugin' ] );

        $groups = _get_var( 'groups', [] );

        $menus = [];
        $menus['core'] = [];
        $menus['modules'] = [];
        $menus['settings'] = [];

        $data = [
            'status' => '1',
        ];
        $modules = $this->plugin->search( $data );
        if ( $modules ) {
            foreach ( $modules as $module ) {
                $code = $module['code'];
                $method = '_get_menu';
                $modules_menu = _get_module( $code, $method );

                if ( !empty( $modules_menu ) ) {
                    foreach ( $modules_menu as $menu ) {
                        if ( isset( $menu['group'] ) && $menu['group'] ) {
                            if ( isset( $groups[$menu['group']] ) ) {
                                if ( $menu['children'] ) {
                                    $temp = [];
                                    foreach ( $menu['children'] as $child ) {
                                        if ( _can( $child['path'], 'page' ) ) {
                                            $temp[] = $child;
                                        }
                                    }
                                    if ( $temp ) {
                                        $menu['children'] = $temp;
                                        $menus[$menu['group']][] = $menu;
                                    }
                                } else {
                                    if ( _can( $menu['path'], 'page' ) ) {
                                        $menus[$menu['group']][] = $menu;
                                    }
                                }
                            }
                        }
                    }
                }
            }
            if ( count( $menus['core'] ) < 1 ) {unset( $menus['core'] );}
            if ( count( $menus['modules'] ) < 1 ) {unset( $menus['modules'] );}
            if ( count( $menus['settings'] ) < 1 ) {unset( $menus['settings'] );}
            return $menus;
        } else {
            return false;
        }

    }

    public function _get_brahma_menu() {

        _model( ['plugin'] );

        $menus = [];

        $data = [
            'status' => '1',
        ];
        $modules = $this->plugin->search( $data );
        if ( $modules ) {
            foreach ( $modules as $module ) {
                $code = $module['code'];
                $method = '_get_menu';
                $modules_menu = _get_module( $code, $method );

                if ( !empty( $modules_menu ) ) {
                    foreach ( $modules_menu as $menu ) {
                        if ( isset( $menu['group'] ) && $menu['group'] ) {
                            /*if(!isset($groups[$menu['group']])) {
                            $groups[$menu['group']] = [];
                            }*/
                            if ( $menu['children'] ) {
                                $temp = [];
                                foreach ( $menu['children'] as $child ) {
                                    if ( _can( $child['path'], 'page' ) ) {
                                        $child['url'] = base_url( $child['path'] );
                                        $temp[] = $child;
                                    }
                                }
                                if ( $temp ) {
                                    $menu['url'] = base_url( $menu['path'] );
                                    $menu['sub'] = $temp;
                                    unset( $menu['children'] );
                                    //$menus[$menu['group']][] = $menu;
                                    if ( isset( $menu['group'] ) && $menu['group'] == 'settings' ) {
                                        $menu['icon'] = '';
                                        $menus['settings'][] = $menu;
                                    } else {
                                        $menus[] = $menu;
                                    }
                                }
                            } else {
                                if ( _can( $menu['path'], 'page' ) ) {
                                    $menu['url'] = base_url( $menu['path'] );
                                    //$menu['sub'] = [];
                                    unset( $menu['children'] );
                                    //$menus[$menu['group']][] = $menu;
                                    if ( isset( $menu['group'] ) && $menu['group'] == 'settings' ) {
                                        $menu['icon'] = '';
                                        $menus['settings'][] = $menu;
                                    } else {
                                        $menus[] = $menu;
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
        $settings = $menus['settings'];

        unset( $menus['settings'] );
        sksort( $settings, 'priority', true );

        if ( $settings ) {
            $setting_menu = [
                'id'       => 'menu-settings',
                'class'    => '',
                'icon'     => 'si si-settings',
                'group'    => '',
                'name'     => 'Settings',
                'path'     => '',
                'module'   => '',
                'priority' => 50,
                'sub'      => $settings,
            ];
            $menus[] = $setting_menu;
        }
        sksort( $menus, 'priority', true );

        return $menus;
    }

}
