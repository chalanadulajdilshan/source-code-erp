<?php

include '../../class/include.php';
header('Content-Type: application/json; charset=UTF8');

// Create a new  Vehicle Brand
if (isset($_POST['create'])) {

    $BRAND = new VehicleBrand(NULL); // Create a new Vehicle Brand

    // Set the Vehicle Brand details
    $BRAND->code = $_POST['code'];
    $BRAND->name = $_POST['name'];

    // Attempt to create the Vehicle Brand
    $res = $BRAND->create();

    if ($res) {
        $result = [
            "status" => 'success'
        ];
        echo json_encode($result);
        exit();
    } else {
        $result = [
            "status" => 'error'
        ];
        echo json_encode($result);
        exit();
    }
}

// Update Vehicle Brand details
if (isset($_POST['update'])) {

    $BRAND = new VehicleBrand($_POST['id']); // Retrieve Vehicle Brand by ID

    // Update Vehicle Brand details
    $BRAND->code = $_POST['code'];
    $BRAND->name = $_POST['name'];

    // Attempt to update the Vehicle Brand
    $result = $BRAND->update();

    if ($result) {
        $result = [
            "status" => 'success'
        ];
        echo json_encode($result);
        exit();
    } else {
        $result = [
            "status" => 'error'
        ];
        echo json_encode($result);
        exit();
    }
}

if (isset($_POST['delete']) && isset($_POST['id'])) {
    $BRAND = new VehicleBrand($_POST['id']);
    $result = $BRAND->delete(); // Make sure this method exists

    if ($result) {
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error']);
    }
}

?>