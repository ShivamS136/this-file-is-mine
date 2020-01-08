<?php
defined("DOCUMENT_ROOT")
or define("DOCUMENT_ROOT", realpath(dirname(__FILE__,2)));

$config = array(
	"db" => array(
		"db1" => array(
			"dbname" => "database1",
			"username" => "dbUser",
			"password" => "pa$$",
			"host" => "localhost"
		),
		"db2" => array(
			"dbname" => "database2",
			"username" => "dbUser",
			"password" => "pa$$",
			"host" => "localhost"
		)
	),
	"urls" => array(
		"baseUrl" => "http://example.com"
	),
	"paths" => array(
		"resources" => "/path/to/resources",
		"images" => array(
			"content" => $_SERVER["DOCUMENT_ROOT"] . "/images/content",
			"layout" => $_SERVER["DOCUMENT_ROOT"] . "/images/layout"
		)
	)
);

ini_set("error_reporting", "true");
error_reporting(E_ALL|E_STRCT);

?>
