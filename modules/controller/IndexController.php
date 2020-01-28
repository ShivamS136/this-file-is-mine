<?php
	require_once(DOCUMENT_ROOT."/modules/model/IndexModel.php");
	/**
	 * This class is controller class for the webApp
	 * Here the actions are passed for the AJAX and pageload
	 */
	class IndexController
	{
		public $IndexModel;
		public function __construct()
		{
			$this->IndexModel = new IndexModel();
		}
		
		/**
		 * This method is the base method of the class 
		 * Here the actions is taken according to the action parameter passed
		 *
		 * @author  Shivam Sharma <shivamshrm235@gmail.com>
		 * @since   2019-12-16
		 * @return void This method includes the view after calling model(s)
		 */
		public function actionIndex()
		{
			$action = isset($_POST["action"]) ? $_POST["action"] : "";
			if($action)
			{
				$this->IndexModel->testDB($action);
			}
			else {
				include("modules/view/index.php");
			}
		}
	}
?>
