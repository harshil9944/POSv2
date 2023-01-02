<?php
$cb = $this->brahma;
$cb->main_nav                   = [];
if(_can('dashboard','page')) {
    $cb->main_nav[] = [
        'name'  => '<span class="sidebar-mini-hide">Dashboard</span>',
        'icon'  => 'si si-home',
        'url'   => base_url('dashboard')
    ];
}
if(_can('pos','page')) {
    $cb->main_nav[] = [
        'name'  => '<span class="sidebar-mini-hide">POS</span>',
        'icon'  => 'si si-calculator',
        'url'   => base_url('pos'),
    ];
}
$cb->inc_side_overlay           = _view('templates/pos/backend/inc_side_overlay');
$cb->inc_sidebar                = _view('templates/pos/backend/inc_sidebar');
$cb->inc_header                 = _view('templates/pos/backend/inc_header');
$cb->inc_footer                 = _view('templates/pos/backend/inc_footer');
