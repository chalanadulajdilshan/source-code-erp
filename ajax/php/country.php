<?php

include '../../class/include.php';
header('Content-Type: application/json; charset=UTF8');

// Create a new country
if (isset($_POST['create'])) {

    $COUNTRY = new Country(NULL); // Create a new Country object

    // Set the country name
    $COUNTRY->code = $_POST['code'];
    $COUNTRY->name = $_POST['name'];
    $COUNTRY->is_active = isset($_POST['is_active']) ? 1 : 0;
    // Attempt to create the country
    $res = $COUNTRY->create();

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

// Update country details
if (isset($_POST['update'])) {

    $COUNTRY = new Country($_POST['id']); // Retrieve country by ID

    // Update country name
    $COUNTRY->name = $_POST['name'];
    $COUNTRY->is_active = isset($_POST['is_active']) ? 1 : 0;

    // Attempt to update the country
    $result = $COUNTRY->update();

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

// Delete country
if (isset($_POST['delete']) && isset($_POST['id'])) {
    $country = new Country($_POST['id']);
    $result = $country->delete(); // Ensure this method exists in the Country class

    if ($result) {
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error']);
    }
}

?>
