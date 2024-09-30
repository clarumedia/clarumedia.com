<?php

$objForm=new HTMLForm($db);
$objForm->strFormTitle="Calculator";
$objForm->boolDisplayTitle=true;
$objForm->arrTableTagAttributes["class"]="tableForm";
$objForm->arrFormTagAttributes["name"]="frmCalculator";
$objForm->arrFormControls["target_service_time"]=["control_type"=>"time","oninput"=>"calculate_times();","required"=>1,"value"=>$_REQUEST["target_service_time"] ?: "13:00"];
$objForm->arrFormControls["oven_warmup_time_minutes"]=["control_type"=>"financial","oninput"=>"calculate_times();","required"=>1,"value"=>$_REQUEST["oven_warmup_time_minutes"] ?: 10];
$objForm->arrFormControls["meat_roasting_time_minutes"]=["control_type"=>"financial","oninput"=>"calculate_times();","required"=>1,"value"=>$_REQUEST["meat_roasting_time_minutes"] ?: 90];
$objForm->arrFormControls["meat_rest_time_minutes"]=["control_type"=>"financial","oninput"=>"calculate_times();","required"=>1,"value"=>$_REQUEST["meat_rest_time_minutes"] ?: 5];
$objForm->arrFormControls["total_time_minutes"]=["control_type"=>"financial","disabled"=>1];

$objForm->arrFormControls["potato_par_boiling_minutes"]=["control_type"=>"financial","oninput"=>"calculate_times();","value"=>$_REQUEST["potato_par_boiling_minutes"] ?: 15];
$objForm->arrFormControls["potato_roasting_minutes"]=["control_type"=>"financial","oninput"=>"calculate_times();","value"=>$_REQUEST["potato_roasting_minutes"] ?: 45];


$lngTableFormColumn=1;
$objForm->arrFormControls["blanker"]=["title"=>" ","control_type"=>"div_area","readonly"=>1,"table_form_column"=>$lngTableFormColumn];
$objForm->arrFormControls["turn_on_oven_at"]=["control_type"=>"time","readonly"=>1,"table_form_column"=>$lngTableFormColumn];
$objForm->arrFormControls["meat_into_oven_at"]=["control_type"=>"time","readonly"=>1,"table_form_column"=>$lngTableFormColumn];
$objForm->arrFormControls["meat_out_of_oven_at"]=["control_type"=>"time","readonly"=>1,"table_form_column"=>$lngTableFormColumn];
$objForm->arrFormControls["blanker2"]=["title"=>" ","control_type"=>"div_area","readonly"=>1,"table_form_column"=>$lngTableFormColumn];


$objForm->arrFormControls["potatoes_par_boil_at"]=["control_type"=>"time","readonly"=>1,"table_form_column"=>$lngTableFormColumn];
$objForm->arrFormControls["potatoes_into_oven_at"]=["control_type"=>"time","readonly"=>1,"table_form_column"=>$lngTableFormColumn];

$objForm->showForm();


?>

<script type='text/javascript'>

    function time_diff(strTime,lngDiffMinutes){

        if (!strTime){
            return strTime;
        }

        const [hours, minutes] = strTime.split(':');
        const lngTargetTimeMinutes = parseInt(hours, 10) * 60 + parseInt(minutes, 10);

        const newlngTargetTimeMinutes = lngTargetTimeMinutes - lngDiffMinutes;

        // Convert back to HH:mm format
        const newHours = Math.floor(newlngTargetTimeMinutes / 60);
        const newMinutes = newlngTargetTimeMinutes % 60;
        return formattedTime = `${String(newHours).padStart(2, '0')}:${String(newMinutes).padStart(2, '0')}`;

    }


    function calculate_times(){

        let objForm=document.forms.frmCalculator;
        
        objForm.total_time_minutes.value=safeNumber(objForm.oven_warmup_time_minutes.value) + safeNumber(objForm.meat_roasting_time_minutes.value)  + safeNumber(objForm.meat_rest_time_minutes.value);
        
        if (!objForm.target_service_time.value){
            objForm.turn_on_oven_at.value="";
            objForm.meat_into_oven_at.value="";
            objForm.meat_out_of_oven_at.value="";
            return;
        }

        objForm.turn_on_oven_at.value = time_diff(objForm.target_service_time.value,objForm.total_time_minutes.value);
        objForm.meat_into_oven_at.value= time_diff(objForm.target_service_time.value,safeNumber(objForm.meat_roasting_time_minutes.value)  + safeNumber(objForm.meat_rest_time_minutes.value));
        objForm.meat_out_of_oven_at.value= time_diff(objForm.target_service_time.value, safeNumber(objForm.meat_rest_time_minutes.value));
    

        objForm.potatoes_par_boil_at.value= time_diff(objForm.target_service_time.value, safeNumber(objForm.potato_par_boiling_minutes.value)  + safeNumber(objForm.potato_roasting_minutes.value));
        objForm.potatoes_into_oven_at.value= time_diff(objForm.target_service_time.value,  safeNumber(objForm.potato_roasting_minutes.value));
    }


</script>



<script type='text/javascript'>
    calculate_times();

</script>