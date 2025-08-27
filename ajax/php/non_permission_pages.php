<?php

include '../../class/include.php';
header('Content-Type: application/json; charset=UTF-8');

$NP = new NonPermissionPage();

// Create a new page
if (isset($_POST['page']) && !isset($_POST['id'])) {
    $NP->page = trim($_POST['page']);
    $NP->is_active = isset($_POST['is_active']) ? (int)$_POST['is_active'] : 0;

    $res = $NP->create();

    if ($res) {
        echo json_encode(["status" => "success"]);
        exit();
    } else {
        echo json_encode(["status" => "error"]);
        exit();
    }
}

// Update existing page
if (isset($_POST['id'])) {
    $NP = new NonPermissionPage((int)$_POST['id']);
    $NP->page = trim($_POST['page']);
    $NP->is_active = isset($_POST['is_active']) ? (int)$_POST['is_active'] : 0;

    $res = $NP->update();

    if ($res) {
        echo json_encode(["status" => "success"]);
        exit();
    } else {
        echo json_encode(["status" => "error"]);
        exit();
    }
}

// Optionally: list all pages
if (isset($_GET['action']) && $_GET['action'] === 'list') {
    $pages = $NP->all();
    echo json_encode($pages);
    exit();
}
