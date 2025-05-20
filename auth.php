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
$company_id = $US->company_id;

$COMPANY_PROFILE_DETAILS = new CompanyProfile($company_id);

//add account year start date and end date 
$year_start = '2024-04-01';
$year_end = '2025-03-31';

$DOCUMENT_TRACKINGS = new DocumentTracking(NULL);
$doc_id = $DOCUMENT_TRACKINGS->getAllByCompanyAndYear($company_id, $year_start, $year_end);

 

?>