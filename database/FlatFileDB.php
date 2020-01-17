<?php

/**
 *    Flat File database written in JSON
 */
class FlatFileDB 
{
	public $db_name="";
	public $db_cred = array();
	private $db_root = "";
	public $error=array();
	function __construct($db_name="", $password="", $createMode=true)
	{
		$this->db_root = DOCUMENT_ROOT."/database/db/";
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
		if(!$db_name && preg_match('/[^a-z_\-0-9]/i', $db_name)){
			$this->setError("Invalid Database Name, Only Alphanumeric, _ and - are allowed",1);
			return false;
		}
		return true;
	}
	
	// public function removeDb($db_name):bool
	// {
	// 	$this->setError(NULL);
	// 	if(is_dir($this->db_root.$db_name)){
	// 		rmdir();
	// 	}
	// 	else{
	// 
	// 	}
	// }
	
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
	
	private function connectDB($db_name="", $password="", $createMode=true):bool
	{
		$this->setError(NULL);
		if(is_dir($this->db_root.$db_name)){
			$cred = file_get_contents($this->db_root.$db_name."/cred.json");
			$this->db_cred = json_decode($cred);
			return true;
		}
		else if($createMode){
			if(mkdir($this->db_root.$db_name, 0777, true)){
				$handle = fopen($this->db_root.$db_name."/cred.json", "w+");
				$cred = new stdClass();
				$cred->password = $password;
				$cred->createdAt = date("d-M-Y H:i:s");
				$cred->updatedAt = date("d-M-Y H:i:s");
				fwrite($handle, json_encode($cred, JSON_PRETTY_PRINT));
				fclose($handle);
				$this->db_cred = $cred;
				return true;
			}
			return false;
		}
		return false;
	}
}

?>
