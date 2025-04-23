<?php
include '../../class/include.php';

if (!isset($_POST['userType']) || !isset($_POST['permissions']) || empty($_POST['permissions'])) {
    echo json_encode(['status' => 'error', 'message' => 'User type or permissions not provided.']);
    exit();
}

$userTypeId = (int) $_POST['userType'];
$permissions = $_POST['permissions'];

$db = new Database();

// Delete existing permissions for this user type
$deleteQuery = "DELETE FROM `user_permission` WHERE `user_id` = $userTypeId";
$db->readQuery($deleteQuery);

// Insert new permissions
foreach ($permissions as $pageId => $permList) {
    foreach ($permList as $permissionId) {
        $userPermission = new UserPermission();
        $userPermission->user_id = $userTypeId;
        $userPermission->page_id = (int)$pageId;
        $userPermission->permission_id = (int)$permissionId;
        $createdPermissionId = $userPermission->create();

        if (!$createdPermissionId) {
            echo json_encode([
                'status' => 'error',
                'message' => "Failed to save permission ID $permissionId for page ID $pageId"
            ]);
            exit();
        }
    }
}

echo json_encode(['status' => 'success', 'message' => 'Permissions saved successfully.']);
exit();
?>
