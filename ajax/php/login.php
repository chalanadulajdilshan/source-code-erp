<?php

include '../../class/include.php';
header('Content-Type: application/json; charset=UTF8');
 
 
$USER = new User(NULL);

$username = $_POST['username'];
$password = $_POST['password'];

if (empty($username) || empty($password)) {
    $result = [
        "status" => 'error'
    ];
    echo json_encode($result);
    exit();
}

if ($USER->login($username, $password)) {
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
