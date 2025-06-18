<?php

include '../../class/include.php';
header('Content-Type: application/json; charset=UTF8');

// Create a new Design Type
if (isset($_POST['create'])) {

    $DESIGN = new DesignMaster(NULL); // Create a new Design Type

    // Set the Design Type details
    $DESIGN->code = $_POST['code'];
    $DESIGN->name = $_POST['name'];
    $DESIGN->is_active = isset($_POST['is_active']) ? 1 : 0;

    // Attempt to create the Design Type
    $res = $DESIGN->create();

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

// Update Design Type details
if (isset($_POST['update'])) {

    $DESIGN = new DesignMaster($_POST['id']); // Retrieve Design Type by ID

    // Update Design Type details
    $DESIGN->code = $_POST['code'];
    $DESIGN->name = $_POST['name'];
    $DESIGN->is_active = isset($_POST['is_active']) ? 1 : 0;

    // Attempt to update the Design Type
    $result = $DESIGN->update();

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
    $DESIGN_MASTER = new DesignMaster($_POST['id']);
    $result = $DESIGN_MASTER->delete(); // Make sure this method exists

    if ($result) {
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error']);
    }
}

?>