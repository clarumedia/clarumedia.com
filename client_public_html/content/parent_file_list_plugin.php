<?php


$sqlTable =  new  ADODBsqltable_files($db);

$sqlTable->objToolBarSearchForm->arrFormControls=[];
$sqlTable->boolSpreadsheetLink=false;


if (!$User->UserID){
	$sqlTable->SetFieldDataHyperlinkHash("title","");
    $sqlTable->arrVisibleColumns=["enc_type_icon","title","description","size_mb","version","created","modified"];
}


$sql="SELECT f.*,
'' as size_mb,
ft.file_type 
FROM files f INNER JOIN file_types ft ON f.file_type_id=ft.file_type_id
WHERE  f.parent_file_id=" . $GLOBALS["oCMSServer"]->arrFile["file_id"] . " 
AND f.file_type_id=1
AND f.published IS NOT NULL";




$sqlTable->strPrimaryKey="file_id";



if ($sqlTable->OrderByFromCGI) {
	$sql .=" ORDER BY " . $sqlTable->OrderByFromCGI;
} else {
	$sql .=" ORDER BY f.title";
}



$sqlTable->sql=$sql;

$sqlTable->strHyperlinkToAddNewRecord="<a href='#' onclick=openTiddlyWindow('/app-virtual-file-add.php?file_type_id=1&file_collection_id=" . $GLOBALS["oCMSServer"]->arrFile["file_collection_id"] . "&parent_file_id=" . $GLOBALS["oCMSServer"]->arrFile["file_id"] . "&popup=1')>";

if (!$sqlTable->showtable()) { ;
	$ErrorHandler->Go("Error: " . $sqlTable->strError);
}

