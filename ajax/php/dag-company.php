<?php

include '../../class/include.php';
header('Content-Type: application/json; charset=UTF8');

// Create a new DAG Company
if (isset($_POST['create'])) {

    $DAG = new DagCompany(NULL); // Create a new DAG Company

    // Set the DAG Company details
    $DAG->name = $_POST['name'];
    $DAG->code = $_POST['code'];
    $DAG->address = $_POST['address'];
    $DAG->contact_person = $_POST['contact_person'];
    $DAG->phone_number = $_POST['phone_number'];
    $DAG->email = $_POST['email'];
    $DAG->is_active = isset($_POST['is_active']) ? 1 : 0;
    $DAG->remark = $_POST['remark'];

    // Attempt to create the DAG Company
    $res = $DAG->create();

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

// Update DAG Company details
if (isset($_POST['update'])) {

    $DAG = new DagCompany($_POST['id']); // Retrieve DAG Company by ID

    // Update DAG Company details
    $DAG->name = $_POST['name'];
    $DAG->code = $_POST['code'];
    $DAG->address = $_POST['address'];
    $DAG->contact_person = $_POST['contact_person'];
    $DAG->phone_number = $_POST['phone_number'];
    $DAG->email = $_POST['email'];
    $DAG->is_active = isset($_POST['is_active']) ? 1 : 0;
    $DAG->remark = $_POST['remark'];

    // Attempt to update the DAG Company
    $result = $DAG->update();

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
    $dag = new DagCompany($_POST['id']);
    $result = $dag->delete(); // Make sure this method exists

    if ($result) {
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error']);
    }
}

?>