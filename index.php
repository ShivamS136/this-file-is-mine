<?php
	/**
	 * This is the base file for the web server
	 * We'll follow MVC so this page just create instance of controller
	 * and calls the base method with default action
	 */
	require_once("modules/controller/IndexController.php");
	
	$indexController = new IndexController();
	$indexController->actionIndex();
?>
