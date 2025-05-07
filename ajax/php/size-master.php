<?php

include '../../class/include.php';
header('Content-Type: application/json; charset=UTF8');

// Create a new Expense Type
if (isset($_POST['create'])) {

    $SIZES = new Sizes(NULL); // Create a new Expense Type

    // Set the Expense Type details
    $SIZES->code = $_POST['code'];
    $SIZES->name = $_POST['name'];
    $SIZES->is_active = isset($_POST['is_active']) ? 1 : 0;

    // Attempt to create the Expense Type
    $res = $SIZES->create();

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

    $SIZES = new Sizes($_POST['id']); // Retrieve Expense Type by ID

    // Update Expense Type details
    $SIZES->code = $_POST['code'];
    $SIZES->name = $_POST['name'];
    $SIZES->is_active = isset($_POST['is_active']) ? 1 : 0;

    // Attempt to update the Expense Type
    $result = $SIZES->update();

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
    $sizes = new Sizes($_POST['id']);
    $result = $sizes->delete(); // Make sure this method exists

    if ($result) {
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error']);
    }
}

?>