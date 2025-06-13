<?php
if (!isset($_SESSION)) {
    session_start();
}

$USER = new User(NULL);
if (!$USER->authenticate()) {
    redirect('login.php');
}

$USER_PERMISSION = new UserPermission();

$page_id = $_GET['page_id'] ?? null;
$current_page = basename($_SERVER['PHP_SELF']);

$skipPages = ['common.php', 'help.php'];

$USER_PERMISSION->checkAccess($page_id);

// Get company details
$US = new User($_SESSION['id']);
$company_id = $US->company_id;

$COMPANY_PROFILE_DETAILS = new CompanyProfile($company_id);

// Add account year start date and end date 
$year_start = '2025-04-01';
$year_end = '2026-03-31';

$DOCUMENT_TRACKINGS = new DocumentTracking(NULL);
$doc_id = $DOCUMENT_TRACKINGS->getAllByCompanyAndYear($company_id, $year_start, $year_end);

$PERMISSIONS = $USER_PERMISSION->hasPermission($_SESSION['id'], $page_id ?? 0);
?>