<?php

$sql="SELECT f1.*,
fparent.filename as parent_filename,
fparent.title as parent_title,
fparent.parent_file_id as parent_parent_file_id 
FROM files f1 INNER JOIN files fparent ON f1.parent_file_id=fparent.file_id 
WHERE fparent.parent_file_id=" . $oCMSServer->arrFile["file_id"] . "  
AND f1.file_type_id=5 AND f1.published IS NOT NULL AND fparent.published IS NOT NULL
ORDER BY fparent.title";

$arrImages=$GLOBALS["db"]->GetAssoc($sql);
if ($arrImages===false){
	$ErrorHandler->Go("Child image error",$GLOBALS["db"]->ErrorMsg());
	return 1;
}

?>

<ol>
	<li>Click on each photo to view the FULL SIZE VERSION</li>
    <li>Right Click (Control + Click on MacOS) to save to your hard drive </li>
</ol>


<div class="card-columns">
<?php foreach($arrImages as $arrRow){  ?>
	<div class="card">	
		<div style="height:14rem;overflow:hidden;"><a href="<?=$arrRow["parent_filename"]?>" title="<?=htmlentities($arrRow["parent_title"])?>"><img alt="<?=htmlentities($arrRow["title"] . " " . $arrRow["description"] )?>" src="<?=$arrRow["filename"]?>" style="width:100%;height:auto;" /></a></div>
		<div class="card-header" style='height:5rem;'><?=$arrRow["parent_title"]?></div>
	</div>
<?php } ?>
</div>
