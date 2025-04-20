<?php


include '../../class/include.php';
header('Content-Type: application/json; charset=UTF8');

$response = array();

if (isset($_POST['create'])) {

    $CATEGORY = new CategoryMaster();

    $CATEGORY->name = $_POST['name'];
    $CATEGORY->code = $_POST['code'];
    $CATEGORY->is_active = isset($_POST['is_active']) ? 1 : 0;

    $result = $CATEGORY->create();

    if ($result) {
        $response['status'] = 'success';
    } else {
        $response['status'] = 'error';
    }
    echo json_encode($response);
    exit();
}

if (isset($_POST['update'])) {

    $CATEGORY = new CategoryMaster($_POST['category_id']);

    $CATEGORY->name = $_POST['name']; 
    $CATEGORY->is_active = isset($_POST['is_active']) ? 1 : 0;

    $result = $CATEGORY->update();

    if ($result) {
        $response['status'] = 'success';
    } else {
        $response['status'] = 'error';
    }
    echo json_encode($response);
    exit();
}

if (isset($_POST['delete'])) {

    $CATEGORY = new CategoryMaster($_POST['id']);

    $result = $CATEGORY->delete();

    if ($result) {
        $response['status'] = 'success';
    } else {
        $response['status'] = 'error';
    }
    echo json_encode($response);
    exit();
}
