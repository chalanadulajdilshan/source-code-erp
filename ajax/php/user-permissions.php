<?php
include '../../class/include.php';

if (!isset($_POST['selectUser']) || !isset($_POST['permissions']) || empty($_POST['permissions'])) {
    echo json_encode(['status' => 'error', 'message' => 'User or permissions not provided.']);
    exit();
}

$userId = (int) $_POST['selectUser']; // Changed from userType to selectUser
$permissions = $_POST['permissions'];

$db = new Database();

// Delete existing permissions for this user
$deleteQuery = "DELETE FROM `user_permission` WHERE `user_id` = $userId";
$db->readQuery($deleteQuery);

// Insert new permissions per page
foreach ($permissions as $pageId => $permSet) {
    $add = isset($permSet['add']) ? 1 : 0;
    $edit = isset($permSet['edit']) ? 1 : 0;
    $search = isset($permSet['search']) ? 1 : 0;
    $delete = isset($permSet['delete']) ? 1 : 0;
    $print = isset($permSet['print']) ? 1 : 0;
    $other = isset($permSet['other']) ? 1 : 0;

    $userPermission = new UserPermission();
    $userPermission->user_id = $userId;
    $userPermission->page_id = (int) $pageId;
    $userPermission->add_page = $add;
    $userPermission->edit_page = $edit;
    $userPermission->search_page = $search;
    $userPermission->delete_page = $delete;
    $userPermission->print_page = $print;
    $userPermission->other_page = $other;

    $created = $userPermission->create();

    if (!$created) {
        echo json_encode([
            'status' => 'error',
            'message' => "Failed to save permission for page ID $pageId"
        ]);
        exit();
    }
}

echo json_encode(['status' => 'success', 'message' => 'User permissions saved successfully.']);
exit();
?>