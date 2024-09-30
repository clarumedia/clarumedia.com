<?php

include($strCBFrameRelativePath .  '/lib/class.upload.php');



if ($SREQUEST["adodb-add-record"]=="guestbook") {

	$boolProceed=TRUE;


	//Not logged in and no Captcha Code = SPAM
	if ($SREQUEST["CAPTCHA_CODE"] =="" && $User->UserID ==""){
		ob_clean();
		header("Location:http://127.0.0.1");
		exit;
	}


	if (strpos(strtolower($SREQUEST["message"]),"http://")!==FALSE) {
		//$ErrorHandler->Go("ERROR !!! Hyperlinks are not allowed in the guest book");

		ob_clean();
		header("Location:http://127.0.0.1");
		exit;
		$boolProceed=FALSE;
	}


	if (strpos("<a href",$SREQUEST["message"]) >0) {
		//$ErrorHandler->Go("ERROR !!! Hyperlinks are not allowed in the guest book");
		ob_clean();
		header("Location:http://127.0.0.1");
		exit;


		$boolProceed=FALSE;
	}



	if ($SREQUEST["CAPTCHA_CODE"] !="") {
		include($strCBFrameRelativePath . '/securimage/securimage.php');
		  $img = new securimage();
		  $valid = $img->check($SREQUEST["CAPTCHA_CODE"]);

		if(!$valid){
			$ErrorHandler->Go("ERROR !!! Validation Code " .$SREQUEST["CAPTCHA_CODE"] ." Does Not Match - please retry");
			$boolProceed=FALSE;
		}




	}

	if ($boolProceed){



		$strTransmissionAddress="chris@barnesconsultants.com";
		$headers = "From: clarumedia_site@clarumedia.com\r\n";
		$headers .="Reply-To: www@clarumedia.com\r\n";
		$headers .='X-Mailer: PHP/' . phpversion();

		$strSubject="Guestbook Message";
		$strMessage=$SREQUEST["message"] . "\n\n" . print_r($SREQUEST,true);
		if (!mail($strTransmissionAddress, $strSubject, $strMessage, $headers)) {
			$ErrorHandler->Go("Failed to send email");
		}

		$Updater=new ADODBAutoFormHandler($db);
		$Updater->table="guestbook";
		$Updater->strPrimaryKey="gb_id";
		$Updater->SetExtraFieldHash("http_log_id", $User->lngSecurityLogID);


		if ($Updater->FormAutoSQLUpdate() != TRUE) {
			$ErrorHandler->Go($Updater->strError);
		} else {


			$lngNewRecord=$Updater->varInsertID;
			//$rdr="track_edit.php?gb_id=" .$lngNewRecord. "&error-message=" . urlencode("New Track Added") ;
			//ob_clean();
			//header("Location: $rdr");
			print "<h3>Many thanks for getting in touch. We will get back to you ASAP !</h3>";
			exit;
		}
	}

}


//Get logged in users email address
if ($User->UserID) {

	$sql="SELECT * FROM users WHERE userid=" . $User->UserID;
	$rs = $db->Execute($sql);
	if (!$rs) {
		$ErrorHandler->Go("Error fetching your email address: ".$db->ErrorMsg() . " SQL=" . $sql);
		exit;
	} else {
		$strUserEmail=$rs->fields["email"];
	}


}
?>

<p>

<?PHP


$Guestbook= new ADODBAutoForm($db);
$Guestbook->table="guestbook";
$Guestbook->strPrimaryKey="gb_id";

$Guestbook->strThisControlHasFocus="fullname";
$Guestbook->arrFormControls["gb_date"]=array("title"=>"gb date","display_mode"=>"hidden","value"=>"now");



if ($User->UserID) {
	$Guestbook->arrFormControls["message"]=array("title"=>"Message", "extra_html"=>"rows='10' style='width:600px' class='form-control'");
	$Guestbook->arrFormControls["email"]=array("display_mode"=>"hidden","value"=>$strUserEmail);
	$Guestbook->arrFormControls["userid"]=array("display_mode"=>"hidden","value"=>$User->UserID);


} else {
	$Guestbook->arrFormControls["fullname"]=array("title"=>"Name","extra_html"=>"class='form-control'");
	$Guestbook->arrFormControls["email"]=array("title"=>"Email","value"=>$strUserEmail,"extra_html"=>"class='form-control' ");

	$Guestbook->arrFormControls["message"]=array("title"=>"Message", "extra_html"=>"rows='10' style='width:600px' class='form-control'");
	$Guestbook->arrFormControls["CAPTCHA_CODE"]=array("title"=>"Validation Code","control_type"=>"captcha", "required"=>"1","extra_html"=>"class='form-control'");

}


//$Guestbook->boolLocked=TRUE;
$Guestbook->strFriendlyFormName="Please leave us a message";
$Guestbook->ztabhtml="  class='tableForm' ";


if ($Guestbook->showform() != TRUE) {
	print "Error: " . $Guestbook->strError;
}


?>