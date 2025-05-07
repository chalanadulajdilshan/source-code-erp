<?php

include '../../class/include.php';
header('Content-Type: application/json; charset=UTF8');

// Create a new Expense Type
if (isset($_POST['create'])) {

    $DESIGN = new DesignMaster(NULL); // Create a new Expense Type

    // Set the Expense Type details
    $DESIGN->code = $_POST['code'];
    $DESIGN->name = $_POST['name'];
    $DESIGN->is_active = isset($_POST['is_active']) ? 1 : 0;

    // Attempt to create the Expense Type
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

// Update Expense Type details
if (isset($_POST['update'])) {

    $DESIGN = new DesignMaster($_POST['id']); // Retrieve Expense Type by ID

    // Update Expense Type details
    $DESIGN->code = $_POST['code'];
    $DESIGN->name = $_POST['name'];
    $DESIGN->is_active = isset($_POST['is_active']) ? 1 : 0;

    // Attempt to update the Expense Type
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
    $design = new DesignMaster($_POST['id']);
    $result = $design->delete(); // Make sure this method exists

    if ($result) {
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error']);
    }
}

?>