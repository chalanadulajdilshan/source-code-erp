<!doctype html>
<?php
include 'class/include.php';

 
// $PAYMENT_RECIPT = new PaymentRecipt();

// // Get the last inserted package id
// $lastId = $PAYMENT_RECIPT->getLastID();
// $recipt_id = 'PR00' . $lastId + 1;

?>

<html lang="en">

<head>

    <meta charset="utf-8" />
    <title> Manage Payment Recipt | <?php echo $COMPANY_PROFILE_DETAILS->name ?></title>
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
                                <a href="#" class="btn btn-danger delete-recipt  " style="display:none">
                                    <i class="uil uil-trash-alt me-1"></i> Delete
                                </a>

                            </div>

                            <div class="col-md-4 text-md-end text-start mt-3 mt-md-0">
                                <ol class="breadcrumb m-0 justify-content-md-end">
                                    <li class="breadcrumb-item"><a href="javascript: void(0);">Dashboard</a></li>
                                    <li class="breadcrumb-item active"> Manage Payment Recipt </li>
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
                                                <h5 class="font-size-16 mb-1">Manage Payment Recipt </h5>
                                                <p class="text-muted text-truncate mb-0">Fill all information below to
                                                    Manage Payment Recipt </p>
                                            </div>
                                            <div class="flex-shrink-0">
                                                <i class="mdi mdi-chevron-up accor-down-icon font-size-24"></i>
                                            </div>
                                        </div>

                                    </div>

                                            <div class="p-4">
                                                <form id="form-data" autocomplete="off">
                                                    <div class="row">

                                                        <div class="col-md-2">
                                                            <label for="reciptNo" class="form-label">Recipt No</label>
                                                            <div class="input-group mb-3">
                                                                <input type="text" id="code" name="code"
                                                                    value="" class="form-control"
                                                                    readonly>

                                                                <button class="btn btn-info" type="button"
                                                                    data-bs-toggle="modal" data-bs-target="#reciptModel">
                                                                    <i class="uil uil-search me-1"></i>
                                                                </button>
                                                            </div>
                                                        </div>

                                                        <div class="col-md-2">
                                                            <label for="customerCode" class="form-label">Customer Code</label>
                                                            <div class="input-group mb-3">
                                                                <input id="customer_code" name="customer_code" type="text"
                                                                    placeholder="Customer code" class="form-control" readonly>
                                                                <button class="btn btn-info" type="button"
                                                                    data-bs-toggle="modal" data-bs-target="#customerModal">
                                                                    <i class="uil uil-search me-1"></i>
                                                                </button>
                                                            </div>
                                                        </div>

                                                        <div class="col-md-3">
                                                            <label for="customerName" class="form-label">Customer Name</label>
                                                            <div class="input-group mb-3">
                                                                <input id="customer_name" name="customer_name" type="text"
                                                                    class="form-control" placeholder="Enter Customer Name"
                                                                    readonly>
                                                            </div>
                                                        </div>

                                                        <div class="col-md-5">
                                                            <label for="customerAddress" class="form-label">Customer
                                                                Address</label>
                                                            <div class="input-group mb-3">
                                                                <input id="customer_address" name="customer_address" type="text"
                                                                    class="form-control" placeholder="Enter customer address"
                                                                    readonly>
                                                            </div>
                                                        </div>

                                                        <div class="col-md-2">
                                                            <label for="payment_type" class="form-label">Payment Type</label>
                                                            <div class="input-group mb-3">
                                                                <select id="payment_type" name="payment_type"
                                                                    class="form-select">
                                                                    <?php
                                                                    $PAYMENT_TYPE = new PaymentType(NULL);
                                                                    foreach ($PAYMENT_TYPE->getActivePaymentType() as $payment_type) {
                                                                        ?>
                                                                        <option value="<?php echo $payment_type['id'] ?>">
                                                                            <?php echo $payment_type['name'] ?>
                                                                        </option>
                                                                    <?php } ?>
                                                                </select>
                                                            </div>
                                                        </div>

                                                        <div class="col-md-2">
                                                            <label for="name" class="form-label">Cash T/T Date</label>
                                                            <div class="input-group" id="datepicker2">
                                                                <input type="text" class="form-control date-picker" id="date"
                                                                    name="date"> <span class="input-group-text"><i
                                                                        class="mdi mdi-calendar"></i></span>
                                                            </div>
                                                        </div>


                                                        <div class="col-md-2">
                                                            <label for="debit_account" class="form-label">Debit Account</label>
                                                            <div class="input-group mb-3">
                                                                <select id="debit_account" name="debit_account"
                                                                    class="form-select">
                                                                    <?php
                                                                    $BANK_MASTER = new Bank(NULL);
                                                                    foreach ($BANK_MASTER->all() as $bank_master) {
                                                                        ?>
                                                                        <option value="<?php echo $bank_master['id'] ?>">
                                                                            <?php echo $bank_master['name'] ?>
                                                                        </option>
                                                                    <?php } ?>
                                                                </select>
                                                            </div>
                                                        </div>
                          
                                                        <div class="col-md-2">
                                                            <label for="sales_executive" class="form-label">Sales Executive</label>
                                                            <div class="input-group mb-3">
                                                                <select id="sales_executive" name="sales_executive" class="form-select">

                                                                    <?php
                                                                    $MARKETING_EXECUTIVE = new MarketingExecutive(NULL);
                                                                    foreach ($MARKETING_EXECUTIVE->getActiveExecutives() as $marketing_executive) {
                                                                        ?>
                                                                        <option value="<?php echo $marketing_executive['id'] ?>">
                                                                            <?php echo $marketing_executive['full_name'] ?>
                                                                        </option>
                                                                    <?php } ?>
                                                                </select>
                                                            </div>
                                                        </div>

                                                        <div class="col-md-2">
                                                            <label for="pay_collected_by" class="form-label">Pay Collected By</label>
                                                            <div class="input-group mb-3">
                                                                <select id="pay_collected_by" name="pay_collected_by" class="form-select">

                                                                    <?php
                                                                    $MARKETING_EXECUTIVE = new MarketingExecutive(NULL);
                                                                    foreach ($MARKETING_EXECUTIVE->getActiveExecutives() as $marketing_executive) {
                                                                        ?>
                                                                        <option value="<?php echo $marketing_executive['id'] ?>">
                                                                            <?php echo $marketing_executive['full_name'] ?>
                                                                        </option>
                                                                    <?php } ?>
                                                                </select>
                                                            </div>
                                                        </div>

                                                        <div class="col-md-2">
                                                            <label for="entry_date" class="form-label">Entry Date</label>
                                                            <div class="input-group" id="datepicker2">
                                                                <input type="texentry_datet" class="form-control date-picker" id="entry_date"
                                                                    name="entry_date"> <span class="input-group-text"><i
                                                                        class="mdi mdi-calendar"></i></span>
                                                            </div>
                                                        </div>

                                                        <div class="col-md-2">
                                                            <label for="cash_type" class="form-label">Cash Type</label>
                                                            <div class="input-group mb-3">
                                                                <select id="cash_type" name="cash_type"
                                                                    class="form-select">
                                                                    <?php
                                                                    $PAYMENT_TYPE = new PaymentType(NULL);
                                                                    foreach ($PAYMENT_TYPE->getActivePaymentType() as $payment_type) {
                                                                        ?>
                                                                        <option value="<?php echo $payment_type['id'] ?>">
                                                                            <?php echo $payment_type['name'] ?>
                                                                        </option>
                                                                    <?php } ?>
                                                                </select>
                                                            </div>
                                                        </div>

                                                        <div class="col-md-2">
                                                            <label for="ref_no" class="form-label">T/T Ref No</label>
                                                            <div class="input-group mb-3">
                                                                <input id="ref_no" name="ref_no" type="text"
                                                                placeholder="T/T Ref No" class="form-control">
                                                            </div>
                                                        </div>

                                                        <div class="col-md-2">
                                                            <label for="cost_center" class="form-label">Cost Center</label>
                                                            <div class="input-group mb-3">
                                                                <select id="cost_center" name="cost_center" class="form-select">
                                                                    <option value="">-- Select cost_center --</option>
                                                                    <option value="1">1</option>
                                                                    <option value="2">2</option>
                                                                    <option value="3">3</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        
                                                        <div class="col-md-2">
                                                            <label for="credit_account" class="form-label">Credit Account</label>
                                                            <div class="input-group mb-3">
                                                                <input id="credit_account" name="credit_account" type="text"
                                                                    placeholder="Credit Account" class="form-control" readonly>
                                                                <button class="btn btn-info" type="button"
                                                                    data-bs-toggle="modal" data-bs-target="#creditAccount">
                                                                    <i class="uil uil-search me-1"></i>
                                                                </button>
                                                            </div>
                                                        </div>

                                                        <div class="col-md-4">
                                                            <label for="creditName" class="form-label">#</label>
                                                            <div class="input-group mb-3">
                                                                <input id="creditName" name="creditName" type="text"
                                                                    class="form-control" placeholder=""
                                                                    readonly>
                                                            </div>
                                                        </div>
                                                        
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                                    
                                    <div class="row">
                                        
                                        <div class="col-md-8">
                                            <div class="p-4">
                                                <form id="form-data" autocomplete="off">
                                                    <div class="row">

                                                    <hr class="my-4">

                                                        <h5 class="mb-3">Cheque Details</h5>


                                                        <div class="row align-items-end">
                                                            <div class="col-md-2">
                                                                <label for="cheque_no" class="form-label">Cheque No</label>
                                                                <div class="input-group">
                                                                    <input id="cheque_no" type="text" class="form-control"
                                                                        placeholder="No" readonly>
                                                                    <button class="btn btn-info" type="button"
                                                                        data-bs-toggle="modal" data-bs-target="#chequeModel">
                                                                        <i class="uil uil-search me-1"></i>
                                                                    </button>
                                                                </div>
                                                            </div>

                                                            <div class="col-md-3">
                                                                <label for="cheque_date" class="form-label">Cheque Date</label>
                                                                <div class="input-group" id="datepicker2">
                                                                    <input type="cheque_date" class="form-control date-picker" id="cheque_date"
                                                                        name="cheque_date"> <span class="input-group-text"><i
                                                                            class="mdi mdi-calendar"></i></span>
                                                                </div>
                                                            </div>

                                                            <div class="col-md-3">
                                                                <label for="bank_branch" class="form-label">Bank & Branch</label>
                                                                <div class="input-group">
                                                                    <input id="bank_branch" type="text" class="form-control"
                                                                        placeholder="Bank & Branch" readonly>
                                                                    <button class="btn btn-info" type="button"
                                                                        data-bs-toggle="modal" data-bs-target="#bankModel">
                                                                        <i class="uil uil-search me-1"></i>
                                                                    </button>
                                                                </div>
                                                            </div>

                                                            <div class="col-md-3">
                                                                <label class="form-label">Amount</label>
                                                                <input type="number" id="itemPayment" class="form-control"
                                                                    placeholder="Amount" readonly>
                                                            </div>

                                                            <div class="col-md-1">
                                                                <button type="button" class="btn btn-success w-200"
                                                                    id="addItemBtn">Add</button>
                                                            </div>
                                                        </div>

                                                        <!-- Table -->
                                                        <div class="table-responsive mt-4">
                                                            <table class="table table-bordered" id="chequeTable">
                                                                <thead class="table-light">
                                                                    <tr>
                                                                        <th>Cheque No</th>
                                                                        <th>Cheque Date</th>
                                                                        <th>Bank & Branch</th>
                                                                        <th>Amount</th>
                                                                        <th>Action</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody id="chequeBody">
                                                                    <tr id="noItemRow">
                                                                        <td colspan="5" class="text-center text-muted">No items
                                                                            added</td>
                                                                    </tr>
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                        
                                                    </div>
                                                </form>
                                            </div>
                                        </div>

                                        
                                        <div class="col-md-4">
                                            <div class="p-4">
                                                <form id="form-data-2" autocomplete="off">
                                                    <div class="row">
                                                        
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>



                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="p-4">
                                                <form id="form-data-3" autocomplete="off">
                                                    <div class="row">

                                                        <hr class="my-4">

                                                        <!-- Totals Row -->
                            

                                                            <div class="col-md-2">
                                                                <label for="cheque_total" class="form-label">Cheque Total</label>
                                                                <div class="input-group mb-3">
                                                                    <input id="cheque_total" name="cheque_total" type="text"
                                                                    placeholder="Cheque Total" class="form-control">
                                                                </div>
                                                            </div>

                                                            <div class="col-md-2">
                                                                <label for="cash_total" class="form-label">Cash Total</label>
                                                                <div class="input-group mb-3">
                                                                    <input id="cash_total" name="cash_total" type="text"
                                                                    placeholder="Cash Total" class="form-control">
                                                                </div>
                                                            </div>

                                                            <div class="col-md-4"></div>

                                                                <div class="col-md-2">
                                                                    <button type="button" class="btn btn w-100">View Dealer Signature(s)</button>
                                                                </div>
                                                                
                                                                <div class="col-md-2">
                                                                    <button type="button" class="btn btn-danger w-100">Select Up All</button>
                                                                </div>
                                                            </div>

                                                        <h5 class="mb-3">Invoice Details</h5>

                                                        <!-- Table -->
                                                        <div class="table-responsive mt-4">
                                                            <table class="table table-bordered" id="invoiceTable">
                                                                <thead class="table-light">
                                                                    <tr>
                                                                        <th>Delivery Date</th>
                                                                        <th>Invoice Date</th>
                                                                        <th>Invoice No</th>
                                                                        <th>Invoice Value</th>
                                                                        <th>Paid</th>
                                                                        <th>Overdue</th>
                                                                        <th>Chq Pay</th>
                                                                        <th>Chq Balance</th>
                                                                        <th>Cash Pay</th>
                                                                        <th>Inv Balance</th>
                                                                        <th>Select</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody id="invoiceBody">
                                                                    <tr id="noItemRow">
                                                                        <td colspan="11" class="text-center text-muted">No items
                                                                            added</td>
                                                                    </tr>
                                                                </tbody>
                                                            </table>
                                                        </div>

                                                        <!-- Total Outstanding -->
                                                        <div class="col-md-3"></div>

                                                            <div class="col-md-4">
                                                                <div class="  p-2 border rounded bg-light"
                                                                    style="max-width: 600px;">
                                                                    <div class="row mb-2">
                                                                        <div class="col-7">
                                                                            <input type="text" class="form-control  "
                                                                                value="Sub Total" disabled>
                                                                        </div>
                                                                        <div class="col-5">
                                                                            <input type="text" class="form-control"
                                                                                id="finalTotal" value="0.00" disabled>
                                                                        </div>
                                                                    </div>

                                                                    <div class="row mb-2">
                                                                        <div class="col-7">
                                                                            <input type="text" class="form-control  "
                                                                                value="Discount Total:" disabled>
                                                                        </div>
                                                                        <div class="col-5">
                                                                            <input type="text" class="form-control"
                                                                                id="disTotal" value="0.00" disabled>
                                                                        </div>
                                                                    </div>


                                                                    <div class="row mb-2">
                                                                        <div class="col-7">
                                                                            <input type="text" class="form-control   fw-bold"
                                                                                value="Grand Total:" disabled>
                                                                        </div>
                                                                        <div class="col-5">
                                                                            <input type="text" class="form-control  fw-bold"
                                                                                id="grandTotal" value="0.00" disabled>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        
                                                    </div>
                                                </form>
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