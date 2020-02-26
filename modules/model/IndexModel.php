<?php
	require_once (DOCUMENT_ROOT."/FFDB/FFDB.php");
	class IndexModel
	{
		function __construct()
		{
			
		}
		
		public function testDB($action=1)
		{
			$db = new FFDB("tfim");
			// print_r($db->error);
			if($action == 1){
				var_dump($db->db("exists"));
				print_r($db->error);
			}
			else if($action ==2){
				print_r($db->db("desc"));
				print_r($db->error);
			}
			else if($action ==3){
				var_dump($db->table("exists","temp_tab"));
				print_r($db->error);
			}
			else if($action ==4){
				var_dump($db->table("exists","non_existent"));
				print_r($db->error);
			}
			else if($action ==5){
				print_r($db->table("desc","temp_tab"));
				print_r($db->error);
			}
			else if($action ==6){
				print_r($db->table("desc","non_existent"));
				print_r($db->error);
			}
			else if($action ==7){
				var_dump($db->table("drop","temp_tab",true));
				print_r($db->error);
			}
			else if($action ==8){
				var_dump($db->table("drop","non_existent"));
				print_r($db->error);
			}
			else if($action ==9){
				print_r($db->table("desc","temp_tab"));
				print_r($db->error);
			}
			else if($action ==10){
				$colArr = array(
					"col_1",
					"col_2",
					"col_3"
				);
				print_r($db->table("create","temp_tab", $colArr));
				print_r($db->error);
			}
			else if($action ==11){
				print_r($db->table("desc","temp_tab"));
				print_r($db->error);
			}
			else if($action ==12){
				$colArr = array(
					"col_1"	=> array(
						"type"		=> "number",
						"constraint"	=> "primary key",
						"autoincr"	=> true,
						"default"	=> 0,
					),
					"col_2"	=> array(
						"type"		=> "bool",
						"constraint"	=> "primary key",
						"autoincr"	=> true,
						"default"	=> 0,
					),
					"col_3"	=> array(
						"type"		=> "string",
						"constraint"	=> "not null",
						"autoincr"	=> true,
						"default"	=> "hello",
					),
				);
				print_r($db->table("create","temp_tab_2", $colArr));
				print_r($db->error);
			}
			else if($action ==13){
				print_r($db->table("desc","temp_tab_2"));
				print_r($db->error);
			}
			else if($action ==14){
				var_dump($db->db("drop"));
				print_r($db->error);
				print_r($db->db("desc"));
			}
		}
	}
	
?>
