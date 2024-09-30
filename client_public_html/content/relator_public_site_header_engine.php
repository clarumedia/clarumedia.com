<?php 

$objAppHeader->arrStyleSheetLinks["clarumedia"]="/client/assets/site.css";
$objAppHeader->boolRequireLogin=false;
//Reset all meta tags
$objAppHeader->arrMetaTags["description"]=["description"=>$oCMSServer->arrFile["description"]];
$objAppHeader->arrMetaTags["keywords"]=["keywords"=>$oCMSServer->arrFile["keywords"]];
$objAppHeader->arrMetaTags["robots"]=["name"=>"robots","content"=>"index, follow"];
$objAppHeader->arrMetaTags["DC.date"]=["name"=>"DC.date","content"=>$oCMSServer->arrFile["modified"]];


$objAppHeader->objToolBarSearchForm->arrFormTagAttributes["action"]="/search";
$objAppHeader->objToolBarSearchForm->arrFormControls["txtDefaultSearch"]=[
	"title"=>" ",
	"control_type"=>"search",
	"placeholder"=>"Search Clarumedia Site",
	"aria-label"=>"Search Clarumedia Site",
	"class"=>"cbframeTxtDefaultSearchInput",
	"value"=>$_REQUEST["txtDefaultSearch"]
];



if ($User->UserID){
	$strEditLink="/app-virtual-file-edit.php?file_id=" . 	$oCMSServer->arrFile["file_id"];
} else {
	$strEditLink="/relator/dms/";
}

$objAppHeader->arrLinks[]=[
	"title"=>"DMS File " . $oCMSServer->arrFile["file_id"] . " v" . $oCMSServer->arrFile["version"],
	"href"=>$strEditLink,
];



$objAppHeader->arrLinks[]=[
	"title"=>"Modified " . ConvertCanonicalDateToHumanDate($oCMSServer->arrFile["modified"]),
	"href"=>$strEditLink,
	"src"=>"/cbframe/images/information.png",
];

if (!$arrBreadCrumbs){

	$arrBreadCrumbs=[];

	if ($oCMSServer->arrFile["file_collection_id"] !=1){
		$arrBreadCrumbs[]=[
			"title"=>$oCMSServer->arrFile["arrFileCollection"]["collection_name"],
			"href"=>$oCMSServer->arrFile["arrFileCollection"]["default_url_prefix"],
		];

	}

	$arrBreadCrumbs[]=[
			"title"=>$oCMSServer->arrFile["title"],
	];


}


$objAppHeader->Go($arrBreadCrumbs); 


?>