<!doctype html>
<?php
include 'class/include.php';

?>

<html lang="en">

<head>

    <meta charset="utf-8" />
    <title> Manage Accounts Master | <?php echo $COMPANY_PROFILE_DETAILS->name ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="#" name="description" />
    <meta content="Themesbrand" name="#" />
    <!-- App favicon -->
    <link rel="shortcut icon" href="assets/images/favicon.ico">

    <!-- Bootstrap Css -->
    <link href="assets/css/bootstrap.min.css" id="bootstrap-style" rel="stylesheet" type="text/css" />
    <!-- Icons Css -->
    <link href="assets/css/icons.min.css" rel="stylesheet" type="text/css" />
    <!-- App Css-->
    <link href="assets/css/app.min.css" id="app-style" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.0/css/line.css">
    <link href="assets/libs/sweetalert/sweetalert.css" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="assets/libs/@chenfengyuan/datepicker/datepicker.min.css">
    <link href="assets/libs/bootstrap-datepicker/css/bootstrap-datepicker.min.css" rel="stylesheet">
    <link href="assets/css/preloader.css" rel="stylesheet" type="text/css" />
    <!-- Responsive datatable examples -->
    <!-- DataTables -->
    <link href="assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css" rel="stylesheet" type="text/css" />
    <link href="assets/libs/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css" rel="stylesheet"
        type="text/css" />
    <link href="assets/libs/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css" rel="stylesheet"
        type="text/css" />
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.14.1/themes/base/jquery-ui.css">



</head>

<body data-layout="horizontal" data-topbar="colored" class="someBlock">

    </head>

    <body data-layout="horizontal" data-topbar="colored">

        <!-- Begin page -->
        <div id="layout-wrapper">

            <?php include 'navigation.php' ?>

            <!-- ============================================================== -->
            <!-- Start right Content here -->
            <!-- ============================================================== -->
            <div class="main-content">
                <div class="page-content">
                    <div class="container-fluid">
                        <div class="row mb-4">
                            <div class="col-md-8 d-flex align-items-center flex-wrap gap-2">
                                <a href="#" class="btn btn-success" id="new">
                                    <i class="uil uil-plus me-1"></i> New
                                </a>
                                <a href="#" class="btn btn-primary" id="create">
                                    <i class="uil uil-save me-1"></i> Save
                                </a>
                                <a href="#" class="btn btn-warning" id="update">
                                    <i class="uil uil-edit me-1"></i> Update
                                </a>
                                <a href="#" class="btn btn-danger delete-accounts  " style="display:none">
                                    <i class="uil uil-trash-alt me-1"></i> Delete
                                </a>

                            </div>

                            <div class="col-md-4 text-md-end text-start mt-3 mt-md-0">
                                <ol class="breadcrumb m-0 justify-content-md-end">
                                    <li class="breadcrumb-item"><a href="javascript: void(0);">Dashboard</a></li>
                                    <li class="breadcrumb-item active"> Manage Accounts Master  </li>
                                </ol>
                            </div>
                        </div>
                        <!--- Hidden Values -->


                        <!-- end page title -->

                        <div class="row">
                            <div class="col-lg-12">
                                <div class="card">
                                    <div class="p-4">
                                        <div class="d-flex align-items-center">
                                            <div class="flex-shrink-0 me-3">
                                                <div class="avatar-xs">
                                                    <div
                                                        class="avatar-title rounded-circle bg-soft-primary text-primary">
                                                        01
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="flex-grow-1 overflow-hidden">
                                                <h5 class="font-size-16 mb-1">Manage Accounts Master  </h5>
                                                <p class="text-muted text-truncate mb-0">Fill all information below to
                                                    Manage Accounts Master  </p>
                                            </div>
                                            <div class="flex-shrink-0">
                                                <i class="mdi mdi-chevron-up accor-down-icon font-size-24"></i>
                                            </div>
                                        </div>

                                        <div class="p-4">
                                            <form id="form-data" autocomplete="off">
                                                <div class="row">

                                                    <div class="col-md-2">
                                                        <label class="form-label" for="code">Account Code </label>
                                                        <div class="input-group mb-3">
                                                            <input id="code" name="code" type="text" value=""
                                                                placeholder="Ref No" class="form-control" readonly>
                                                            <button class="btn btn-info" type="button"
                                                                data-bs-toggle="modal"
                                                                data-bs-target="#accountModel">
                                                                <i class="uil uil-search me-1"></i>
                                                            </button>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-2">
                                                        <label for="name" class="form-label">Account Name</label>
                                                        <div class="input-group mb-3">
                                                            <input id="name" name="name" type="text"
                                                            placeholder="Enter Account Name" class="form-control">
                                                        </div>
                                                    </div>

                                                    <div class="col-md-5">
                                                        <label for="remark" class="form-label">Remark</label>
                                                        <div class="input-group mb-3">
                                                            <input id="remark" name="remark" type="text"
                                                            placeholder="Enter Remark" class="form-control">
                                                        </div>
                                                    </div>

                                                    <div class="col-md-3">
                                                        <label for="company" class="form-label">Company</Select></label>
                                                        <div class="input-group mb-3">
                                                            <select id="company" name="company" class="form-select">
                                                                <option value="">-- Select Company--</option>

                                                            </select>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-3">
                                                        <div class=" ">
                                                            <label class="form-label fw-bold">Account Type:</label><br />
                                                            <div class="form-check form-check-inline">
                                                                <input class="form-check-input" type="radio" name="account_type"
                                                                    id="manufacturing" value="manufacturing" checked>
                                                                <label class="form-check-label" for="manufacturing">Manufacturing</label>
                                                            </div>
                                                            <div class="form-check form-check-inline">
                                                                <input class="form-check-input" type="radio" name="payment_type"
                                                                    id="pnl_account" value="pnl_account">
                                                                <label class="form-check-label" for="pnl_account">PNL Account</label>
                                                            </div>
                                                            <div class="form-check form-check-inline">
                                                                <input class="form-check-input" type="radio" name="payment_type"
                                                                    id="balance_sheet_account" value="balance_sheet_account">
                                                                <label class="form-check-label" for="balance_sheet_account">Balance Sheet Account</label>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-3">
                                                        <label for="assets" class="form-label">Assets</Select></label>
                                                        <div class="input-group mb-3">
                                                            <select id="assets" name="assets" class="form-select">
                                                                <option value="">-- Select Assets--</option>

                                                            </select>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-2">
                                                        <label for="select" class="form-label">#</Select></label>
                                                        <div class="input-group mb-3">
                                                            <select id="#" name="#" class="form-select">
                                                                <option value="">-- Select --</option>

                                                            </select>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-2">
                                                        <label for="pnl_type" class="form-label">PNL Type</Select></label>
                                                        <div class="input-group mb-3">
                                                            <select id="pnl_type" name="pnl_type" class="form-select">
                                                                <option value="">-- Select PNL Type --</option>

                                                            </select>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-2">
                                                        <label for="select" class="form-label">#</Select></label>
                                                        <div class="input-group mb-3">
                                                            <select id="#" name="#" class="form-select">
                                                                <option value="">-- Select --</option>

                                                            </select>
                                                        </div>
                                                    </div>

                                                    <div class=" ">
                                                        <div class="form-check form-check-inline">
                                                            <input class="form-check-input" type="checkbox"
                                                                name="payment_type[]" id="cash" value="cash">
                                                            <label class="form-check-label" for="cash">Payment Account</label>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-3">
                                                        <label for="opening_balance" class="form-label">Opening Balance</label>
                                                        <div class="input-group mb-3">
                                                            <input id="opening_balance" name="opening_balance" type="text"
                                                            placeholder="Enter Opening Balance" class="form-control">
                                                        </div>
                                                    </div>

                                                    <div class="col-md-2">
                                                        <label for="as_at" class="form-label">As at</label>
                                                        <div class="input-group" id="datepicker2">
                                                            <input type="texentry_datet" class="form-control date-picker" id="as_at"
                                                                name="as_at"> <span class="input-group-text"><i
                                                                    class="mdi mdi-calendar"></i></span>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-3">
                                                        <label for="select" class="form-label">#</Select></label>
                                                        <div class="input-group mb-3">
                                                            <select id="#" name="#" class="form-select">
                                                                <option value="">LKR</option>

                                                            </select>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-2">
                                                        <label for="name" class="form-label">#</label>
                                                        <div class="input-group mb-3">
                                                            <input id="name" name="name" type="text"
                                                            placeholder="1" class="form-control" readonly>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-2">
                                                        <label for="parent_account" class="form-label">Parent Account</label>
                                                        <div class="input-group mb-3">
                                                            <input id="parent_account" name="parent_account" type="text"
                                                            placeholder="Enter Parent Account" class="form-control">
                                                        </div>
                                                    </div>

                                                    <div class="col-md-4">
                                                        <label for="description" class="form-label">Description</label>
                                                        <div class="input-group mb-3">
                                                            <input id="description" name="description" type="text"
                                                            placeholder="Description" class="form-control">
                                                        </div>
                                                    </div>


                                                    <div class="col-md-1">
                                                        <label for="name" class="form-label">#</label>
                                                        <div class="input-group mb-3">
                                                            <input id="name" name="name" type="text"
                                                            placeholder="0" class="form-control" readonly>
                                                        </div>
                                                    </div>       
                                                </div>
                                            </form>
                                        </div>
                                    </div> 
                                </div> 
                            </div> 
                        </div> 
                    </div>
                </div>
            </div>
        </div>


        <!-- Right bar overlay-->
        <div class="rightbar-overlay"></div>

        <!-- JAVASCRIPT -->
        <script src="assets/libs/jquery/jquery.min.js"></script>
        <!-- /////////////////////////// -->
        <script src="ajax/js/quotation.js"></script>
        <script src="ajax/js/common.js"></script>
 

        <script src="assets/libs/sweetalert/sweetalert-dev.js"></script>
        <script src="assets/js/jquery.preloader.min.js"></script>

        <script src="assets/libs/bootstrap/js/bootstrap.bundle.min.js"></script>
        <script src="assets/libs/metismenu/metisMenu.min.js"></script>
        <script src="assets/libs/simplebar/simplebar.min.js"></script>
        <script src="assets/libs/node-waves/waves.min.js"></script>
        <script src="assets/libs/waypoints/lib/jquery.waypoints.min.js"></script>
        <script src="assets/libs/jquery.counterup/jquery.counterup.min.js"></script>
        <script src="assets/libs/bootstrap-datepicker/js/bootstrap-datepicker.min.js"></script>
        <script src="assets/libs/@chenfengyuan/datepicker/datepicker.min.js"></script>

        <!-- Required datatable js -->
        <script src="assets/libs/datatables.net/js/jquery.dataTables.min.js"></script>
        <script src="assets/libs/datatables.net-bs4/js/dataTables.bootstrap4.min.js"></script>
        <!-- Buttons examples -->
        <script src="assets/libs/datatables.net-buttons/js/dataTables.buttons.min.js"></script>
        <script src="assets/libs/datatables.net-buttons-bs4/js/buttons.bootstrap4.min.js"></script>
        <script src="assets/libs/jszip/jszip.min.js"></script>
        <script src="assets/libs/pdfmake/build/pdfmake.min.js"></script>
        <script src="assets/libs/pdfmake/build/vfs_fonts.js"></script>
        <script src="assets/libs/datatables.net-buttons/js/buttons.html5.min.js"></script>
        <script src="assets/libs/datatables.net-buttons/js/buttons.print.min.js"></script>
        <script src="assets/libs/datatables.net-buttons/js/buttons.colVis.min.js"></script>

        <!-- Responsive examples -->
        <script src="assets/libs/datatables.net-responsive/js/dataTables.responsive.min.js"></script>
        <script src="assets/libs/datatables.net-responsive-bs4/js/responsive.bootstrap4.min.js"></script>

        <!-- Datatable init js -->
        <script src="assets/js/pages/datatables.init.js"></script>
        <!-- apexcharts -->

        <script src="assets/js/pages/dashboard.init.js"></script>

        <!-- App js -->
        <script src="assets/js/app.js"></script>
        <script src="https://code.jquery.com/ui/1.14.1/jquery-ui.js"></script>
        <script>
            $('#quotation_table').DataTable();
            $(function () {
                // Initialize the datepicker
                $(".date-picker").datepicker({
                    dateFormat: 'yy-mm-dd' // or 'dd-mm-yy' as per your format
                });

                // Set today's date as default value
                var today = $.datepicker.formatDate('yy-mm-dd', new Date());
                $(".date-picker").val(today);
            });
        </script>

    </body>

</html>