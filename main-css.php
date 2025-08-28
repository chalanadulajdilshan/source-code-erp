<?php
// Get company profile for favicon
if (!isset($COMPANY_PROFILE)) {
    $COMPANY_PROFILE = new CompanyProfile(1);
}
?>
<link rel="shortcut icon" href="<?php echo !empty($COMPANY_PROFILE->image_name) ? 'uploads/company-logos/' . $COMPANY_PROFILE->image_name : 'assets/images/logo.png'; ?>">

<!-- Bootstrap Css -->
<link href="assets/css/bootstrap.min.css" id="bootstrap-style" rel="stylesheet" type="text/css" />
<!-- Icons Css -->
<link href="assets/css/icons.min.css" rel="stylesheet" type="text/css" />
<!-- App Css-->
<link href="assets/css/app.min.css" id="app-style" rel="stylesheet" type="text/css" />
<link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.0/css/line.css">

<link href="assets/libs/sweetalert/sweetalert.css" rel="stylesheet" type="text/css" />

<link href="assets/css/preloader.css" rel="stylesheet" type="text/css" />
<!-- Responsive datatable examples -->
<!-- DataTables -->
<link href="assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css" rel="stylesheet" type="text/css" />
<link href="assets/libs/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css" rel="stylesheet" type="text/css" />
<link href="assets/libs/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css" rel="stylesheet"
    type="text/css" />
<link rel="stylesheet" href="https://code.jquery.com/ui/1.14.1/themes/base/jquery-ui.css">