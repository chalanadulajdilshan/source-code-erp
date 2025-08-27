<?php
if (!isset($_SESSION)) {
    session_start();
}

$USER = new User(NULL);
if (!$USER->authenticate()) {
    redirect('login.php');
}

$USER_PERMISSION = new UserPermission();

// Get the current page
$current_page = basename($_SERVER['PHP_SELF']);

// Add non-permission pages dynamically
$NP = new NonPermissionPage();
$nonPermissionPages = $NP->all(); // fetch all non-permission pages

foreach ($nonPermissionPages as $page) {
    $skipPages[] = $page['page']; // add page name to skipPages array
}

// Check access if current page is not in skipPages
if (!in_array($current_page, $skipPages)) {
    $page_id = $_GET['page_id'] ?? null;
    $USER_PERMISSION->checkAccess($page_id);
}

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
