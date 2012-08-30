<?php
class MainController
{
	public $model;
	
	public function __construct()  
    {  
        $this->model = new MainModel();

    } 
	
	public function run()
	{
		global $vhcore, $tplView;
		//print_r($vhcore->GPC);
		$_view 	= $vhcore->GPC['control'];
		//$_view1 = $vhcore->GPC['view'];
		
		switch($_view)
		{
			case 'gioi-thieu':{
				$tplView = tplView('Main','Intro');
				$this->model->intro();
				break;
			}
			
			case 'lien-he':{
				$tplView = tplView('Main','Contact');
				$this->model->contact();
				break;
			}
			
			default:{
				$tplView = tplView('Main','Index');
				$this->model->index();
				break;
			}
		}
	}
}

?>