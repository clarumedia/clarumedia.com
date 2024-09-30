<?php 
header("Content-Type: text/xml"); 


$arrRedirects=[
    /* Armourcoat */
    "+441732460668"=>[
        "Dial"=>"+447881540974"
    ],
    /* Baldwin Boxall */
    "+441892664422"=>[
        "Dial"=>"+447881540974"
    ], 
    /* Chris 
    "+447881540974"=>[
        "Say"=>"Chris, stop playing with yourself",
    ], */

];

$arrRedirect=$arrRedirects[$_REQUEST["From"]];

if (!$arrRedirect){
    $arrRedirect=[
        "Dial"=>"+447881540974",
    ];
}



print "<?xml version=" . chr(34) . "1.0" . chr(34) . " encoding=" . chr(34) . "UTF-8" . chr(34) . "?>\n";

print "<Response>\n";

print "<Play>https://clarumedia.com/thankyou_for_calling_clarumedia.mp3</Play>\n";

foreach($arrRedirect as $strAction=>$strValue){
    print "<" . $strAction . ">" . $strValue . "</" . $strAction . ">\n";
}


print "</Response>\n";


