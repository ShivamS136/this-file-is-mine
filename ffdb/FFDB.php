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
	
	public function DB($action="")
	{
		$action = strtolower($action);
		if($action=="desc"){
			$manifest = readJsonFile(FFDB_ROOT."/db/$this->db_name/manifest.json");
			return $manifest;
		}
	}

	private function readJsonFile($path='')
	{
		$this->setError(NULL);
		$file_content = file_get_contents($path);
		if($file_content){
			$json_err_arr = array(
				JSON_ERROR_NONE => "No error has occurred", 
				JSON_ERROR_DEPTH => "The maximum stack depth has been exceeded", 
				JSON_ERROR_STATE_MISMATCH => "Invalid or malformed JSON", 
				JSON_ERROR_CTRL_CHAR => "Control character error, possibly incorrectly encoded", 
				JSON_ERROR_SYNTAX => "Syntax error", 
				JSON_ERROR_UTF8 => "Malformed UTF-8 characters, possibly incorrectly encoded",
				JSON_ERROR_RECURSION => "One or more recursive references in the value to be encoded",
				JSON_ERROR_INF_OR_NAN => "One or more NAN or INF values in the value to be encoded",
				JSON_ERROR_UNSUPPORTED_TYPE => "A value of a type that cannot be encoded was given",
				JSON_ERROR_INVALID_PROPERTY_NAME => "A property name that cannot be encoded was given",
				JSON_ERROR_UTF16 => "Malformed UTF-16 characters, possibly incorrectly encoded",
			);
			$file_content = json_decode($file_content);
			$json_error = json_last_error();
			if($json_error){
				$this->setError("Error while JSON decoding file: $path, Error: ".$json_err_arr[$json_error],6);
				return false;
			}
			else{
				return $file_content;
			}
		}
		else{
			$this->setError("Unable to open file: $path",5);
			return false;
		}
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
