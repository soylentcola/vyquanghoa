<?php
class MainModel
{
	public function index()
	{
		global $vhcore;
	}
	
	public function intro()
	{
		global $vhcore;
		echo 'id:'.$id = current(explode('-',$vhcore->GPC['req_1']));
	}
	
	public function contact()
	{
		global $vhcore;
	}
}

?>