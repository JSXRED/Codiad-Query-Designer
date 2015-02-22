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


require_once('../../../common.php');
if(extension_loaded("mysql")) {
  require_once(dirname(__FILE__).'/DBConnection.mysql.php');
} elseif(extension_loaded("mysqli")) {
  require_once(dirname(__FILE__).'/DBConnection.mysqli.php');
}
checkSession();

class CM{
	function readdb_storage(){
		$raw =  file_get_contents("common/connections.php");
		preg_match('/\<\?php\/\*\|(.*)\|\*\/\?\>/', $raw, $results);
		return $results[1];
	}

	function getDetailByName($connectionname){
		$connection_json = json_decode($this->readdb_storage());
		foreach($connection_json as $connection){
			if(isset($connection->connectionname) && $connection->connectionname == $connectionname){
				return $connection;
			}
		}
	}

	function connectToDB(){
		$connection_name = urldecode($_GET['connectionname']);
		$connection_info = ($this->getDetailByName($connection_name));
		
		$db = new DBConnection();
		@$db->connect($connection_info->server, $connection_info->databasename, $connection_info->user, $connection_info->password);
		$this->catchError($db);

		return $db;
	}

	function catchError($db){
		if(!$db->getState())
		{
				$ret_array["success"] = "false";
				$ret_array["message"] = $db->getLastError();
				echo json_encode($ret_array);
				exit;
		}
	}
}

$CM = new CM();

if(!empty($_GET['action']))
{
	switch ($_GET['action']) {
		case 'connectionnames':
			$connection_json = json_decode($CM->readdb_storage());
			$ret_array = array();

			foreach($connection_json as $connection){
				array_push($ret_array, $connection->connectionname);
			}

			echo json_encode($ret_array);
			break;
		case 'dbobjs':
			if(!empty($_GET['connectionname'])){
				$db = $CM->connectToDB();

				$data = $db->getDatabaseObjs();
				$CM->catchError($db);	

				$db->disconnect();
				echo $data;
			}
			break;
		case 'executeQuery':
			if(!empty($_POST['query']) && !empty($_GET['action'])){
				$db = $CM->connectToDB();

				$data = $db->executeQuery($_POST['query']);
				$CM->catchError($db);	

				$db->disconnect();
				echo $data;
			}
			break;
		default:
			break;
	}
}

?>