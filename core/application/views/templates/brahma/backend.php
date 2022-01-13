<?php
$cb = $this->brahma;
$cb->main_nav                   = _get_module('core/menu','_get_brahma_menu');
$cb->inc_side_overlay           = _view('templates/brahma/backend/inc_side_overlay');//'inc/backend/views/inc_side_overlay.php';
$cb->inc_sidebar                = _view('templates/brahma/backend/inc_sidebar');//'inc/backend/views/inc_sidebar.php';
$cb->inc_header                 = _view('templates/brahma/backend/inc_header');//'inc/backend/views/inc_header.php';
$cb->inc_footer                 = _view('templates/brahma/backend/inc_footer');//'inc/backend/views/inc_footer.php';
