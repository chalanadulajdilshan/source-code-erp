<!doctype html>
<?php
include 'class/include.php';

?>


<html lang="en">

<head>

    <meta charset="utf-8" />
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

                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="card">

                                        <div class="p-4">
                                            <form id="form-data">
                                                <div class="row">

                                                    <form class="row mb-3">
                                                        <div class="col-md-2">
                                                            <select class="form-select form-select-sm">
                                                                <option>Show - 10</option>
                                                                <option>Show - 25</option>
                                                                <option>Show - 50</option>
                                                            </select>
                                                        </div>

                                                    <!-- Table -->
                                                    <div class="table-responsive mt-4">
                                                        <table class="table table-bordered" id="stockTable">
                                                            <thead class="table-light">
                                                                <tr>
                                                                    <th>Item Code</th>
                                                                    <th>Item Description</th>
                                                                    <th>Brand</th>
                                                                    <th>Pattern</th>
                                                                    <th>Category</th>
                                                                    <th>Cost</th>
                                                                    <th>Selling</th>
                                                                    <th>Dealer Price</th>
                                                                    <th>Quantity</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <tr>
                                                                    <td colspan="8" class="text-center text-muted">No items
                                                                    added</td>
                                                                    <th>
                                                                    <div class="form-check form-switch d-flex justify-content-center">
                                                                        <input class="form-check-input" type="checkbox" id="stockToggle" checked>
                                                                    </div>
                                                                    <small class="d-block text-center">Stock</small>
                                                                </th>
                                                                </tr>
                                                                <tr>
                                                                    <td colspan="9" class="text-start text-muted">Total Records - </td>
                                                                </tr>
                                                            
                                                                <!-- Sample Static Rows -->
                                                                <tr>
                                                                    <td colspan="9" class="text-center text-muted">No items
                                                                    added</td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                    </div>

                                                    <!-- Pagination UI (static) -->
                                                    <nav>
                                                        <ul class="pagination pagination-sm">
                                                            <li class="page-item disabled"><a class="page-link">Previous</a></li>
                                                            <li class="page-item active"><a class="page-link">1</a></li>
                                                            <li class="page-item"><a class="page-link">2</a></li>
                                                            <li class="page-item"><a class="page-link">3</a></li>
                                                            <li class="page-item"><a class="page-link">Next</a></li>
                                                        </ul>
                                                    </nav>

                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- container-fluid -->
                    </div>
                </div> 
                <!-- end main content-->                    
        </div>
        <!-- END layout-wrapper -->

    <script src="assets/libs/bootstrap/js/bootstrap.bundle.min.js"></script>
</body>
</html>
