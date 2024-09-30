<?php

$arrNaughty=array();
$arrNaughty[]="myadmin";
$arrNaughty[]="setup.php";


foreach($arrNaughty as $strNaughty){

	if (stripos($_SERVER["REQUEST_URI"],"myadmin") >0){
		$ErrorHandler->Go("Hackers from " . $_SERVER["REMOTE_ADDR"] . " are not welcome", "Attempting to hack via " . $_SERVER["REQUEST_URI"]);
		CleanRedirect("http://127.0.0.1/");
		exit;
	}
}

?>