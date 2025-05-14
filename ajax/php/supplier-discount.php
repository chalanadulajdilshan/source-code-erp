<?php

include '../../class/include.php';
header('Content-Type: application/json; charset=UTF8');

// Create a new Supplier Discount
if (isset($_POST['create'])) {

    $DISCOUNT = new SuplierDiscount(NULL); // Create a new Supplier Discount

    // Set the Supplier Discount details
    $DISCOUNT->code = $_POST['code'];
    $DISCOUNT->date = $_POST['date'];
    $DISCOUNT->discount_id = $_POST['discount_id'];
    $DISCOUNT->suplier_id = $_POST['suplier_id'];
    $DISCOUNT->name = $_POST['name'];
    $DISCOUNT->brand_id = $_POST['brand_id'];
    $DISCOUNT->discount = $_POST['discount'];
    $DISCOUNT->is_active = isset($_POST['is_active']) ? 1 : 0;

    // Attempt to create the Supplier Discount
    $res = $DISCOUNT->create();

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

// Update Supplier Discount details
if (isset($_POST['update'])) {

    $DISCOUNT = new SuplierDiscount($_POST['id']); // Retrieve Supplier Discount by ID

    // Update Supplier Discount details
    $DISCOUNT->code = $_POST['code'];
    $DISCOUNT->date = $_POST['date'];
    $DISCOUNT->discount_id = $_POST['discount_id'];
    $DISCOUNT->suplier_id = $_POST['suplier_id'];
    $DISCOUNT->name = $_POST['name'];
    $DISCOUNT->brand_id = $_POST['brand_id'];
    $DISCOUNT->discount = $_POST['discount'];
    $DISCOUNT->is_active = isset($_POST['is_active']) ? 1 : 0;

    // Attempt to update the Supplier Discount
    $result = $DISCOUNT->update();

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
    $discount = new SuplierDiscount($_POST['id']);
    $result = $discount->delete(); // Make sure this method exists

    if ($result) {
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error']);
    }
}

?>
