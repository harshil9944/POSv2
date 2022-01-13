<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Topbar extends CORE_Controller {

	public function index()
	{
	    return _view('topbar_view',[],$this);
	}

}
