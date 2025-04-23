<?php
include '../../class/include.php';

if (isset($_GET['userTypeId'])) {
    $userTypeId = $_GET['userTypeId'];

    // Get the pages and their permissions for the selected user type
    $permissionsData = getPermissionsForUserType($userTypeId);

    echo json_encode($permissionsData);
    exit;
}

function getPermissionsForUserType($userTypeId)
{
    // Assume the function fetches pages and their permissions for a specific user type.
    // This is just a sample, adjust based on your actual database structure.

    $pages = [];
    $PAGES = new Pages(null);

    // Get all pages
    foreach ($PAGES->all() as $page) {
        $PAGE_CATEGORY = new PageCategory($page['page_category']);

        // Fetch permissions for the user type (you should implement this based on your DB structure)
        $permissions = getPermissionsForPageAndUserType($page['id'], $userTypeId);

        $pages[] = [
            'pageId' => $page['id'],
            'pageCategory' => $PAGE_CATEGORY->name,
            'pageName' => $page['page_name'],
            'permissions' => $permissions
        ];
    }

    return ['pages' => $pages];
}

function getPermissionsForPageAndUserType($pageId, $userTypeId)
{
    $PERMISSIONS = new Permission(null);
    $USERPERMISSION = new UserPermission(null);
 
    // Secure the IDs
    $pageId = (int) $pageId;
    $userTypeId = (int) $userTypeId;

    // Initialize all permissions to false
    $permissions = [];
    foreach ($PERMISSIONS->all() as $perm) {
        $key = strtolower($perm['permission_name']);
        $permissions[$key] = false;
    }

    // Get all user_permission records for this user and page
    $userPermissions = $USERPERMISSION->getUserPermissionByPages($userTypeId, $pageId);

    // If user has permissions, map permission IDs to names
    foreach ($userPermissions as $up) {
        $permObj = new Permission($up['permission_id']);
        $key = strtolower($permObj->permission_name);
        if (isset($permissions[$key])) {
            $permissions[$key] = true;
        }
    }

    return $permissions;
}


?>