<?php
/*
Script Name: Codiad Database Query Designer
Author: JSX.RED 
Author URI: http://www.jsx.red

Description: This plugin allow designing database queries through Codiad user interface.

Copyright (c) 2015 by JSX.RED 
distributed as-is and without warranty under the MIT License. See
[root]/license.txt for more. This information must remain intact.

Let us build a better future for all humanity.
Share knowledge. Share emotions. Share fun.
*/

class DBConnection{
	var $connection;
	var $state = true;

	var $dbname = "";

	function startsWith($haystack, $needle) {
    	// search backwards starting from haystack length characters from the end
    	return $needle === "" || strrpos($haystack, $needle, -strlen($haystack)) !== FALSE;
	}	

	function connect($server, $database, $user, $password){
		$this->connection = @mysql_connect($server, $user, $password);
		if($this->connection){
			mysql_select_db($database) or $this->state = false;
			$this->dbname = $database;
		}
	}

	function getState(){
		if($this->connection && $this->state)return true;
		else return false;
	}

	function getDatabaseObjs(){
		$result = mysql_query("SELECT table_name, table_type FROM information_schema.tables WHERE table_schema = '".$this->dbname."'", $this->connection) or $this->state = false;

		$ret_array = array();
		while($row=mysql_fetch_assoc($result)){
			$ret_row["Name"] = $row['table_name'];
			$ret_row['Type'] = $row['table_type'];
			$ret_row['Columns'] = $this->getColumnsOfObj($row['table_name']);
			array_push($ret_array, $ret_row);
		}

		return json_encode($ret_array);
	}

	function getColumnsOfObj($objname){
		$result = mysql_query("SELECT * FROM information_schema.columns where table_name = '".$objname."'", $this->connection) or $this->state = false;

		$ret_array = array();
		while($row=mysql_fetch_assoc($result)){
			$ret_row["TableName"] = $row['TABLE_NAME'];
			$ret_row['ColumnName'] = $row['COLUMN_NAME'];
			$ret_row['ColumnType'] = $row['COLUMN_TYPE'];
			array_push($ret_array, $ret_row);
		}

		return json_encode($ret_array);
	}

	function executeQuery($query){
		$result = mysql_query($query, $this->connection);
		if(trim($this->startsWith($query,"SELECT"))){
			$ret_array = array();
			if(mysql_num_rows($result)>0){
				while($row=mysql_fetch_assoc($result)){
					foreach(array_keys($row) as $key){
						$ret_row[$key] = htmlentities($row[$key]);
					}
					array_push($ret_array, $ret_row);
				}
			}else{
				$ret_array = array();
				$ret_array["success"] = "true";
				$ret_array["affected_rows"] = "0";
			}
		}else{
			$ret_array = array();
			$ret_array["success"] = "true";
			$ret_array["affected_rows"] = mysql_affected_rows();
		}

		echo json_encode($ret_array);
	}

	function getLastError(){
		return mysql_error();
	}

	function disconnect(){
		mysql_close($this->connection);
	}
}

?>
