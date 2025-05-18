<?php

include '../../class/include.php';
header('Content-Type: application/json; charset=UTF8');

// Create a new Employee
if (isset($_POST['create'])) {

    $EMPLOYEE = new EmployeeMaster(NULL); // Create a new Employee

    // Set the Employee details
    $EMPLOYEE->code = $_POST['code'];
    $EMPLOYEE->name = $_POST['name'];
    $EMPLOYEE->full_name = $_POST['full_name'];
    $EMPLOYEE->gender = $_POST['gender'];
    $EMPLOYEE->birthday = $_POST['birthday'];
    $EMPLOYEE->nic_no = $_POST['nic_no'];
    $EMPLOYEE->mobile_1 = $_POST['mobile_1'];
    $EMPLOYEE->mobile_2 = $_POST['mobile_2'];
    $EMPLOYEE->email = $_POST['email'];
    $EMPLOYEE->epf_available = ($_POST['epf_available'] === 'available') ? 1 : 0;
    $EMPLOYEE->epf_no = ($_POST['epf_available'] === 'available') ? $_POST['epf_no'] : '';
    $EMPLOYEE->finger_print_no = $_POST['finger_print_no'];
    $EMPLOYEE->department_id = $_POST['department_id'];

    // Attempt to create the Employee
    $res = $EMPLOYEE->create();

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

// Update Employee details
if (isset($_POST['update'])) {

    $EMPLOYEE = new EmployeeMaster($_POST['id']); // Retrieve Employee by ID

    // Update Employee details
    $EMPLOYEE->code = $_POST['code'];
    $EMPLOYEE->name = $_POST['name'];
    $EMPLOYEE->full_name = $_POST['full_name'];
    $EMPLOYEE->gender = $_POST['gender'];
    $EMPLOYEE->birthday = $_POST['birthday'];
    $EMPLOYEE->nic_no = $_POST['nic_no'];
    $EMPLOYEE->mobile_1 = $_POST['mobile_1'];
    $EMPLOYEE->mobile_2 = $_POST['mobile_2'];
    $EMPLOYEE->email = $_POST['email'];
    $EMPLOYEE->epf_available = ($_POST['epf_available'] === 'available') ? 1 : 0;
    $EMPLOYEE->epf_no = ($_POST['epf_available'] === 'available') ? $_POST['epf_no'] : '';
    $EMPLOYEE->finger_print_no = $_POST['finger_print_no'];
    $EMPLOYEE->department_id = $_POST['department_id'];

    // Attempt to update the Employee
    $result = $EMPLOYEE->update();

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
    $employee = new EmployeeMaster($_POST['id']);
    $result = $employee->delete(); // Make sure this method exists

    if ($result) {
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error']);
    }
}

?>