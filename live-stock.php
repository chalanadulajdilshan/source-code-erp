<!doctype html>
<?php
include 'class/include.php';
include 'auth.php';
?>

<html lang="en">

<head>
    <meta charset="utf-8" />
    <title>Live Stocks | <?php echo $COMPANY_PROFILE_DETAILS->name ?> </title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="<?php echo $COMPANY_PROFILE_DETAILS->name ?>" name="author" />
    <!-- include main CSS -->
    <?php include 'main-css.php' ?>

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

                                            <!-- DataTable -->
                                            <div class="table-responsive mt-4">
                                                <table class="table table-bordered dt-responsive nowrap" id="stockTable"
                                                    style="border-collapse: collapse; border-spacing: 0; width: 100%;">
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
                                                            <th>Stock Status</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <!-- Sample data - replace with your dynamic data -->
                                                        <?php
                                                        $ITEM_MASTER = new ItemMaster()
                                                            ?>
                                                        <tr>
                                                            <td>ITM001</td>
                                                            <td>Sample Item Description</td>
                                                            <td>Brand A</td>
                                                            <td>Pattern 1</td>
                                                            <td>Category 1</td>
                                                            <td>$10.00</td>
                                                            <td>$15.00</td>
                                                            <td>$12.00</td>
                                                            <td>25</td>
                                                            <td>
                                                                <div
                                                                    class="form-check form-switch d-flex justify-content-center">
                                                                    <input class="form-check-input" type="checkbox"
                                                                        checked>
                                                                </div>
                                                            </td>
                                                        </tr>

                                                        <tr>
                                                            <td>ITM002</td>
                                                            <td>Another Item Description</td>
                                                            <td>Brand B</td>
                                                            <td>Pattern 2</td>
                                                            <td>Category 2</td>
                                                            <td>$20.00</td>
                                                            <td>$30.00</td>
                                                            <td>$25.00</td>
                                                            <td>50</td>
                                                            <td>
                                                                <div
                                                                    class="form-check form-switch d-flex justify-content-center">
                                                                    <input class="form-check-input" type="checkbox">
                                                                </div>
                                                            </td>
                                                        </tr>

                                                        <!-- Add more sample rows as needed -->
                                                    </tbody>
                                                </table>
                                            </div>

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

    <!-- JAVASCRIPT -->
    <script src="assets/libs/jquery/jquery.min.js"></script>
    <script src="assets/libs/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- include main js  -->
    <?php include 'main-js.php' ?>

    <!-- Datatable init js -->
    <script>
        $(document).ready(function () {
            // Initialize DataTable
            $('#stockTable').DataTable({
                lengthChange: true,

                responsive: true,
                language: {
                    paginate: {
                        previous: "<i class='mdi mdi-chevron-left'>",
                        next: "<i class='mdi mdi-chevron-right'>"
                    }
                },
                drawCallback: function () {
                    $('.dataTables_paginate > .pagination').addClass('pagination-rounded');
                }
            });

            // Add buttons to DataTable
            $('#stockTable').DataTable().buttons().container()
                .appendTo('#stockTable_wrapper .col-md-6:eq(0)');
        });
    </script>

</body>

</html>