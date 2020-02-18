<?php

/**
 *    Flat File database written for JSON File DB
 */
class FFDB 
{
	public $db_name="";
	public $error=array();
	
	function __construct($db_name="", $createMode=true)
	{
		defined("FFDB_ROOT") or define("FFDB_ROOT", realpath(dirname(__FILE__,1)));
		$this->setError(NULL);
		$db_name =strtolowwer($db_name);
		$isValid = $this->validateName($db_name);
		if(!$isValid){
			return false;
		}
		if($this->connectDB($db_name, $createMode)){
			$this->db_name = $db_name;
		}
	}
	
	/*
	Actions:
		- EXISTS
		- DESC
		- DROP
	 */
	public function db()
	{
		$this->setError(NULL);
		$args = func_get_args();
		$action = isset($args[0]) ? strtolower($args[0]) : "";
		if($action=="desc"){
			return $this->dbDesc();
		}
		else if($action == "exists"){
			return $this->dbExists();
		}
		else if ($action == "drop") {
			$hardDelete = isset($args[1]) ? $args[1] : false;
			return $this->dbDrop($hardDelete);
		}
		else {
			$this->setError("Invalid action given", __LINE__);
			return false;
		}
		return true;
	}
	
	private function dbDrop($hardDelete=false):bool
	{
		if($this->dbExists()){
			$dbDir = FFDB_ROOT."/db/$this->db_name";
			$delDbDir = FFDB_ROOT."/deletedDb/$this->db_name";
			if($hardDelete){
				if(!$this->delDir($dbDir)){
					$this->setError("Unable to delete the database", __LINE__);
					return false;
				}
			}
			else{
				$this->updateDbManifest();
				if(is_dir($delDbDir)){
					$this->setError("A deleted database with same DB name already exits", __LINE__);
					if(!$this->delDir($delDbDir)){
						$this->setError("Unable to delete the existing deleted database", __LINE__);
					}
				}
				if(!rename($dbDir, $delDbDir)){
					$this->setError("Unable to move database to deleted databases", __LINE__);
					return false;
				}
			}
			return true;
		}
		else{
			$this->setError("A DB with this name doesn't exist", __LINE__);
			return false;
		}
	}
	
	private function dbExists():bool
	{
		if(!file_exists(FFDB_ROOT."/db/$this->db_name/manifest.json")){
			return false;
		}
		else{
			return true;
		}
	}
	
	private function dbDesc()
	{
		if(!empty($this->db_name)){
			if(!file_exists(FFDB_ROOT."/db/$this->db_name/manifest.json")){
				$this->setError("A DB with this name doesn't exist", __LINE__);
				return false;
			}
			else{
				$dbManifest = $this->readJsonFile(FFDB_ROOT."/db/$this->db_name/manifest.json");
				return $dbManifest;
			}
		}
		else{
			$this->setError("DB name can not be empty", __LINE__);
			return false;
		}
	}
	
	/*
	Actions:
		- EXISTS
		- DESC
		- CREATE
		- DROP
	 */
	public function table()
	{
		$this->setError(NULL);
		$args = func_get_args();
		$action = isset($args[0]) ? strtolower($args[0]) : "";
		if($action == "exists"){
			$tableName = isset($args[1]) ? strtolower($args[1]) : "";
			return $this->tableExists($tableName);
		}
		else if($action == "create"){
			$tableName = isset($args[1]) ? strtolower($args[1]) : "";
			$columnArr = isset($args[2]) ? $args[2] : array();
			return $this->tableCreate($tableName, $columnArr);
		}
		else if($action == "desc"){
			$tableName = isset($args[1]) ? strtolower($args[1]) : "";
			return $this->tabelDesc($tableName);
		}
		else if($action == "drop"){
			$tableName = isset($args[1]) ? strtolower($args[1]) : "";
			$hardDelete = isset($args[2]) && $args[2]!=false ? true : false;
			return $this->tabelDrop($tableName, $hardDelete);
		}
		else {
			$this->setError("Invalid action given", __LINE__);
			return false;
		}
		return true;
	}
	
	private function tableDrop($tableName="", $hardDelete=false):bool
	{
		if($this->dbExists()){
			$tableDir = FFDB_ROOT."/db/$this->db_name/$tableName.json";
			$delTableDir = FFDB_ROOT."/db/$this->db_name/deletedTable/$tableName.json";
			if($hardDelete){
				if(!unlink($tableDir)){
					$this->setError("Unable to delete the table", __LINE__);
					return false;
				}
			}
			else{
				if(file_exists($delTableDir)){
					$this->setError("A deleted table with same table name already exits", __LINE__);
					if(!unlink($delTableDir)){
						$this->setError("Unable to delete the existing deleted table", __LINE__);
					}
					else{
						$this->updateDbManifest();
					}
				}
				$this->updateTableManifest($tableName);
				if(!rename($tableDir, $delTableDir)){
					$this->setError("Unable to move table to deleted tables", __LINE__);
					return false;
				}
			}
			return true;
		}
		else{
			$this->setError("DB doesn't exist", __LINE__);
			return false;
		}
	}
	
	private function updateDbManifest($manifest=array()){
		if(empty($manifest)){
			$manifest = $this->dbDesc();
		}
		if($manifest){
			$manifest->updatedAt = date("d-M-Y H:i:s");
			$this->writeJsonFile(FFDB_ROOT."/db/$this->db_name/manifest.json", $manifest);
		}
	}
	
	private function updateTableManifest($tableName="", $tableObj=array()){
		if(empty($tableObj)){
			$tableObj = $this->tableDesc($tableName, true);
		}
		if($tableObj){
			$tableObj->manifest->updatedAt = date("d-M-Y H:i:s");
			$this->writeJsonFile(FFDB_ROOT."/db/$this->db_name/$tableName.json", $tableObj);
			$this->updateDbManifest();
		}
	}
	
	private function tableDesc($tableName="", $fullFile=false)
	{
		$dbManifest = $this->dbDesc();
		if(!$dbManifest){
			$this->setError("Unable to describe table as DB is not found", __LINE__);
			return false;
		}
		else{
			if(!$this->tableExists($tableName)){
				$this->setError("Table not found", __LINE__);
				return false;
			}
			else{
				$tableObj = $this->readJsonFile(FFDB_ROOT."/db/$this->db_name/$tableName.json");
				if($fullFile){
					return $tableObj;
				}
				return $tableObj->manifest;
			}
		}
	}
	
	private function tableCreate($tableName="", $columnArr=array()):bool
	{
		// Check if a table already exists with same name
		$dbManifest = $this->dbDesc();
		if(!$dbManifest){
			$this->setError("Unable to create table as DB is not found", __LINE__);
			return false;
		}
		else{
			if($this->tableExists($tableName)){
				$this->setError("A table with same name already exists", __LINE__);
				return false;
			}
			$columnDetailArr = array();
			foreach ($columnArr as $colName => $colArr) {
				$colDatatype = "string";
				$colConstraint = "";
				$colAutoincr = false;
				$colDefault = "";
				if(is_array($colArr)){
					$colArr = array_change_key_case($colArr,"CASE_LOWER");
					$colDatatype = isset($colArr["type"]) ? strtolower($colArr["type"]) : "string";
					if(in_array($colConstraint, array("string", "number", "bool"))){
						$colDatatype = "string";
						$this->setError("Invalid Column Datatype hence converted into string", __LINE__);
					}
					$colConstraint = isset($colArr["constraint"]) ? strtolower($colArr["constraint"]) : "";
					if(in_array($colConstraint, array("primary key", "unique key", "not null"))){
						$this->setError("Invalid Column Datatype hence converted into string", __LINE__);
						$colConstraint = "";
					}
					$colAutoincr = isset($colArr["autoincr"]) && $colArr["autoincr"] ? true : false;
					if($colDatatype != "number" && $colAutoincr==true){
						$colAutoincr = false;
						$this->setError("Only Number type columns can be Auto Incremented", __LINE__);
					}
					$colDefault = isset($colArr["default"]) ? strtolower($colArr["default"]) : "";
				}
				else{
					$colName = $colArr;
				}
				$colFinal = array(
					"name"	=> $colName,
					"type"	=> $colDatatype
				);
				if($colConstraint !== ""){
					$colFinal["constraint"] = $colConstraint;
				}
				if($colAutoincr !== false){
					$colFinal["autoincr"] = $colAutoincr;
				}
				if($colDefault !== ""){
					$colFinal["default"] = $colDefault;
				}
				$columnDetailArr[] = $colFinal;
			}
			$tableObj = new stdClass();
			$tableObj->data = [];
			$tableObj->manifest = new stdClass();
			$tableObj->manifest->columns = $columnDetailArr;
			$tableObj->manifest->rowCount = 0;
			$tableObj->manifest->colCount = count($columnDetailArr);
			$tableObj->manifest->createdAt = date("d-M-Y H:i:s");
			$tableObj->manifest->updatedAt = date("d-M-Y H:i:s");
			if($this->writeJsonFile(FFDB_ROOT."/db/$this->db_name/$tableName.json", $tableObj)){
				$dbManifest->tables[] = $tableName;
				$dbManifest->tablesLength++;
				$this->updateDbManifest($dbManifest);
			}
			return true;
		}
	}
	
	private function tableExists($tableName=""):bool
	{
		$dbManifest = $this->dbDesc();
		if($dbManifest){
			if($tableName==""){
				$this->setError("Table name can not be empty", __LINE__);
				return false;
			}
			else if(!in_array($tableName, $dbManifest->tables)){
				$this->setError("A table with this name doesn't exist in the manifest.", __LINE__);
				return false;
			}
			else if(!file_exists(FFDB_ROOT."/db/$this->db_name/$tableName.json")){
				$this->setError("A table with this name doesn't exist in the database", __LINE__);
				return false;
			}
			else{
				return true;
			}
		}
		else{
			$this->setError("DB doesn't exist", __LINE__);
			return false;
		}
		return false;
	}
	
	private function validateName($db_name):bool
	{
		$this->setError(NULL);
		if(!$db_name || preg_match('/[^a-z_\-0-9]/i', $db_name)){
			$this->setError("Invalid Database Name. Only Alphanumeric, _ and - are allowed", __LINE__);
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
					$this->setError("Unable to create manifest file for the new DB", __LINE__);
					return false;
				};
				$this->db_name = $db_name;
				return true;
			}
			$this->setError("Unable to create new DB", __LINE__);
			return false;
		}
		return false;
	}

	private function readJsonFile($path='')
	{
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
				$this->setError("Error while JSON decoding file: $path. Error: ".$json_err_arr[$json_error], __LINE__);
				return false;
			}
			else{
				return $file_content;
			}
		}
		else{
			$this->setError("Unable to open file: $path", __LINE__);
			return false;
		}
	}
	
	public function writeJsonFile($path='', $dataObj):bool
	{
		$handle = fopen($path,"w");
		if($handle){
			if(!fwrite($handle, json_encode($dataObj, JSON_PRETTY_PRINT))){
				$this->setError("Unable to write file: $path", __LINE__);
				return false;
			}
			fclose($handle);
			return true;
		}
		else{
			$this->setError("Unable to open file: $path", __LINE__);
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
	
	private function setError($err="", $line=-1):void
	{
		if($err===NULL){
			$this->error = array();
		}
		else{
			if(empty($this->error)){
				$this->error["trace"] = array();
			}
			$this->error["message"] = $err;
			$this->error["line"] = $line;
			$this->error["trace"][] = array(
				"message"	=> $err,
				"line"		=> $line,
			);
		}
	}
}

?>
