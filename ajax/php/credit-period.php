<?php

include '../../class/include.php';
header('Content-Type: application/json; charset=UTF8');

// Create a new credit-period
if (isset($_POST['create'])) {

    $CREDIT = new CreditPeriod(NULL); // Create a new credit-period

    // Set the credit details
    $CREDIT->code = $_POST['code'];
    $CREDIT->days = $_POST['days'];
    $CREDIT->is_active = isset($_POST['is_active']) ? 1 : 0;

    // Attempt to create the credit-period
    $res = $CREDIT->create();

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

// Update credit-period details
if (isset($_POST['update'])) {

    $CREDIT = new CreditPeriod($_POST['id']); // Retrieve credit-period by ID

    // Update credit-period details
    $CREDIT->code = $_POST['code'];
    $CREDIT->days = $_POST['days'];
    $CREDIT->is_active = isset($_POST['is_active']) ? 1 : 0;

    // Attempt to update the credit-period
    $result = $CREDIT->update();

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
    $credit = new CreditPeriod($_POST['id']);
    $result = $credit->delete(); // Make sure this method exists

    if ($result) {
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error']);
    }
}

?>