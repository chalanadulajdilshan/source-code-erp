 <?php
$USER_PERMISSION = new UserPermission(null);

$page_id = $_GET['page_id'];
$USER_PERMISSION->checkAccess($page_id);

?>