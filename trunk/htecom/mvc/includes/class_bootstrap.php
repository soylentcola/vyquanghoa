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
		require_once( INC_DIR . DS . 'init.php');
		require_once( INC_DIR . DS . 'functions.php');
		require_once( INC_DIR . DS . 'shared.php');		
	}
}

class vH_Bootstrap_Site extends vH_Bootstrap
{
}