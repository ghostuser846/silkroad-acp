<?php

class Database {

	var $database;
	var $error;
	var $result;

	function Database($host, $username, $password, $dbname) {
		$this->database = @mysql_connect($host, $username, $password); 				
		if (!$this->database) {
			$this->set_error();
		} else {
			$this->set_database($dbname);
		}
	}
	
	function set_database($dbname) {
		if (!mysql_select_db($dbname)) {		
			$this->set_error();
		}
	}
	
	function set_error() {
		$this->error = mysql_errno().": ".mysql_error();
		echo $this->error."\n";
	}
	
	function get_last_error() {
		if ($this->error != "") {			
			return $this->error;
		} else {
			return false;
		}
	}
	
	// Return max $field from $table
	function get_max($table, $field) {
		$m = @mysql_query("select max($field) from $table");
		$m = @mysql_fetch_array($m, MYSQL_ASSOC);
		return $m["max($field)"];	
	}
	
	// Execute mysql query
	function query($string) {
		if (!$this->result = @mysql_query($string, $this->database)) {
			$this->set_error();
			echo $string.'<br>';
			return false;
		} else {
			return true;
		}
	}
	
	
	/**
	 * Return count of row affected last operation
	 *
	 * @return count of row affected last operation
	 */
	
	function affected_rows() {
		if ($this->database) {
			return mysql_affected_rows($this->database);
		} else {
			return false;
		}
	}
	
	// Update $table with $object
	function update($table, $object) {		
		$query = "update $table set ";				
		foreach ($object as $name => $value) {
			$value = mysql_escape_string($value);
			$query .= "$name = '$value',";
		}
		$query[strlen($query) - 1] = " ";
		$query .= 'where id = '.$object['id'];		
		return $this->query($query);		
	}
	
	// Select from $table with $condition into internal variable $result
	function select($table, $clause = '', $order = '') {
		if (empty($clause)) $clause = 1;
		if (!empty($order)) $order = 'order by '.$order;
		$this->query("select * from $table where $clause $order");				
		return $this->affected_rows();
	}
	
	// Get next object from associated array $result
	function get() {
		if ($array = @mysql_fetch_array($this->result, MYSQL_ASSOC)) return $array;
		else return false;		
	}
	
	function get_insert_id() {
		return mysql_insert_id();
	}
	
	// Insert $object into $table
	function insert($table, $object) {
		//$object['id'] = $this->get_max($table, 'id') + 1;
		$query = "insert into $table set ";
		foreach ($object as $name => $value) {
			$value = mysql_escape_string($value);
			$query .= "$name = '$value',";
		}
		$query[strlen($query) - 1] = " ";
		return $this->query($query);	
	}	
	
	// Delete $table with $condition
	function delete($table, $clause) {		
		return $this->query("delete from $table where $clause");
	}
	
	function counter($table, $field, $clause = 1) {
		$c = @mysql_query("select count($field) from $table where $clause");
		$c = @mysql_fetch_array($c, MYSQL_ASSOC);
		return $c["count($field)"];	
	}
	
	function close() {
		mysql_close($this->database);	
	}
}

?>