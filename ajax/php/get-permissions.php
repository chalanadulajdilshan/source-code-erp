<?php
include '../../class/include.php';

if (isset($_GET['userId'])) { // Changed from 'userTypeId' to 'userId'
    $userId = (int)$_GET['userId'];

    // Get the pages and their permissions for the selected user
    $permissionsData = getPermissionsForUser($userId); // Changed function name

    echo json_encode($permissionsData);
    exit;
}

function getPermissionsForUser($userId) // Changed function name
{
    $pages = [];
    $PAGES = new Pages(null);

    // Get all pages
    foreach ($PAGES->all() as $page) {
        $PAGE_CATEGORY = new PageCategory($page['page_category']);

        // Fetch permissions for the user
        $permissions = getPermissionsForPageAndUser($page['id'], $userId); // Changed function name

        // Flatten permission values for frontend
        $pages[] = [
            'pageId'       => $page['id'],
            'pageCategory' => $PAGE_CATEGORY->name,
            'pageName'     => $page['page_name'],
            'add_page'     => $permissions['add'],
            'edit_page'    => $permissions['edit'],
            'delete_page'  => $permissions['delete'],
            'search_page'  => $permissions['search'],
            'print_page'   => $permissions['print'],
            'other_page'   => $permissions['other']
        ];
    }

    return ['pages' => $pages];
}

function getPermissionsForPageAndUser($pageId, $userId) // Changed function name
{
    $pageId = (int) $pageId;
    $userId = (int) $userId;

    $permissions = [
        'add'    => false,
        'edit'   => false,
        'delete' => false,
        'search' => false,
        'print'  => false,
        'other'  => false,
    ];

    $db = new Database();
    $query = "SELECT * FROM `user_permission` 
              WHERE `user_id` = $userId AND `page_id` = $pageId 
              LIMIT 1";

    $result = $db->readQuery($query);

    if ($row = mysqli_fetch_assoc($result)) {
        $permissions['add']    = (bool) $row['add_page'];
        $permissions['edit']   = (bool) $row['edit_page'];
        $permissions['delete'] = (bool) $row['delete_page'];
        $permissions['search'] = (bool) $row['search_page'];
        $permissions['print']  = (bool) $row['print_page'];
        $permissions['other']  = (bool) $row['other_page'];
    }

    return $permissions;
}
?>