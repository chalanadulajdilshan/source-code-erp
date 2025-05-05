<!doctype html>
<?php
include 'class/include.php';

$SALES_INVOICE = new SalesInvoice(NULL);

// Get the last inserted package id
$lastId = $SALES_INVOICE->getLastID();
$invoice_id = 'IN00' . $lastId + 1;
?>

<html lang="en">

<head>

    <meta charset="utf-8" />
    <title>Horizontal Layout | Minible - Admin & Dashboard Template</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="#" name="description" />
    <meta content="Themesbrand" name="author" />
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

    <link href="assets/css/preloader.css" rel="stylesheet" type="text/css" />
    <!-- Responsive datatable examples -->
    <!-- DataTables -->
    <link href="assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css" rel="stylesheet" type="text/css" />
    <link href="assets/libs/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css" rel="stylesheet"
        type="text/css" />
    <link href="assets/libs/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css" rel="stylesheet"
        type="text/css" />

     


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
                                <a href="#" class="btn btn-primary" id="print">
                                    <i class="uil uil-save me-1"></i> Print
                                </a>
                                <a href="#" class="btn btn-warning" id="search">
                                    <i class="uil uil-search me-1"></i> Search
                                </a>
                                <a href="#" class="btn btn-danger delete-category">
                                    <i class="uil uil-trash-alt me-1"></i> Delete
                                </a>

                            </div>

                            <div class="col-md-4 text-md-end text-start mt-3 mt-md-0">
                                <ol class="breadcrumb m-0 justify-content-md-end">
                                    <li class="breadcrumb-item"><a href="javascript: void(0);">Dashboard</a></li>
                                    <li class="breadcrumb-item active">Stock Transfer </li>
                                </ol>
                            </div>
                        </div>
                        <!--- Hidden Values -->
                        <input type="hidden" id="item_id">
                        <input type="hidden" id="availableQty">

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
                                                <h5 class="font-size-16 mb-1">Stock Transfer </h5>
                                                <p class="text-muted text-truncate mb-0">Fill all information below</p>
                                            </div>
                                            <div class="flex-shrink-0">
                                                <i class="mdi mdi-chevron-up accor-down-icon font-size-24"></i>
                                            </div>
                                        </div>

                                    </div>

                                    <div class="p-4">
                                        <form id="form-data">
                                            <div class="row">

                                                <div class="col-md-2">
                                                    <label for="ST_No" class="form-label">ST No</label>
                                                    <div class="input-group mb-3">
                                                        <input id="st_no" name="st_no" type="text"
                                                            class="form-control" readonly>
                                                    </div>
                                                </div>

                                                <div class="col-md-2">
                                                    <label class="form-label" for="entry_date">Date</label>
                                                    <input id="entry_date" name="entry_date" type="date"
                                                        class="form-control">
                                                </div>

                                                
                                                <div class="col-md-3">
                                                    <label for="Department" class="form-label">Department</label>
                                                    <div class="input-group mb-3">
                                                        <select id="department_id" name="department_id" class="form-select">

                                                            

                                                        </select>
                                                    </div>
                                                </div>


                                                <hr class="my-4">


                                                <div class="col-md-3">
                                                    <label for="fromDepartment" class="form-label">From Department</label>
                                                    <div class="input-group mb-3">
                                                        <select id="from_department" name="from_department" class="form-select">

                                                            

                                                        </select>
                                                    </div>
                                                </div>


                                                <div class="col-md-3">
                                                    <label for="itemCode" class="form-label">Item Code</label>
                                                    <div class="input-group mb-3">
                                                        <input id="itemCode" name="itemCode" type="text"
                                                            placeholder="Item Code" class="form-control">

                                                        <button class="btn btn-info" type="button"
                                                            data-bs-toggle="modal" data-bs-target="#itemCode">
                                                            <i class="uil uil-search me-1"></i> Find
                                                        </button>

                                                    </div>
                                                    <div id="customerList" class="list-group position-absolute w-100">
                                                    </div>

                                                </div>

                                                <div class="col-md-4">
                                                    <label for="Description" class="form-label">Description</label>
                                                    <div class="input-group mb-3">
                                                        <input id="description" name="description" type="text"
                                                            class="form-control" placeholder="Description"
                                                            readonly>
                                                    </div>
                                                </div>

                                                <div class="col-md-1">
                                                    <label class="form-label">Quantity</label>
                                                    <div class="input-group mb-3">
                                                        <input type="number" id="Qty" class="form-control"
                                                            placeholder="Qty" oninput="calculatePayment()">

                                                        <button class="btn btn-info ms-1" type="button"
                                                            data-bs-toggle="modal" data-bs-target="#itemCode">
                                                            <i class="uil uil-plus"></i>
                                                        </button>
                                                    </div>

                                                </div>

                                                

                                                <div class="container" style="max-width: 1000px; margin-left: 10px;">


                                                    <div class="col-md-3">
                                                        <label for="availabaleQty" class="form-label">Available Quantity</label>
                                                        <div class="input-group mb-3">
                                                            <input id="availabale_qty" name="availabale_qty" type="text"
                                                                class="form-control" readonly>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-3">
                                                        <label for="ARN_No" class="form-label">ARN No</label>
                                                        <div class="input-group mb-3">
                                                            <input id="arn_no" type="text" class="form-control" readonly>
                                                            
                                                            <button class="btn btn-info" type="button"
                                                                id="open-item-modal">
                                                                <i class="uil uil-search me-1"></i>
                                                            </button>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-3">
                                                        <label for="ARN_Date" class="form-label">ARN Date</label>
                                                        <div class="input-group mb-3">
                                                            <input id="arn_date" name="arn_date" type="text"
                                                                class="form-control" readonly>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-2">
                                                        <button class="btn btn-success" type="button" id="update-arn">
                                                            <i class="uil uil-edit"></i> Update ARN
                                                        </button>
                                                    </div>

                                                </div>

                                            </div>

                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div> <!-- container-fluid -->
                </div>

                <?php include 'footer.php' ?>

            </div>
            <!-- end main content-->

        </div>
        <!-- END layout-wrapper -->

        <!-- Right bar overlay-->
        <div class="rightbar-overlay"></div>

        <!-- JAVASCRIPT -->
        <script src="assets/libs/jquery/jquery.min.js"></script>
        <script src="ajax/js/customer-master.js"></script>

        <!-- /////////////////////////// -->

        <script src="ajax/js/sales-invoice.js"></script>

        <script src="assets/libs/sweetalert/sweetalert-dev.js"></script>
        <script src="assets/js/jquery.preloader.min.js"></script>

        <script src="assets/libs/bootstrap/js/bootstrap.bundle.min.js"></script>
        <script src="assets/libs/metismenu/metisMenu.min.js"></script>
        <script src="assets/libs/simplebar/simplebar.min.js"></script>
        <script src="assets/libs/node-waves/waves.min.js"></script>
        <script src="assets/libs/waypoints/lib/jquery.waypoints.min.js"></script>
        <script src="assets/libs/jquery.counterup/jquery.counterup.min.js"></script>

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

    </body>

</html>