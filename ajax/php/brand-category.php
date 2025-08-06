<?php

include '../../class/include.php';
header('Content-Type: application/json; charset=UTF8');

// Create a new brand category
if (isset($_POST['create'])) {

    $BRAND_CATEGORY = new BrandCategory(NULL); // Create a new brand category

    // Set the brand category details
    $BRAND_CATEGORY->code = $_POST['code'];
    $BRAND_CATEGORY->name = $_POST['name'];


    // Attempt to create the brand category
    $res = $BRAND_CATEGORY->create();

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

// Update brand category details
if (isset($_POST['update'])) {

    $BRAND_CATEGORY = new BrandCategory($_POST['id']); // Retrieve brand category by ID

    // Update brand category details
    $BRAND_CATEGORY->code = $_POST['code'];
    $BRAND_CATEGORY->name = $_POST['name'];

    // Attempt to update the brand category
    $result = $BRAND_CATEGORY->update();

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
    $brand_category = new BrandCategory($_POST['id']);
    $result = $brand_category->delete(); // Make sure this method exists

    if ($result) {
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error']);
    }
}

?>