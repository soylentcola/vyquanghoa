<?php
class Mysql_DB{

	var $conn_id;
	var $query_id;
	var $record;
	var $db;
	var $port;
	//var $dbName;

    function Mysql_DB($vhcore){
        $this->db = $vhcore->config['Database'];
		if( empty( $vhcore->config['Database']['port'] ) )
			$this->port = 3306;
		else 
			$this->port = $db_info['dbPort'];
		if( empty($this->dbName) )
			$this->dbName = $db_info['dbName'];
    }

    function connect() {
        $this->conn_id = @mysql_connect($this->db['host'].":".$this->port,$this->db['username'],$this->db['password']);
        if (!$this->conn_id) $this->sql_error("Connection Error");
        if (!@mysql_select_db($this->db['dbname'], $this->conn_id)){
			return false;
			$this->sql_error("Database Error");
		}
        return $this->conn_id;
    }	
	function close(){
		return mysql_close($this->conn_id);
	}
	
	function select($cols, $table,  $where = '', $orderby = '', $limit = '') {
    
        $cols = !empty($cols) ? $cols : '*';
		$orderby = !empty($orderby) ? " order by ".$orderby."" : '';
        $where = !empty($where) ? " where ".$where."" : '';
        $limit = !empty($limit) ? " limit ".$limit."" : '';
    
        return "select ".$cols." from ".$table.$where.$orderby.$limit."";
    }
	function prefix(){
		return $this->db['prefix'];
	}
	
	function insert($table, $data) {
    
        if (!is_array($data))
            return false;
            
        foreach ($data as $col => $value)
            $data[$col] = "'".htmlentities($value,ENT_QUOTES)."'";
        
        $cols = array_keys($data);
        $vals = array_values($data);
        
        $insert = "insert into ".$table." (".implode(',', $cols).") values (".implode(',', $vals).")";
        return $insert;
    }
	
	function update($table, $data, $where) {
    
        if (!is_array($data))
            return false;
            
        foreach ($data as $col => $value) {
            $vals[] = $col." = '".htmlentities($value,ENT_QUOTES)."'";
        }
        
        return "update  ".$table." set ".implode(',', $vals)." where ".$where."";
    
    }
	
	function delete($table, $where) {
    
        return "delete from ".$table." where ".$where."";
    
    }
	
	function query($query_string) {
		//$query_string = str_replace("'", "\'", $query_string);
		$this->query_id = @mysql_query($query_string,$this->conn_id);
		if (!$this->query_id){
			$this->sql_error("Query Error", $query_string);
		}
		return $this->query_id;
    }

    function fetch_array($query_id=-1) {
		if ($query_id!=-1) $this->query_id=$query_id;
        $this->record = @mysql_fetch_array($this->query_id);
        return $this->record;
    }
	
	function fetch_array_tree($query_id=-1, $ASSOC) {
		if ($query_id!=-1) $this->query_id=$query_id;
        $this->record = @mysql_fetch_array($this->query_id, $ASSOC);
        return $this->record;
    }

	function query_first($query_string) {
		$this->query($query_string);
		$returnarray=$this->fetch_array($this->query_id);
		$this->free_result($this->query_id);
		return $returnarray;
  	}

    function num_rows($query_id=-1) {
        if ($query_id!=-1) $this->query_id=$query_id;
		return @mysql_num_rows($this->query_id);
  	}

    function free_result($query_id) {
        return @mysql_free_result($query_id);
    }

    function sql_error($message, $query="") {
		
		$msgbox_title = $message;
		//$sqlerror= "<table width='100%' border='1' cellpadding='0' cellspacing='0'>";
		//$sqlerror ="<tr><th colspan='2'>SQL SYNTAX ERROR</th></tr>\n\n";
		$sqlerror.=($query!="")?"<tr><td> Query SQL</td><td>: ".$query."</td></tr>\n\n" : "";
		$sqlerror.="<tr><td> Error Number</td><td>: ".mysql_errno()." ".mysql_error()."</td></tr>\n";
        //$sqlerror.="<tr><td> Date</td><td>: ".date("D, F j, Y H:i:s")."</td></tr>\n";
        //$sqlerror.="<tr><td> IP</td><td>: ".getenv("REMOTE_ADDR")."</td></tr>\n";
        //$sqlerror.="<tr><td> Browser</td><td>: ".getenv("HTTP_USER_AGENT")."</td></tr>\n";
		//$sqlerror.="<tr><td> Script</td><td>: ".getenv("REQUEST_URI")."</td></tr>\n";
        //$sqlerror.="<tr><td> Referer</td><td>: ".getenv("HTTP_REFERER")."</td></tr>\n";
        //$sqlerror.="<tr><td> PHP Version </td><td>: ".PHP_VERSION."</td></tr>\n";
        //$sqlerror.="<tr><td> OS</td><td>: ".PHP_OS."</td></tr>\n";
        //$sqlerror.="<tr><td> Server</td><td>: ".getenv("SERVER_SOFTWARE")."</td></tr>\n";
        //$sqlerror.="<tr><td> Server Name</td><td>: ".getenv("SERVER_NAME")."</td></tr>\n";
		//$sqlerror.="</table>";
		$msgbox_messages = "\n<table class='smallgrey' cellspacing=1 cellpadding=0>\n\n".$sqlerror."</table>";
		$msg_header = "header_listred.gif";
		$msg_icon = "msg_error.gif";
		$imagesdir = "images";
		$redirecturl = '';
		$lang['gallery_back'] = "Back to the last request";
		if(!$templatefolder) $templatefolder = "templates";
		
		echo "Loi SQL! \n<!--".$msgbox_messages.'-->';
		
		//print "Eror";
		exit;
    }
}