<?php

$_SERVER["DOCUMENT_ROOT"]=dirname(__FILE__) . "/../public_html";
include('../public_html/app-start.php');

$oCLI=new cbframe_cli_helper($GLOBALS["arrCBFApplicationVariables"]["system_monitor_emails_to"]);
$oCLI->boolSuppressEcho=false;
$oCLI->enable_echo_logging();
$oCLI->ensure_cli_context();


$sql="SELECT file_id,title,filename,path FROM files WHERE path LIKE '/home/clarumedia.com/mp3/%'";

$arrFiles=$db->GetAssoc($sql);

foreach($arrFiles as $arrFile){

	$arrUp=array();
	
	$arrUp["file_id"]=$arrFile["file_id"];
	$arrUp["path"]="/home/clarumedia.com/virtual_files/" . $arrFile["file_id"] . ".dat";
	
	if (!is_file($arrFile["path"])){

		$oCLI->d_echo($arrFile["file_id"] . ": path does not exist " . $arrFile["path"]);
		continue;
			
	}
	
	
	if (!cdb_rename($arrFile["path"],$arrUp["path"],$strError)){
		$oCLI->death($strError );
	}
	
	$oCLI->d_echo("Moved " . $arrFile["path"] . " to " . $arrUp["path"]);
	
	$sql="UPDATE files SET path=" . $db->qstr($arrUp["path"] ) . " WHERE file_id=" . $db->qstr($arrUp["file_id"] );
	
	if (!$db->Execute($sql)){
		$oCLI->death($db->ErrorMsg());
	}
	
	
	$oCLI->d_echo($sql);
	

	
}



//$oCLI->death($strError);


//$oCLI->end($oCLI->pretty_array($arrStats));

?>