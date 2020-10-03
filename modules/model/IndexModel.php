<?php
class IndexModel
{
	// public $db;
	function __construct()
	{
		// $this->db = new \Filebase\Database([
		// 	'dir' => DOCUMENT_ROOT."/Filebase"
		// ]);
	}

	public function insertTempData()
	{
		$db_proj = new \Filebase\Database([
			'dir' => DOCUMENT_ROOT."/Filebase/projects"
		]);
		$proj = $db_proj->get("leap");
		$proj->name = "LEAP";
		$proj->created_at = strtotime("now");
		$proj->created_by = "56532";
		$proj->files = array("index.php", "composer.json", "modules/model/Model.php","modules/controller/Controller.php");
		$proj->save();
	}

	public function getFiles($emp_id)
	{
		$db_proj = new \Filebase\Database([
			'dir' => DOCUMENT_ROOT."/Filebase/projects"
		]);
		$db_user = new \Filebase\Database([
			'dir' => DOCUMENT_ROOT."/Filebase/users"
		]);
		$files = array();
		$user = $db_user->get($emp_id);
		foreach ($user->projects as $key => $projName) {
			$proj = $db_proj->get("leap");
			$files[$projName] = $proj->field("files");
		}
		return $files;
	}
}
	
?>
