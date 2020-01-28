<?php

/**
 *    Flat File database written in JSON
 */
class FFDB 
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
	
	public function db()
	{
		$args = func_get_args();
		$action = isset($args[0]) ? $args[0] : "";
		if($action=="desc"){
			$manifest = $this->readJsonFile(FFDB_ROOT."/db/$this->db_name/manifest.json");
			return $manifest;
		}
		elseif ($action=="remove") {
			$hardDelete = isset($args[1]) ? $args[1] : false;
			return $this->removeDb($hardDelete);
		}
	}
	
	public function table()
	{
		$args = func_get_args();
		$action = isset($args[0]) ? $args[0] : "";
	}
	
	private function validateDbName($db_name):bool
	{
		$this->setError(NULL);
		if(!$db_name || preg_match('/[^a-z_\-0-9]/i', $db_name)){
			$this->setError("Invalid Database Name, Only Alphanumeric, _ and - are allowed");
			return false;
		}
		return true;
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
				$cred = new stdClass();
				$cred->createdAt = date("d-M-Y H:i:s");
				$cred->updatedAt = date("d-M-Y H:i:s");
				$cred->tables = array();
				$cred->tablesLength = 0;
				if(!$this->writeJsonFile(FFDB_ROOT."/db/$db_name/manifest.json", $cred)){
					$this->setError("Unable to create manifest file for the new DB");
					return false;
				};
				$this->db_name = $db_name;
				return true;
			}
			$this->setError("Unable to create new DB");
			return false;
		}
		return false;
	}
	
	public function removeDb($hardDelete=false):bool
	{
		$this->setError(NULL);
		$db = FFDB_ROOT."/db/$this->db_name";
		$delDb = FFDB_ROOT."/deletedDb/$this->db_name";
		if(is_dir($db)){
			if($hardDelete){
				if(!$this->delDir($db)){
					$this->setError("Unable to delete the database");
					return false;
				}
			}
			else{
				$manifest = $this->readJsonFile("$db/manifest.json");
				if($manifest){
					$manifest->updatedAt = date("d-M-Y H:i:s");
					$this->writeJsonFile("$db/manifest.json", $manifest);
				}
				if(is_dir($delDb)){
					$this->setError("A deleted database with same DB name already exits");
					if(!$this->delDir($delDb)){
						$this->setError("Unable to delete the existing deleted database");
					}
				}
				if(!rename($db, $delDb)){
					$this->setError("Unable to move database to deleted databases");
					return false;
				}
			}
			return true;
		}
		else{
			$this->setError("No such Database exists to delete");
			return false;
		}
		return false;
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
				$this->setError("Error while JSON decoding file: $path, Error: ".$json_err_arr[$json_error]);
				return false;
			}
			else{
				return $file_content;
			}
		}
		else{
			$this->setError("Unable to open file: $path");
			return false;
		}
	}
	
	public function writeJsonFile($path='', $dataObj):bool
	{
		$this->setError(NULL);
		$handle = fopen($path,"w+");
		if($handle){
			if(!fwrite($handle, json_encode($dataObj, JSON_PRETTY_PRINT))){
				$this->setError("Unable to write file: $path");
				return false;
			}
			fclose($handle);
			return true;
		}
		else{
			$this->setError("Unable to open file: $path");
			return false;
		}
	}
	
	private function delDir($dir):bool
	{
		$files = array_diff(scandir($dir), array('.','..'));
		foreach ($files as $file) {
			(is_dir("$dir/$file")) ? $this->delDir("$dir/$file") : unlink("$dir/$file");
		}
		return rmdir($dir);
	}
	
	private function setError($err="", $code=-1):void
	{
		if(!$err){
			$this->error = array();
		}
		else{
			if(empty($this->error)){
				$this->error["trace"] = array();
			}
			$this->error["name"] = $err;
			$this->error["code"] = $code;
			$this->error["trace"][] = array(
				"name"	=> $err,
				"code"	=> $code,
			);
		}
	}
}

?>
