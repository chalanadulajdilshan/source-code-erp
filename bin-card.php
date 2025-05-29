<!doctype html>
<?php
include 'class/include.php';

?>
<html lang="en">

<head>

    <meta charset="utf-8" />
    <title>Bin Card</title>
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

<body data-layout="horizontal" data-topbar="colored">



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
                                <a href="#" class="btn btn-primary" id="create">
                                    <i class="uil uil-save me-1"></i> Print Bin Card
                                </a>
                                <a href="#" class="btn btn-primary" id="create">
                                    <i class="uil uil-save me-1"></i> Print Sup. Card
                                </a>
                                <a href="#" class="btn btn-primary" id="create">
                                    <i class="uil uil-save me-1"></i> Print Po. Card
                                </a>


                            </div>

                            <div class="col-md-4 text-md-end text-start mt-3 mt-md-0">
                                <ol class="breadcrumb m-0 justify-content-md-end">
                                    <li class="breadcrumb-item"><a href="javascript: void(0);">Dashboard</a></li>
                                    <li class="breadcrumb-item active">Bin Card </li>
                                </ol>
                            </div>
                        </div>

                        <!--- Hidden Values -->
                        <input type="hidden" id="item_id">
                        <input type="hidden" id="availableQty">

                        <!-- end page title -->

                        <!-- Form Section -->
                        <form class="card p-3 mb-4">
                            <div class="row g-3">

                                <div class="p-4">

                                    <div class="d-flex align-items-center">
                                        <div class="flex-shrink-0 me-3">
                                            <div class="avatar-xs">
                                                <div class="avatar-title rounded-circle bg-soft-primary text-primary">
                                                    01
                                                </div>
                                            </div>
                                        </div>
                                        <div class="flex-grow-1 overflow-hidden">
                                            <h5 class="font-size-16 mb-1">Bin Card </h5>
                                            <p class="text-muted text-truncate mb-0">Fill all information below</p>
                                        </div>
                                        <div class="flex-shrink-0">
                                            <i class="mdi mdi-chevron-up accor-down-icon font-size-24"></i>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-10">

                                    <div class="row mb-3">
                                        <div class="col">
                                            <label for="itemNo" class="form-label">Item No</label>
                                            <div class="input-group">
                                                <input id="item_no" name="item_no" type="text" placeholder="Item No"
                                                    class="form-control">
                                                <button class="btn btn-info" type="button" data-bs-toggle="modal"
                                                    data-bs-target="#itemModal">
                                                    <i class="uil uil-ellipsis-h"></i>
                                                </button>
                                            </div>
                                        </div>

                                        <div class="col">
                                            <label for="partNo" class="form-label">Part No</label>
                                            <div class="input-group">
                                                <input id="part_no" name="part_no" type="text" class="form-control"
                                                    placeholder="Enter Part No" readonly>
                                            </div>
                                        </div>

                                        <div class="col">
                                            <label for="Department" class="form-label">Department</label>
                                            <div class="input-group">
                                                <select id="department_id" name="department_id"
                                                    class="form-select"></select>
                                            </div>
                                        </div>

                                        <div class="col">
                                            <label for="itemName" class="form-label">Item Name</label>
                                            <div class="input-group">
                                                <input id="item_name" name="item_name" type="text" class="form-control"
                                                    placeholder="Enter Item Name" readonly>
                                            </div>
                                        </div>

                                        <div class="col">
                                            <label for="selling" class="form-label">Selling</label>
                                            <div class="input-group">
                                                <input id="selling" name="selling" type="text" class="form-control"
                                                    placeholder="" readonly>
                                            </div>
                                        </div>
                                    </div>


                                    <div class="row">
                                        <div class="col-md-3">
                                            <label class="form-label" for="date">From</label>
                                            <input id="date" name="date" type="date" class="form-control">
                                        </div>

                                        <div class="col-md-3">
                                            <label for="discount1" class="form-label">Discount 1</label>
                                            <div class="input-group">
                                                <input id="discount1" name="discount1" type="text" class="form-control"
                                                    placeholder="">
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <label for="discount2" class="form-label">Discount 2</label>
                                            <div class="input-group">
                                                <input id="discount2" name="discount2" type="text" class="form-control"
                                                    placeholder="">
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <label for="total" class="form-label">Total</label>
                                            <div class="input-group">
                                                <input id="total" name="total" type="text" class="form-control"
                                                    placeholder="" readonly>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-2 d-flex flex-column">
                                    <div class="d-flex flex-column justify-content-between flex-grow-1 h-100 w-100">
                                        <!-- Over 90 Stock -->
                                        <div class="bg-danger text-white text-center p-2   rounded">
                                            Over 90 Stock<br><strong>10</strong>
                                        </div>
                                        <!-- Stock in Hand -->
                                        <div class="bg-primary text-white text-center p-2 rounded">
                                            Stock in Hand<br><strong>10</strong>
                                        </div>
                                    </div>
                                </div>


                        </form>

                        <!-- Monthly Consumption -->
                        <div class="card p-3 mb-4">
                            <h5>Monthly Consumption</h5>
                            <div class="row">
                                <div class="col-md-2">
                                    <select class="form-select">
                                        <option>2025</option>
                                    </select>
                                </div>
                                <div class="col-md-10">
                                    <table class="table table-bordered table-sm text-center mb-0">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Jan</th>
                                                <th>Feb</th>
                                                <th>Mar</th>
                                                <th>Apr</th>
                                                <th>May</th>
                                                <th>Jun</th>
                                                <th>Jul</th>
                                                <th>Aug</th>
                                                <th>Sep</th>
                                                <th>Oct</th>
                                                <th>Nov</th>
                                                <th>Dec</th>
                                                <th>Avg for 12 Months</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td colspan="13" class="text-center text-muted">No items
                                                    added</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <!-- Show Transactions -->
                        <div class="mb-2">
                            <input type="checkbox" checked> Show Transactions
                        </div>

                        <!-- Transaction Table -->
                        <div class="table-responsive mb-4">
                            <table class="table table-bordered">
                                <thead class="table-light text-center">
                                    <tr>
                                        <th>Ref No</th>
                                        <th>Date</th>
                                        <th>Document Type</th>
                                        <th>Cost</th>
                                        <th>Selling</th>
                                        <th>Stk In</th>
                                        <th>Stk Out</th>
                                        <th>Stk Bal</th>
                                    </tr>
                                </thead>
                                <tbody class="text-center">
                                    <tr>
                                        <td colspan="12" class="text-center text-muted">No items
                                            added</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <!-- Stock In Hand Table -->
                        <div class="card p-3">
                            <h5>Stock In Hand</h5>
                            <table class="table table-bordered table-sm text-center mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Department</th>
                                        <th>Stock</th>
                                        <th>Pending Orders</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td colspan="12" class="text-center text-muted">No items
                                            added</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>

                <?php include 'footer.php' ?>

            </div>
        </div>

        <!-- Right bar overlay-->
        <div class="rightbar-overlay"></div>

        <!-- JAVASCRIPT -->
        <script src="assets/libs/jquery/jquery.min.js"></script>
        <script src="ajax/js/bin-card.js"></script>

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