<?php

include '../../class/include.php';
header('Content-Type: application/json; charset=UTF8');

// Create a new user
if (isset($_POST['create'])) {

    $name = $_POST['name'];
    $code = $_POST['code'];

    $type = $_POST['type'];
    $active = isset($_POST['active']) ? 1 : 0;
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $company_id = $_POST['company_id'];
    $department_id = $_POST['department_id'];

    $USER = new User(NULL); // Assume there's a User class like USER

    $res = $USER->create($name, $code, $type, $company_id, $active, $email, $phone, $username, $_POST['password'], $password, $department_id);

    echo json_encode([
        "status" => $res ? 'success' : 'error'
    ]);
    exit();
}

// Update an existing user
if (isset($_POST['update'])) {

    $USER = new User($_POST['user_id']); // Retrieve USER by ID

    // Update USER details 
    $USER->name = $_POST['name'];
    $USER->code = $_POST['code'];
    $USER->email = $_POST['email'];
    $USER->phone = $_POST['phone'];
    $USER->username = $_POST['username'];
    $USER->company_id = $_POST['company_id'];
    $USER->department_id = $_POST['department_id'];
    
    $USER->active_status = isset($_POST['active']) ? 1 : 0;

    // Attempt to update the USER
    $result = $USER->update();

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


?>