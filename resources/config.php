<?php
defined("DOCUMENT_ROOT")
or define("DOCUMENT_ROOT", realpath(dirname(__FILE__,2)));

require_once DOCUMENT_ROOT.'/vendor/autoload.php';

ini_set("error_reporting", "true");
error_reporting(E_ALL|E_STRCT);

?>
