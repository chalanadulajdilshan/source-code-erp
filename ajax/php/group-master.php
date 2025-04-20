<?php
include '../../class/include.php';
header('Content-Type: application/json; charset=UTF8');
 

if (isset($_POST['create'])) {

    $GROUP_MASTER = new GroupMaster();

    $GROUP_MASTER->name = $_POST['name'];
    $GROUP_MASTER->code = $_POST['code'];
    $GROUP_MASTER->is_active = isset($_POST['is_active']) ? 1 : 0;

    $result = $GROUP_MASTER->create();

    if ($result) {
        $response['status'] = 'success';
    } else {
        $response['status'] = 'error';
     }
    echo json_encode($response);
    exit();
}

if (isset($_POST['update'])) {

    $GROUP_MASTER = new GroupMaster($_POST['group_id']);

    $GROUP_MASTER->name = $_POST['name'];
    $GROUP_MASTER->code = $_POST['code'];
    $GROUP_MASTER->is_active = isset($_POST['is_active']) ? 1 : 0;

    $result = $GROUP_MASTER->update();

    if ($result) {
        $response['status'] = 'success';
    } else {
        $response['status'] = 'error';
     }
    echo json_encode($response);
    exit();
}

if (isset($_POST['delete'])) {

    $GROUP_MASTER = new GroupMaster($_POST['id']);

    $result = $GROUP_MASTER->delete();

    if ($result) {
        $response['status'] = 'success';
    } else {
        $response['status'] = 'error';
    }
    echo json_encode($response);
    exit();
}
?>