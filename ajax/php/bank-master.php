<?php

include '../../class/include.php';
header('Content-Type: application/json; charset=UTF8');

// Create a new Bank Type
if (isset($_POST['create'])) {

    $BANK = new Bank(NULL); // Create a new Bank Type

    // Set the bank Type details
    $BANK->name = $_POST['name'];
    $BANK->code = $_POST['code'];


    // Attempt to create the Bank Type
    $res = $BANK->create();

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

// Update Bank Type details
if (isset($_POST['update'])) {

    $BANK = new Bank($_POST['id']); // Retrieve Bank Type by ID

    // Update Bank Type details
    $BANK->name = $_POST['name'];
    $BANK->code = $_POST['code'];

    // Attempt to update the Bank Type
    $result = $BANK->update();

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
    $bank = new Bank($_POST['id']);
    $result = $bank->delete(); // Make sure this method exists

    if ($result) {
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error']);
    }
}

?>