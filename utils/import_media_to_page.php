<?php

$_SERVER["DOCUMENT_ROOT"]=dirname(__FILE__) . "/../public_html";
include('../public_html/app-start.php');



$oCLI=new cbframe_cli_helper("chris@barnesconsultants.com");
//$oCLI->boolSuppressEcho=true;
$oCLI->enable_echo_logging();
$oCLI->ensure_cli_context();

$sql="SELECT * FROM users WHERE permissions LIKE '%|1|%'";

$arrAdmin=$db->GetRow($sql);

$User=new User($db);
$User->UserID=$arrAdmin["userid"];
$User->arrUserInfo=$arrAdmin;

$arrStats["parent_file_id"]=$argv[1];
$arrStats["path"]=$argv[2];


if (!$arrStats["parent_file_id"]){
	$oCLI->death("Missing parent_file_id (command line first argument)");
}

if (!$arrStats["path"]){
	$oCLI->death("Missing path (command line second argument)");
}

$arrFiles=cdb_dir($arrStats["path"],$strError);

if ($arrFiles===false){
	$oCLI->death($strError);	
}

$arrStats["backup_dir"]="/tmp/hoover_backup" . $arrStats["path"];

if (!is_dir($arrStats["backup_dir"])){

	if (!cdb_mkdir($arrStats["backup_dir"],0777,true,null,$strError)){
		$oCLI->death($strError);		
	}

}

$oFile=new cbframe_virtual_file($db,$User);	

$oFile->arrDisallowedSuffixes=array();

if (!$oFile->load_file_info($arrStats["parent_file_id"])){
	$oCLI->death($oFile->strError);	
}

$arrParentFile=$oFile->arrFile;

foreach($arrFiles as $arrFile){
		
	if (!is_file($arrFile["fullpath"])){
		$oCLI->d_echo("Not a file: " .$arrFile["fullpath"]);
		continue;
	}	
				
	
	$arrNewFile=array();
	$arrFileInfo=array();
	
	$arrNewFile["name"]=$arrFileCollection["default_url_prefix"] . $arrStats["default_url_prefix_append"] . "/" . $arrFile["filename"];
	$arrNewFile["tmp_name"]=$arrFile["fullpath"];
	$arrNewFile["size"]=$arrFile["filesize"];	

	$arrFileInfo["file_collection_id"]=$arrParentFile["file_collection_id"];
	$arrFileInfo["parent_file_id"]=$arrParentFile["file_id"];
	$arrFileInfo["private"]=$arrParentFile["private"];
	
	$arrFileInfo["title"]=($arrFile["filename"]);
	$arrFileInfo["title"]=str_replace("-","_",$arrFileInfo["title"]);
	$arrFileInfo["title"]=humaniseUnderscoredFieldName($arrFileInfo["title"]);
	
	$arrNewFile=$oFile->receive_new_file_php($arrNewFile,$arrFileInfo);
	
	
	if (!$arrNewFile){
			$oCLI->death($oFile->strError);
	}

	if (substr($arrNewFile["enc_type"],0,5)=="image"){
		
		$oFThumb=new cbframe_virtual_file_thumbnail($db,$User);
			
		if (!$oFThumb->generate_thumbnail_for_file_id($arrNewFile["file_id"])){
			$oCLI->death($oFThumb->strError);
		}
			
	}

	
	$oCLI->d_echo("Processed " . $arrFile["fullpath"]);

	
	$arrStats["files_processed"]++;


}


//$oCLI->end($oCLI->pretty_array($arrStats));


?>