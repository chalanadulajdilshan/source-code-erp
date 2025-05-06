<?php

include '../../class/include.php';
header('Content-Type: application/json; charset=UTF8');

// Create a new Expense Type
if (isset($_POST['create'])) {

    $SUBCATEGORY = new Subcategory(NULL); // Create a new Expense Type

    // Set the Expense Type details
    $SUBCATEGORY->code = $_POST['code'];
    $SUBCATEGORY->category_id = $_POST['category_id'];
    $SUBCATEGORY->name = $_POST['name'];
    $SUBCATEGORY->is_active = isset($_POST['is_active']) ? 1 : 0;

    // Attempt to create the Expense Type
    $res = $SUBCATEGORY->create();

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

    $SUBCATEGORY = new Subcategory($_POST['id']); // Retrieve Expense Type by ID

    // Update Expense Type details
    $SUBCATEGORY->code = $_POST['code'];
    $SUBCATEGORY->category_id = $_POST['category_id'];
    $SUBCATEGORY->name = $_POST['name'];
    $SUBCATEGORY->is_active = isset($_POST['is_active']) ? 1 : 0;

    // Attempt to update the Expense Type
    $result = $SUBCATEGORY->update();

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
    $subcategory = new Subcategory($_POST['id']);
    $result = $subcategory->delete(); // Make sure this method exists

    if ($result) {
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error']);
    }
}

?>