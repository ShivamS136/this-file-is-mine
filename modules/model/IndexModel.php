<?php
	require_once (DOCUMENT_ROOT."ffdb/FFDB.php");
	class ClassName
	{
		function __construct()
		{
			
		}
		
		public function testDB($db_name='')
		{
			$db = new FFDB("tfim");
			print_r($db->DB("desc"));
		}
	}
	
?>
