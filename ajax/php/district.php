<?php

include '../../class/include.php';

if ($_POST['action'] == 'GET_DISTRICT_BY_PROVINCE') {

    $DISTRICT = new District(NULL);
  
    $result = $DISTRICT->GetDistrictByProvince($_POST["province"]);
    echo json_encode($result);
     
    exit();
}

