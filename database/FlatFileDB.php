<?php

/**
 *    Flat File database written in JSON
 */
class FlatFileDB 
{
	public $db_name="";
	public $error=array();
	function __construct($db_name="", $createMode=true)
	{
		defined("FFDB_ROOT") or define("FFDB_ROOT", realpath(dirname(__FILE__,1)));
		$this->setError(NULL);
		$isValid = $this->validateDbName($db_name);
		if(!$isValid){
			return false;
		}
		if($this->connectDB($db_name, $createMode)){
			$this->db_name = $db_name;
		}
	}
	
	private function validateDbName($db_name):bool
	{
		$this->setError(NULL);
		if(!$db_name || preg_match('/[^a-z_\-0-9]/i', $db_name)){
			$this->setError("Invalid Database Name, Only Alphanumeric, _ and - are allowed",1);
			return false;
		}
		return true;
	}

	public function table($mode='')
	{
		// code...
	}
	
	private function delDir($dir) {
		$files = array_diff(scandir($dir), array('.','..'));
		foreach ($files as $file) {
			(is_dir("$dir/$file")) ? delDir("$dir/$file") : unlink("$dir/$file");
		}
		return rmdir($dir);
	}
	
	public function removeDb($db_name):bool
	{
		$this->setError(NULL);
		if(is_dir(FFDB_ROOT."/db/$db_name")){
			if(!$this->delDir(FFDB_ROOT."/db/$db_name")){
				$this->setError("Unable to delete the database",4);
			}
		}
		else{
			$this->setError("No such Database exists",3);
		}
	}
	
	private function setError($err="", $code=-1):void
	{
		if(!$err){
			$this->error = array();
		}
		else{
			$this->error["name"] = $err;
			$this->error["code"] = $code;
		}
	}
	
	private function connectDB($db_name="", $createMode=true):bool
	{
		$this->setError(NULL);
		if(is_dir(FFDB_ROOT."/db/$db_name")){
			$this->db_name = $db_name;
			return true;
		}
		else if($createMode){
			if(mkdir(FFDB_ROOT."/db/$db_name", 0777, true)){
				$handle = fopen(FFDB_ROOT."/db/$db_name/manifest.json", "w+");
				$cred = new stdClass();
				$cred->createdAt = date("d-M-Y H:i:s");
				$cred->updatedAt = date("d-M-Y H:i:s");
				$cred->tables = array();
				$cred->tablesLength = 0;
				fwrite($handle, json_encode($cred, JSON_PRETTY_PRINT));
				fclose($handle);
				$this->db_name = $db_name;
				return true;
			}
			$this->setError("Unable to create new DB",2);
			return false;
		}
		return false;
	}
}

?>
