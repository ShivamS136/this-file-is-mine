<?php
	require_once (DOCUMENT_ROOT."/FFDB/FFDB.php");
	class IndexModel
	{
		function __construct()
		{
			
		}
		
		public function testDB($db_name='')
		{
			$db = new FFDB("tfim");
			print_r($db->db("desc"));
			// print_r($db->db("remove"));
			$colArr = array(
				"col_1",
				"col_2",
				"col_3"
			);
			print_r($db->table("create","temp_tab", $colArr));
			print_r($db->error);
			print_r($db->db("desc"));
		}
	}
	
?>
