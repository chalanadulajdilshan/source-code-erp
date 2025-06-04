<?php

include '../../class/include.php';
header('Content-Type: application/json; charset=UTF8');

// Create a new Dag
if (isset($_POST['create'])) {

    $DAG = new CreateDag(NULL); // Create a new Dag

    // Set the Dag details
    $DAG->code = $_POST['code'];
    $DAG->department_id = $_POST['department_id'];
    $DAG->date = $_POST['date'];
    $DAG->customer_id = $_POST['customer_id'];
    $DAG->casing_cost = $_POST['casing_cost'];
    $DAG->type = $_POST['type'];
    $DAG->size = $_POST['size'];
    $DAG->make = $_POST['make'];
    $DAG->belt_design = $_POST['belt_design'];
    $DAG->job_no = $_POST['job_no'];
    $DAG->serial_no = $_POST['serial_no'];
    $DAG->warranty = $_POST['warranty'];
    $DAG->remark = $_POST['remark'];

    // Attempt to create the Dag
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

// Update Dag details
if (isset($_POST['update'])) {

    $DAG = new CreateDag($_POST['id']); // Retrieve Dag by ID

    // Update Dag details
    $DAG->code = $_POST['code'];
    $DAG->department_id = $_POST['department_id'];
    $DAG->date = $_POST['date'];
    $DAG->customer_id = $_POST['customer_id'];
    $DAG->casing_cost = $_POST['casing_cost'];
    $DAG->type = $_POST['type'];
    $DAG->size = $_POST['size'];
    $DAG->make = $_POST['make'];
    $DAG->belt_design = $_POST['belt_design'];
    $DAG->job_no = $_POST['job_no'];
    $DAG->serial_no = $_POST['serial_no'];
    $DAG->warranty = $_POST['warranty'];
    $DAG->remark = $_POST['remark'];

    // Attempt to update the Dag
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
    $dag = new CreateDag($_POST['id']);
    $result = $dag->delete(); // Make sure this method exists

    if ($result) {
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error']);
    }
}

?>