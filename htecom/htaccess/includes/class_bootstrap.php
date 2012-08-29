<?php
class vH_Bootstrap
{
	var $db;	
	
	var $st;
	
	var $config;
	
	var $GPC;
	
	var $lang = 'vn';
	
	public function bootstrap()
	{
		$this->init();
	}
	
	public function init()
	{
		global $vhcore, $db, $st;		
		require_once( INC_DIR . '/init.php');
		require_once( INC_DIR . '/functions.php');
	}
}

class vH_Bootstrap_Site extends vH_Bootstrap
{
}