<?php
//Fulltext search
class FTSearch
{
	var $settable='';
	var $searchcolumns='';
	var $selectcolumns='';
	var $comlumn_id ='';
	private $column_match = array();
	private $result = array();
	private $keywords = '';
	
	public function FTSearch()
	{
	}
	
	public function find($keywords)
	{
		global $db;
		$keywords = trim($keywords);
		if(empty($keywords))
			return false;
			
		$this->keywords = $keywords;
		
		//$db->query('ALTER TABLE `'.$this->settable.'` ADD FULLTEXT ('.$this->searchcolumns.')');
		
		if(empty($this->selectcolumns))
			$select = '*';
		else
			$select = $this->selectcolumns;
			
		$sql = "select ".$select." from ".$this->settable." where MATCH(".$this->searchcolumns.") AGAINST('".$keywords."') ";
		$rs = $db->query($sql);
		$arr_data = array();
		while( $row = $db->fetch_array($rs))
		{
			$row = fetch_row($row);
			array_push($arr_data, $row);
		}
			
		$this->result = $arr_data;
	}
	
	public function result(){
		return $this->result;
	}
	
	public function filters($column)
	{
		if(empty($this->result) || empty($column))
			return false;
		
		//print_r($this->result);
		
		$column = explode(',',$column);		
		$arr_data = array();
		foreach($this->result as $v)
		{
			$match = false;			
			foreach($column as $k_=>$column_)
			{
				$column_ = trim($column_);
				$v_[$column_.'_strip'] = stripUnicode($v[$column_]);
				if($this->findData($v_[$column_.'_strip']))
				{
					$match = true;
					array_push($arr_data, $v);
					array_push($this->column_match,array('id'=> $v[$this->comlumn_id], 'column'=>$column_));
					break;
				}
			}
		}
		return $arr_data;
	}
	
	private function findData($str)
	{
		if(empty($str))
			return false;
		//echo $str."\n";
		$str = explode(' ',removeSpecialChars($str));	
		foreach($str as $k=>$v){
			$str[$k] = strtoupper($v);
		}
		$str_upper = implode(' ',$str);
		//print_r($str);
		$keywords = explode(' ',removeSpecialChars(stripUnicode($this->keywords)));
		foreach($keywords as $v)
		{
			$v = strtoupper($v);
			if(!in_array($v, $str) && !$this->match($v,$str_upper))
			{
				return false;
			}
		}		
		return true;
	}
	
	private function match($find, $str)
	{
		if(empty($find) || empty($str))
			return false;
			
		if (preg_match("/$find/i", $str)) {
			return true;
		} else {
			return false;
		}
	}
	
	public function columnMatch(){
		if(empty($this->column_match))
			return false;
		return $this->column_match;
	}
}

?>