<?php include($GLOBALS["arrCBFApplicationVariables"]["html_content_path"] . "/relator_public_site_header_engine.php");

$sql="SELECT f1.*,
fparent.filename as parent_filename,
fparent.title as parent_title,
fparent.parent_file_id as parent_parent_file_id 
FROM files f1 INNER JOIN files fparent ON f1.parent_file_id=fparent.file_id 
WHERE fparent.parent_file_id=" . $oCMSServer->arrFile["file_id"] . "  
AND f1.file_type_id=5 AND f1.published IS NOT NULL AND fparent.published IS NOT NULL
ORDER BY fparent.title";

$arrImages=$db->GetAssoc($sql);
if ($arrImages===false){
	$ErrorHandler->Go("Child image error",$db->ErrorMsg());
	return 1;
}

?>


        <blockquote><?=$oCMSServer->arrFile["description"]?></blockquote>
	 <div class='divLightBoxGrid'>
        <?php foreach($arrImages as $arrRow){  ?>


                <figure>
	            <a href='<?=$arrRow["parent_filename"]?>'>

                    <img src="<?=$arrRow["filename"]?>" alt="<?=htmlentities($arrRow["parent_title"] . " " . $arrRow["description"] )?>" />
                    <figcaption><?=$arrRow["parent_title"]?></figcaption>
		    </a>        
        	</figure>
        


        <?php } ?>  
	</div>
        [[ascii_content]]


</div>
</main>



</body>
</html>