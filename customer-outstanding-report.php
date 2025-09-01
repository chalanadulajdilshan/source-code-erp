<?php
include 'class/include.php';
include 'auth.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <title>Customer Outstanding Report | <?php echo $COMPANY_PROFILE_DETAILS->name ?> </title>

    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="<?php echo $COMPANY_PROFILE_DETAILS->name ?>" name="author" />
    <!-- include main CSS -->
    <?php include 'main-css.php' ?>
    <link href="assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css" rel="stylesheet" type="text/css">
    <link href="assets/libs/daterangepicker/daterangepicker.css" rel="stylesheet" type="text/css">
</head>

<body data-layout="horizontal" data-topbar="colored" class="someBlock">

    <!-- Begin page -->
    <div id="layout-wrapper">
        <?php include 'navigation.php'; ?>

        <div class="main-content">
            <div class="page-content">
                <div class="container-fluid">
                    <!-- start page title -->
                    <div class="row">
                        <div class="col-12">
                            <div class="page-title-box d-flex align-items-center justify-content-between">
                                <h4 class="mb-0">Customer Outstanding Report</h4>
                            </div>
                        </div>
                    </div>
                    <!-- end page title -->

                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body">
                                    <form id="reportForm">
                                        <div class="row mb-3">
                                            <div class="col-md-3">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="filterType" id="customerView" value="customer" checked>
                                                    <label class="form-check-label" for="customerView">
                                                        Customer View
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="filterType" id="dateView" value="date">
                                                    <label class="form-check-label" for="dateView">
                                                        Date View
                                                    </label>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Customer Filter -->
                                        <div class="col-md-2">
                                            <label for="customerCode" class="form-label">Customer Code</label>
                                            <div class="input-group mb-3">
                                                <input id="customer_code" name="customer_code" type="text"
                                                    placeholder="Select Customer" class="form-control" readonly>
                                                <input type="hidden" id="customer_id" name="customer_id">
                                                <button class="btn btn-info" type="button" data-bs-toggle="modal"
                                                    data-bs-target="#customerModal">
                                                    <i class="uil uil-search me-1"></i>
                                                </button>
                                            </div>
                                        </div>

                                        <!-- Date Filter -->
                                        <div class="row mt-3" id="dateFilter" style="display: none;">
                                            <div class="col-md-6">
                                                <div class="row">
                                                    <div class="col-md-5">
                                                        <label for="fromDate" class="form-label">From Date <span class="text-danger">*</span></label>
                                                        <input type="date" class="form-control" id="fromDate" name="fromDate" required>
                                                    </div>
                                                    <div class="col-md-5">
                                                        <label for="toDate" class="form-label">To Date <span class="text-danger">*</span></label>
                                                        <input type="date" class="form-control" id="toDate" name="toDate" required>
                                                    </div>
                                                    <div class="col-md-2 d-flex align-items-end">
                                                        <button type="button" class="btn btn-sm btn-outline-primary" id="setToday">Today</button>
                                                    </div>
                                                </div>
                                                <small class="text-muted">Select a date range to filter the report</small>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-12">
                                                <button type="button" class="btn btn-primary" id="searchBtn">
                                                    <i class="mdi mdi-magnify me-1"></i> Search
                                                </button>
                                                <button type="button" class="btn btn-secondary" id="resetBtn">
                                                    <i class="mdi mdi-refresh me-1"></i> Reset
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Results Table -->
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body">
                                    <table id="reportTable" class="table table-bordered dt-responsive nowrap w-100">
                                        <thead>
                                            <tr>
                                                <th>Invoice No</th>
                                                <th>Date</th>
                                                <th>Customer</th>
                                                <th>Invoice Amount</th>
                                                <th>Paid Amount</th>
                                                <th>Outstanding</th>
                                            </tr>
                                        </thead>
                                        <tbody id="reportTableBody">
                                            <!-- Data will be loaded via AJAX -->
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <th colspan="3" class="text-end">Total:</th>
                                                <th id="totalInvoice" class="text-danger">0.00</th>
                                                <th id="totalPaid" class="text-danger">0.00</th>
                                                <th id="totalOutstanding" class="text-danger">0.00</th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div> <!-- container-fluid -->
            </div>
            <!-- End Page-content -->

            <?php include 'footer.php'; ?>
        </div>
        <!-- end main content-->
    </div>
    <!-- END layout-wrapper -->

    <?php include 'customer-master-model.php'; ?>
    <?php include 'main-js.php'; ?>

    <!-- Required datatable js -->
    <script src="assets/libs/datatables.net/js/jquery.dataTables.min.js"></script>
    <script src="assets/libs/datatables.net-bs4/js/dataTables.bootstrap4.min.js"></script>
    <script src="assets/libs/moment/min/moment.min.js"></script>
    <script src="assets/libs/daterangepicker/daterangepicker.min.js"></script>

    <!-- Custom JS for Customer Outstanding Report -->
    <script src="ajax/js/customer-outstanding-report.js"></script>

    <script>
        $(document).ready(function() {
            // Toggle between customer and date views
            $('input[name="filterType"]').change(function() {
                const selectedValue = $(this).val();
                if (selectedValue === 'date') {
                    $('#customer_code').closest('.col-md-2').hide();
                    $('#dateFilter').show();
                    // Set default dates
                    const today = new Date().toISOString().split('T')[0];
                    $('#toDate').val(today);
                    // Set from date to first day of current month
                    const firstDay = new Date();
                    firstDay.setDate(1);
                    $('#fromDate').val(firstDay.toISOString().split('T')[0]);
                } else {
                    $('#customer_code').closest('.col-md-2').show();
                    $('#dateFilter').hide();
                }
            });

            // Set to today's date
            $('#setToday').click(function() {
                const today = new Date().toISOString().split('T')[0];
                $('#toDate').val(today);
            });

            // Validate date range
            $('#fromDate, #toDate').change(function() {
                const fromDate = new Date($('#fromDate').val());
                const toDate = new Date($('#toDate').val());

                if (fromDate > toDate) {
                    alert('From date cannot be after To date');
                    $(this).val('');
                }
            });
        });
    </script>

</body>

</html>