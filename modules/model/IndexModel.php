<?php
	require_once (DOCUMENT_ROOT."/FFDB/FFDB.php");
	class IndexModel
	{
		function __construct()
		{
			
		}
		
		public function testDB($db_name='')
		{
			$db = new FFDB($db_name);
			print_r($db->db("desc"));
			// print_r($db->db("remove"));
			print_r($db->error);
		}
	}
	
?>
