<?php

include '../../class/include.php';
header('Content-Type: application/json; charset=UTF8');

// Create a new branch
if (isset($_POST['create'])) {

    $LABOUR_MASTER = new LabourMaster(NULL); // Create a new Branch object

    // Set the branch details
    $LABOUR_MASTER->type = $_POST['type']; 
    $LABOUR_MASTER->code = $_POST['code'];
    $LABOUR_MASTER->name = $_POST['name'];
    $LABOUR_MASTER->is_active = isset($_POST['is_active']) ? 1 : 0;  
   

    // Attempt to create the branch
    $res = $LABOUR_MASTER->create();

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

// Update branch details
if (isset($_POST['update'])) {

    $LABOUR_MASTER = new LabourMaster($_POST['id']); // Retrieve branch by ID

    // Update branch details
    $LABOUR_MASTER->type = $_POST['type']; 
    $LABOUR_MASTER->code = $_POST['code'];
    $LABOUR_MASTER->name = $_POST['name'];
    $LABOUR_MASTER->is_active = isset($_POST['is_active']) ? 1 : 0; 

    // Attempt to update the branch
    $result = $LABOUR_MASTER->update();

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
    $LABOUR_MASTER= new LabourMaster($_POST['id']);
    $result = $LABOUR_MASTER->delete(); // Make sure this method exists

    if ($result) {
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error']);
    }
}

?>