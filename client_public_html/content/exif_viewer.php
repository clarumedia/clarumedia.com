<?php


if (realpath(__FILE__) == realpath($_SERVER['SCRIPT_FILENAME'])) {
	print "Invalid attempt to access a cbframe component";
	exit;
}




if (!$_FILES || $_FILES["file1"]["error"]){	
	

	$oF=new HTMLForm($db);

	$oF->arrFormControls["file1"]=array("title"=>" ","control_type"=>"file","accept"=>"image/*");	

	$oF->strSubmitButtonsArray["btnGo"]=array("name"=>"btnGo","value"=>"Upload");

	if (!$oF->showForm()){
		$ErrorHandler->Go($oF->strError);
		exit;
	}
	
	
	exit;	
} 




if ($_FILES["file1"]["error"]){
	$ErrorHandler->Go("Please press the browse button to select a file, then upload !");	
	exit;
}



print "<p><strong>Metadata extract from " . $_FILES["file1"]["name"] . "</strong>. (<a href=''>Select another file</a>)<p>";

$im = new imagick($_FILES["file1"]["tmp_name"]);

$arrMeta = $im->getImageProperties("*");

unset($arrMeta["exif:MakerNote"]);
unset($arrMeta["date:create"]);
unset($arrMeta["date:modify"]);

$arrTableData=array();

foreach($arrMeta as $strFieldName=>$strValue){

	$arrFieldNameSplit=explode(":",$strFieldName);
	

	$arrTableData[]=array("fieldname"=>$arrFieldNameSplit[1],"collection"=>$arrFieldNameSplit[0],"value"=>$strValue);
	
	
	
	
}


if ($arrMeta["exif:GPSLatitude"]){
	
	$arrGPS=array();
	
	$arrGPS["latitude"]=get_gps_coordinate("exif:GPSLatitude",$arrMeta);
	$arrGPS["longitude"]=get_gps_coordinate("exif:GPSLongitude",$arrMeta);
	$arrGPS["google_maps_link"]="https://www.google.co.uk/maps/place/" . urlencode($arrGPS["latitude"] . " " . $arrGPS["longitude"]) . "/data=!4m5!3m4!1s0x0:0x0!8m2!3d48.4842528!4d-120.6785444!5m1!1e4";
	
	if (!$arrGPS["latitude"]){
		$ErrorHandler->Go("Unable to get GPS data from Exif: " . $arrMeta["exif:GPSLatitude"]);
	} else {

		print "<p>GPS Location: <a href=" . chr(39) . $arrGPS["google_maps_link"] . chr(39) . " target='_blank'>" .$arrGPS["latitude"] . " " . $arrGPS["longitude"] . "</a>";
		
	}
	
	
	
	
	//https://www.google.co.uk/maps/place/48%C2%B029'03.3%22N+120%C2%B040'42.8%22W/@48.4821973,-120.6831812,15z/data=!4m5!3m4!1s0x0:0x0!8m2!3d48.4842528!4d-120.6785444!5m1!1e4
	
	
} else {
	print "<p>No GPS Info available";
}


$oT=new html_table();

$oT->initialize_columns($arrTableData);
$oT->strFriendlyTable="Raw Image Metadata";

$oT->boolShowColumnTitles=false;

if (!$oT->showtable()){
		$ErrorHandler->Go($oT->strError);
		exit;	
}


function get_gps_coordinate($strExifType,$arrMeta){
		
	$s=$arrMeta[$strExifType];
	$strRef=$arrMeta[$strExifType . "Ref"];
	
	if (!$s){
		return false;
	}
		
	$arrStuff=explode(",",$s);
	
	$lngCounter=0;
	
	$strRes="";
	
	foreach($arrStuff as $strFraction){
		
		$arrFraction=explode("/",$strFraction);
		
		$lngDecimalFraction=safeNumber($arrFraction[0] /$arrFraction[1]);
		

		switch($lngCounter){
		
			case 0:
				$strRes=$lngDecimalFraction . "°";
			break;
			
			case 1:
				$strRes .=$lngDecimalFraction . chr(39);
			break;
			
			case 2:
				$strRes .=$lngDecimalFraction . chr(34);
			break;
			
		}
		
		
		
		
		$lngCounter++;
		
	}
	
	$strRes.=$strRef;
	
	return $strRes;
	
}




?>