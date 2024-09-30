<?php


global $db;
global $User;
global $ErrorHandler;




if ($SREQUEST["adodb-add-record"]=="users" ) {

		$objUserRecord=new UserRecord($db,$User);
		$objUserRecord->boolHaltCreationOnDuplicate=FALSE;
		$objUserRecord->boolAutoCapsContactDetailsOnNew=true;

		$arrUp=$SREQUEST;

		$arrUp["comment"]="slow_broadband=" . $SREQUEST["slow_broadband"] . "\n";
		$arrUp["comment"].="intermittent_broadband=" . $SREQUEST["intermittent_broadband"] . "\n";
		$arrUp["comment"].=$SREQUEST["notes"];

		$lngNewRecord=$objUserRecord->CreateUser($arrUp);		
		
		if (!$lngNewRecord){
			$ErrorHandler->Go("Sorry ! There was an error writing your record",  $objUserRecord->strError);
			return 1;				
		}
		
	
		
		
		print "<h1>THANK YOU FOR PROVIDING YOUR INFORMATION !</h1>";
		return 1;
		
}

print "<h2>" . $GLOBALS["oCMSServer"]->arrFile["title"] . "</h2>";

if ($GLOBALS["oCMSServer"]->arrFile["description"]){
	print "<p style='font-weight:bold'>" . $GLOBALS["oCMSServer"]->arrFile["description"] . "</p>";
}

if ($GLOBALS["oCMSServer"]->arrFile["ascii_content"]){
	print "<p>" . $GLOBALS["oCMSServer"]->arrFile["ascii_content"] . "</p>";
}



$ContactOrgForm= new ADODBAutoForm($db);
$ContactOrgForm->table="users";
$ContactOrgForm->strPrimaryKey="userid";
$ContactOrgForm->strFriendlyFormName="Marden Broadband Petition Form";
$ContactOrgForm->ztabhtml=" class='tableForm' ";

$ContactOrgForm->arrFormControls["job_id"]=array("display_mode"=>"hidden","value"=>10254);
$ContactOrgForm->arrFormControls["ls_id"]=array("display_mode"=>"hidden","value"=>9);
$ContactOrgForm->arrFormControls["last_contact"]=array("display_mode"=>"hidden","value"=>"now");


$ContactOrgForm->arrFormControls["first_name"]=array("title"=>"First Name","required"=>1);
$ContactOrgForm->arrFormControls["last_name"]=array("title"=>"Last Name","required"=>1);

$ContactOrgForm->arrFormControls["email"]=array("title"=>"Email","required"=>1);

$ContactOrgForm->arrFormControls["land_line"]=array("title"=>"Land Line Telephone Number","required"=>1,"description"=>"Where the service is provided");


$arrUserTypes=array("1"=>"Household","2"=>"Business");
$ContactOrgForm->arrFormControls["user_type_id"]=array("title"=>"User Type","control_type"=>"array_driven_drop_down","array_driven_drop_down_populating_array"=>$arrUserTypes,"required"=>"1");



$ContactOrgForm->arrFormControls["address1"]=array("title"=>"Address1","required"=>1,"description"=>"Where the service is provided");
$ContactOrgForm->arrFormControls["address2"]=array("title"=>"Address2");
$ContactOrgForm->arrFormControls["address3"]=array("title"=>"Address3");
$ContactOrgForm->arrFormControls["town"]=array("title"=>"Town","value"=>"Marden");
$ContactOrgForm->arrFormControls["county"]=array("title"=>"County","value"=>"Kent");
$ContactOrgForm->arrFormControls["postal_code"]=array("title"=>"Postal Code","required"=>1);

$ContactOrgForm->arrFormControls["current_broadband_provider"]=array("title"=>"Current Broadband Provider","required"=>1,"description"=>"eg BT, TalkTalk, Plusnet, Virgin");


$ContactOrgForm->arrFormControls["slow_broadband"]=array("title"=>"Slow Broadband","control_type"=>"boolean_checkbox");

$ContactOrgForm->arrFormControls["intermittent_broadband"]=array("title"=>"Intermittent Broadband","control_type"=>"boolean_checkbox");
$ContactOrgForm->arrFormControls["notes"]=array("title"=>"Comment");


if (!$ContactOrgForm->showform()) {
	$ErrorHandler->Go("Error: " . $ContactOrgForm->strError);
	return 1;
}


?>

<p>&nbsp;<br></p>
