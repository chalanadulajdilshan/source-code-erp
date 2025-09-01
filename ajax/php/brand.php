<?php

include '../../class/include.php';
header('Content-Type: application/json; charset=UTF8');

// Create a new brand
if (isset($_POST['create'])) {

    $BRAND = new Brand(NULL); // New brand object

    $BRAND->category_id = $_POST['category_id'];
    $BRAND->name = $_POST['name'];
    $BRAND->country_id = $_POST['country_id'];
    $BRAND->discount = $_POST['discount'];
    $BRAND->is_active = isset($_POST['activeStatus']) ? 1 : 0;
    $BRAND->remark = $_POST['remark'];

    $res = $BRAND->create();

    if ($res) {
        echo json_encode(["status" => 'success']);
    } else {
        echo json_encode(["status" => 'error']);
    }
    exit();
}

// Update brand
if (isset($_POST['update'])) {

    $BRAND = new Brand($_POST['brand_id']); // Load brand by ID

    $BRAND->category_id = $_POST['category_id'];
    $BRAND->name = $_POST['name'];
    $BRAND->country_id = $_POST['country_id'];
    $BRAND->discount = $_POST['discount'];
    $BRAND->is_active = isset($_POST['activeStatus']) ? 1 : 0;
    $BRAND->remark = $_POST['remark'];

    $res = $BRAND->update();

    if ($res) {
        echo json_encode(["status" => 'success']);
    } else {
        echo json_encode(["status" => 'error']);
    }
    exit();
}

// Delete brand
if (isset($_POST['delete']) && isset($_POST['id'])) {
    $BRAND = new Brand($_POST['id']);
    $res = $BRAND->delete();

    if ($res) {
        echo json_encode(["status" => 'success']);
    } else {
        echo json_encode(["status" => 'error']);
    }
    exit();
}
