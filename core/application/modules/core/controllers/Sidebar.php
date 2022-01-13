<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Sidebar extends CORE_Controller {

	public function index()
	{
        return _view('sidebar_view',[],$this);
	}

}
