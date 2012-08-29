<?php 
class Mysql_DB
{
	var $fields;
	public function Mysql_DB(){
	}
	
	public function connect(){		
	}
	
	public function assign($field, $value)
	{
		$this->fields[$field] = $value;
	}
	
	public function select($table){
	}
	
	public function insert($table){
		$f = "";
		$v = "";
		reset($this->fields);
		foreach($this->fields as $field=>$value)
		{
			if(!is_numeric($value))
				$value = "'$value'";
			$f.= ($f!=""?", ":"")."`$field`";
			$v.= ($v!=""?", ":"").$value;
		}
		$sql = "INSERT INTO ".TABLE_PREFIX.$table." (".$f.") VALUES (".$v.")";
		$this->query($sql);
		return $this->insert_id();
	}
	
	public function update($table, $where)
	{
		$f = "";
		reset($this->fields);
		foreach($this->fields as $field=>$value)
		{
			if(!is_numeric($value))
				$value = "'$value'";
			$f.= ($f!=""?", ":"")."`$field`"." = ".$value;
		}
		$sql = "UPDATE ".TABLE_PREFIX.$table." SET ".$f." WHERE ".$where;
		$this->query($sql);
	}
	
	public function query($_query){
		$this->query = $_query;
		$this->result = @mysql_query($_query, $this->link_id) or die( $_query."<p>".mysql_error($this->link_id) );
		return $this->result;
	}
	
	public function get_records(){
		$this->records = array();
		while($row = @mysql_fetch_array($this->result, MYSQL_BOTH)){
			$this->records[count($this->records)] = $row;
		}
		reset($this->records);
		return $this->records;
	}
	
	public function fetch_array(){
		$this->col = @mysql_fetch_array($this->result, MYSQL_BOTH);
		return $this->col;
	}
	public function num_rows(){
		return (int)@mysql_num_rows($this->result);
	}
	
	public function close(){
		@mysql_close($this->link_id);
	}
	
	private function insert_id(){
		return @mysql_insert_id($this->link_id);
	}
	
	public function reset(){
		$this->fields = array();	
	}
}
?>