<?php

include '../../class/include.php';
header('Content-Type: application/json; charset=UTF8');

// Create a new Expense Type
if (isset($_POST['create'])) {

    $BELT = new BeltMaster(NULL); // Create a new Expense Type

    // Set the Expense Type details
    $BELT->code = $_POST['code'];
    $BELT->name = $_POST['name'];
    $BELT->is_active = isset($_POST['is_active']) ? 1 : 0;

    // Attempt to create the Expense Type
    $res = $BELT->create();

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

// Update Expense Type details
if (isset($_POST['update'])) {

    $BELT = new BeltMaster($_POST['id']); // Retrieve Expense Type by ID

    // Update Expense Type details
    $BELT->code = $_POST['code'];
    $BELT->name = $_POST['name'];
    $BELT->is_active = isset($_POST['is_active']) ? 1 : 0;

    // Attempt to update the Expense Type
    $result = $BELT->update();

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
    $belt = new BeltMaster($_POST['id']);
    $result = $belt->delete(); // Make sure this method exists

    if ($result) {
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error']);
    }
}

?>