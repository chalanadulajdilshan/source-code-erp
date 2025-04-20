<?php

include '../../class/include.php';
header('Content-Type: application/json; charset=UTF8');

// Create a new branch
if (isset($_POST['create'])) {

    $BRANCH = new Branch(NULL); // Create a new Branch object

    // Set the branch details
    $BRANCH->bank_id = $_POST['bankId'];
    $BRANCH->name = $_POST['name'];
    $BRANCH->code = $_POST['code'];
    $BRANCH->address = $_POST['address'];
    $BRANCH->phone_number = $_POST['phoneNumber'];
    $BRANCH->city = $_POST['city'];
    $BRANCH->active_status = isset($_POST['activeStatus']) ? 1 : 0; // Handle checkbox for active status
    $BRANCH->remark = $_POST['remark'];

    // Attempt to create the branch
    $res = $BRANCH->create();

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

    $BRANCH = new Branch($_POST['branch_id']); // Retrieve branch by ID

    // Update branch details
    $BRANCH->bank_id = $_POST['bankId'];
    $BRANCH->name = $_POST['name'];
    $BRANCH->code = $_POST['code'];
    $BRANCH->address = $_POST['address'];
    $BRANCH->phone_number = $_POST['phoneNumber'];
    $BRANCH->city = $_POST['city'];
    $BRANCH->active_status = isset($_POST['activeStatus']) ? 1 : 0;
    $BRANCH->remark = $_POST['remark'];

    // Attempt to update the branch
    $result = $BRANCH->update();

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
    $branch = new Branch($_POST['id']);
    $result = $branch->delete(); // Make sure this method exists

    if ($result) {
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error']);
    }
}

?>