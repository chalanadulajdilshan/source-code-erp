<?php

include '../../class/include.php';
header('Content-Type: application/json; charset=UTF8');

// Create a new  Vehicle Brand
if (isset($_POST['create'])) {

    $MODEL = new VehicleModel(NULL); // Create a new Vehicle Brand

    // Set the Vehicle Brand details
    $MODEL->code = $_POST['code'];
    $MODEL->brand_id = $_POST['brand_id'];
    $MODEL->name = $_POST['name'];

    // Attempt to create the Vehicle Brand
    $res = $MODEL->create();

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

    $MODEL = new VehicleModel($_POST['id']); // Retrieve Vehicle Brand by ID

    // Update Vehicle Brand details
    $MODEL->code = $_POST['code'];
    $MODEL->brand_id = $_POST['brand_id'];
    $MODEL->name = $_POST['name'];

    // Attempt to update the Vehicle Brand
    $result = $MODEL->update();

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
    $MODEL = new VehicleModel($_POST['id']);
    $result = $MODEL->delete(); // Make sure this method exists

    if ($result) {
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error']);
    }
}

?>