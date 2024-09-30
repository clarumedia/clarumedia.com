<?php


$oC=new HTMLCalendar("month",$SREQUEST);

$arrToday=ExplodeCanonicalDate(GetDateForMySQLDateField());

$oC->arrMonthWithDaysDisplayConfig["table"]["style"]="width:100%;";
$oC->arrMonthWithDaysDisplayConfig["day_table_add_appointment_href"]="<a href='/ts/visits?prepare_add_new=1&activity_date=[year]-[month]-[day] " . $arrToday[3] . ":" . $arrToday[4] . "')>";
$oC->arrMonthWithDaysDisplayConfig["day_table_zoom_href"]="<a href='/ts/visits?activity_date=[year]-[month]-[day]')>";


if ($SREQUEST["adodb-add-record"]=="crm_log"){

        $arrActivityDateExploded=ExplodeCanonicalDate($SREQUEST["activity_date"]);

        $arrUp=$SREQUEST;
        $arrUp["activity"]="note";
        $arrUp["job_id"]="10257";
        $arrUp["about_userid"]="2071";



        $oCRM=new CRMLog($db,$User);

        $arrNewCRM=$oCRM->Insert($arrUp);


        if (!$arrNewCRM){
                $ErrorHandler->Go($oCRM->strError);
                exit;
        }


        CleanRedirect("/ts/visits?year=" . $arrActivityDateExploded[0] . "&month=" . $arrActivityDateExploded[1] . "&day=" . $arrActivityDateExploded[2]);


        exit;

}

if ($SREQUEST["prepare_add_new"]){

        $arrNewMessage=$SREQUEST;

        $objFormCRM= new ADODBAutoForm($db);
        $objFormCRM->table="crm_log";
        $objFormCRM->strPrimaryKey="crm_id";
        $objFormCRM->ztabhtml=" class='tableForm' ";
        $datCanonNow=getDateForMysqlDateField();
        $objFormCRM->strFormTitle="Add Diary Entry";


        $objFormCRM->arrFormControls["subject"]=array("title"=>"Subject","value"=>$arrNewMessage["subject"],"css_class"=>"txtCRMEditControls","max_length"=>100,"value"=>"Home Visit");
        $objFormCRM->arrFormControls["activity_date"]=array("title"=>"Started","control_type"=>"datetime5","value"=>$arrNewMessage["activity_date"],"required"=>true);
        $objFormCRM->arrFormControls["activity_end_date"]=array("title"=>"Ended","control_type"=>"datetime5","value"=>$arrNewMessage["activity_date"],"required"=>true);




        if (!$objFormCRM->showForm()){
                $ErrorHandler->Go($objFormCRM->strError);
                exit;
        }

        exit;


}

if ($SREQUEST["activity_date"]){

	print "<a href='/ts/visits'><img src='/cbframe/images/cal.gif'></a><p>";

	$db->SetTransactionMode("READ UNCOMMITTED");

	$sqlTable =  new  ADODBsqltable($db);

	$sqlTable->arrTableTagAttributes["style"]="width:100%;";
	$sqlTable->strFriendlyTableName="";
	$sqlTable->strItemsDescriptor="Entries for " . ConvertCanonicalToLocaleDate($SREQUEST["activity_date"]);
	$sqlTable->boolSpreadsheetLink=false;
	$sqlTable->lngPageSize=50;
	$sqlTable->boolShowTimeOnDates=true;
	$sqlTable->strHyperlinkToAddNewRecord="<a href='/ts/visits?prepare_add_new=1&activity_date=" .$SREQUEST["activity_date"] . " " .  $arrToday[3] . ":" . $arrToday[4] . "'>";
	
    $sqlTable->arrColumnProperties["hours"]["data_type"]="T";

	$sqlTable->arrVisibleColumns=["activity_date","finished","hours","subject","creator","created"];
	
	$sqlTable->sql="SELECT crm_id,activity_date,subject,
    DATE_ADD(activity_date, INTERVAL billable_time_minutes MINUTE) as finished,
    SEC_TO_TIME(billable_time_minutes *60) AS hours,
    u.display_name as creator,c.created
	FROM crm_log c LEFT JOIN users u ON c.created_by=u.userid
	WHERE job_id=" . $db->qstr(10257) . " AND DATE(activity_date)=" . $db->qstr($SREQUEST["activity_date"]) . " ORDER BY activity_date";
	
	if ($sqlTable->showtable() !=TRUE) { ;
		$ErrorHandler->Go("Error: " . $sqlTable->strError);
	}
	
	return 1;


}


$sql="SELECT *,activity_date as date_time,
    DATE_ADD(activity_date, INTERVAL billable_time_minutes MINUTE) as date_time_end,
    billable_time_minutes as duration_minutes,
    subject as appointment_title
        FROM crm_log WHERE job_id='10257' AND activity_date >=" . $db->qstr($oC->arrMonthInfo["first_of_month"]) . " AND activity_date <=" . $db->qstr($oC->arrMonthInfo["last_of_month"]) . " ORDER BY activity_date";



$arrCRMLogs=$db->GetAssoc($sql);

if ($arrCRMLogs===false){
    $ErrorHandler->Go("Failed to get records" .$db->strError);
    return 1;
}

print "<p>";

print $oC->MonthsDisplay();

print "<p>";

print $oC->MonthWithDaysDisplay($arrCRMLogs);


?>