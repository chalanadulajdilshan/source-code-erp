<?php

include '../../class/include.php';
header('Content-Type: application/json; charset=UTF-8');

// Create a new department
if (isset($_POST['create'])) {

    $DEPARTMENT = new DepartmentMaster(NULL); // Create a new Department object

    // Set department details
    $DEPARTMENT->code = $_POST['code'];
    $DEPARTMENT->name = $_POST['name'];
    $DEPARTMENT->remark = $_POST['remark'];
    $DEPARTMENT->is_active = isset($_POST['is_active']) ? 1 : 0; // Checkbox for active

    // Attempt to create
    $res = $DEPARTMENT->create();

    if ($res) {
        echo json_encode(['status' => 'success']);
        exit();
    } else {
        echo json_encode(['status' => 'error']);
        exit();
    }
}

// Update department
if (isset($_POST['update'])) {

    $DEPARTMENT = new DepartmentMaster($_POST['id']); // Get department by ID

    // Update details
    $DEPARTMENT->code = $_POST['code'];
    $DEPARTMENT->name = $_POST['name'];
    $DEPARTMENT->remark = $_POST['remark'];
    $DEPARTMENT->is_active = isset($_POST['is_active']) ? 1 : 0;

    $result = $DEPARTMENT->update();

    if ($result) {
        echo json_encode(['status' => 'success']);
        exit();
    } else {
        echo json_encode(['status' => 'error']);
        exit();
    }
}

// Delete department
if (isset($_POST['delete']) && isset($_POST['id'])) {
    $DEPARTMENT = new DepartmentMaster($_POST['id']);
    $result = $DEPARTMENT->delete();

    if ($result) {
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error']);
    }
}

// search by department
if (isset($_POST['query'])) {
    $search = $_POST['query'];

    $DEPARTMENT_MASTER = new DepartmentMaster();
    $departments = $DEPARTMENT_MASTER->searchDepartments($search);

    if ($departments) {
        echo json_encode($departments);  // Return the customers as a JSON string
    } else {
        echo json_encode([]);  // Return an empty array if no departments are found
    }
    exit;
}


?>
