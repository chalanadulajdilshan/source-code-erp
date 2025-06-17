<!doctype html>
<?php
include 'class/include.php';

?>

<html lang="en">

<head>

    <meta charset="utf-8" />
    <title> Manage Sales Summary | <?php echo $COMPANY_PROFILE_DETAILS->name ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="<?php echo $COMPANY_PROFILE_DETAILS->name ?>" name="author" />
    <!-- include main CSS -->
    <?php include 'main-css.php' ?>

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
                                <a href="#" class="btn btn-primary" id="print_btn">
                                    <i class="uil uil-save me-1"></i> View
                                </a>
                               

                            </div>

                            <div class="col-md-4 text-md-end text-start mt-3 mt-md-0">
                                <ol class="breadcrumb m-0 justify-content-md-end">
                                    <li class="breadcrumb-item"><a href="javascript: void(0);">Dashboard</a></li>
                                    <li class="breadcrumb-item active"> Manage Sales Summary </li>
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
                                                <h5 class="font-size-16 mb-1">Manage Sales Summary </h5>
                                                <p class="text-muted text-truncate mb-0">Fill all information below to
                                                    Manage Sales Summary </p>
                                            </div>
                                            <div class="flex-shrink-0">
                                                <i class="mdi mdi-chevron-up accor-down-icon font-size-24"></i>
                                            </div>
                                        </div>

                                        <div class="p-4">
                                            <form id="form-data" autocomplete="off">
                                                <div class="row">

                                                    <div class="col-md-2">
                                                        <label for="customerCode" class="form-label">Customer
                                                            Code</label>
                                                        <div class="input-group mb-3">
                                                            <input id="customer_code" name="customer_code" type="text"
                                                                placeholder="Customer code" class="form-control"
                                                                readonly>
                                                            <button class="btn btn-info" type="button"
                                                                data-bs-toggle="modal" data-bs-target="#customerModal">
                                                                <i class="uil uil-search me-1"></i>
                                                            </button>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-4">
                                                        <label for="customerName" class="form-label">Customer
                                                            Name</label>
                                                        <div class="input-group mb-3">
                                                            <input id="customer_name" name="customer_name" type="text"
                                                                class="form-control" placeholder="Enter Customer Name"
                                                                readonly>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-4">
                                                        <label for="customerAddress" class="form-label">Customer
                                                            Address</label>
                                                        <div class="input-group mb-3">
                                                            <input id="customer_address" name="customer_address"
                                                                type="text" class="form-control"
                                                                placeholder="Enter customer address" readonly>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-2 d-flex align-items-end">
                                                        <div class="form-check mb-4">
                                                            <input class="form-check-input" type="checkbox"
                                                                id="checkAllStudents">
                                                            <label class="form-check-label" for="checkAllStudents">
                                                                Check All Customers
                                                            </label>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-3">
                                                        <label for="select" class="form-label">Select
                                                            Status</Select></label>
                                                        <div class="input-group mb-3">
                                                            <select id="selectStatus" name="selectStatus"
                                                                class="form-select">
                                                                <option value="">-- Select Status--</option>
                                                                <option value="sales">Sales</option>
                                                                <option value="return">Return</option>
                                                                <option value="summery">Summery</option>
                                                                <option value="reciept">Reciept</option>
                                                                <option value="item_sales">Item Sales</option>
                                                            </select>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-3">
                                                        <label for="from_date" class="form-label">From Date</label>
                                                        <div class="input-group" id="datepicker2">
                                                            <input type="texentry_datet"
                                                                class="form-control date-picker" id="from_date"
                                                                name="from_date"> <span class="input-group-text"><i
                                                                    class="mdi mdi-calendar"></i></span>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-3">
                                                        <label for="to_date" class="form-label">To Date</label>
                                                        <div class="input-group" id="datepicker2">
                                                            <input type="texentry_datet"
                                                                class="form-control date-picker" id="to_date"
                                                                name="to_date"> <span class="input-group-text"><i
                                                                    class="mdi mdi-calendar"></i></span>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-3">
                                                        <label for="date_range" class="form-label">Date
                                                            Range</Select></label>
                                                        <div class="input-group mb-3">
                                                            <select id="date_range" name="date_range"
                                                                class="form-select">
                                                                <option value="">-- Date Range --</option>
                                                                <option value="1">One Week</option>
                                                                <option value="2">One Month</option>
                                                                <option value="3">90 Days</option>
                                                                <option value="4">120 Days</option>
                                                            </select>
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
                    <?php include 'footer.php' ?>
                </div>
            </div>
        </div>



        <!-- Right bar overlay-->
        <div class="rightbar-overlay"></div>

        <!-- JAVASCRIPT -->
        <script src="assets/libs/jquery/jquery.min.js"></script>
        <!-- /////////////////////////// -->
        <script src="ajax/js/sales-summary.js"></script> 
        <script src="ajax/js/common.js"></script>


        <!-- include main js  -->
        <?php include 'main-js.php' ?>

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