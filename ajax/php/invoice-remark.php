<?php

include '../../class/include.php';
header('Content-Type: application/json; charset=UTF8');

// Create a new invoice remark
if (isset($_POST['create'])) {

    $REMARK = new InvoiceRemark(NULL); // Create a new invoice remark

    // Set the belt Type details
    $REMARK->code = $_POST['code'];
    $REMARK->payment_type = $_POST['payment_type'];
    $REMARK->remark = $_POST['remark'];
    $REMARK->is_active = isset($_POST['is_active']) ? 1 : 0;

    // Attempt to create the invoice remark
    $result = $REMARK->create();



    if ($result) {
        $response['status'] = 'success';
    } else {
        $response['status'] = 'error';
    }
    echo json_encode($response);
    exit();
}

// Update invoice remark details
if (isset($_POST['update'])) {

    $REMARK = new InvoiceRemark($_POST['id']); // Retrieve invoice remark by ID

    // Update belt Type details
    $REMARK->code = $_POST['code'];
    $REMARK->payment_type = $_POST['payment_type'];
    $REMARK->remark = $_POST['remark'];
    $REMARK->is_active = isset($_POST['is_active']) ? 1 : 0;

    // Attempt to update the invoice remark
    $result = $REMARK->update();

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
    $remark = new InvoiceRemark($_POST['id']);
    $result = $remark->delete(); // Make sure this method exists

    if ($result) {
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error']);
    }
}
