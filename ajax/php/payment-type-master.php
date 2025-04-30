<?php

include '../../class/include.php';
header('Content-Type: application/json; charset=UTF8');

// Create a new payment
if (isset($_POST['create'])) {
   
    $PAYMENT_TYPE = new PaymentType(NULL); // Create a new payment object

    // Set the payment details
    $PAYMENT_TYPE->name = $_POST['name'];
    $PAYMENT_TYPE->is_active = isset($_POST['is_active']) ? 1 : 0;

    // Attempt to create the payment type
    $res = $PAYMENT_TYPE->create();

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


// Update payment type details
if (isset($_POST['update'])) {
 
    $PAYMENT_TYPE = new PaymentType($_POST['id']);  
 
    $PAYMENT_TYPE->name = $_POST['name'];
    $PAYMENT_TYPE->is_active = isset($_POST['is_active']) ? 1 : 0;

    // Attempt to update the payment type
    $result = $PAYMENT_TYPE->update();

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
    $payment_type = new PaymentType($_POST['id']);
    $result = $payment_type->delete(); // Make sure this method exists

    if ($result) {
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error']);
    }
}

?>