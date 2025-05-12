<?php
if (!isset($_SESSION)) {
    session_start();
}

$USER = new User(NULL);
if (!$USER->authenticate()) {
    redirect('login.php');
}
 
$USER_PERMISSION = new UserPermission(null);

$page_id = $_GET['page_id'];
$USER_PERMISSION->checkAccess($page_id);

//get company details

$US = new User($_SESSION['id']);
$COMPANY_PROFILE_DETAILS = new CompanyProfile($US->company_id);

?>