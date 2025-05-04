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
                                <a href="#" class="btn btn-warning" id="search">
                                    <i class="uil uil-search me-1"></i> Search
                                </a>
                                <a href="#" class="btn btn-primary" id="print">
                                    <i class="uil uil-save me-1"></i> Print
                                </a>
                                <a href="#" class="btn btn-danger delete-category">
                                    <i class="uil uil-trash-alt me-1"></i> Delete
                                </a>

                            </div>

                            <div class="col-md-4 text-md-end text-start mt-3 mt-md-0">
                                <ol class="breadcrumb m-0 justify-content-md-end">
                                    <li class="breadcrumb-item"><a href="javascript: void(0);">Dashboard</a></li>
                                    <li class="breadcrumb-item active">ARN </li>
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
                                                <h5 class="font-size-16 mb-1">ARN </h5>
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
                                                    <label for="GRN_No" class="form-label">GRN No</label>
                                                    <div class="input-group mb-3">
                                                        <input id="grn_no" name="grn_no" type="text"
                                                            class="form-control" readonly>
                                                    </div>
                                                </div>

                                                <div class="col-md-2">
                                                    <label for="Entry_Date" class="form-label">Entry Date</label>
                                                    <div class="input-group mb-3">
                                                        <input id="entry_date" name="entry_date" type="text"
                                                            class="form-control" readonly>
                                                    </div>
                                                </div>

                                                <div class="col-md-2">
                                                    <label for="PO_No" class="form-label">PO No</label>
                                                    <div class="input-group">
                                                        <input id="po_no" type="text" class="form-control"
                                                            placeholder="PO No">

                                                        <button class="btn btn-info" type="button"
                                                            id="open-item-modal">
                                                            <i class="uil uil-search me-1"></i> Search
                                                        </button>
                                                    </div>
                                                </div>


                                                <div class="col-md-5">
                                                <label for="supplier" class="form-label">Supplier</label>
                                                    <div class="input-group mb-3">
                                                        <input id="supplier_id" name="supplier_id" type="text"
                                                            class="form-control ms-2 me-2" style="max-width: 150px;" readonly>
                                                        <input id="supplier_name" name="supplier_name" type="text"
                                                            class="form-control" style="flex: 1;" readonly>

                                                        <button class="btn btn-info" type="button"
                                                            data-bs-toggle="modal" data-bs-target="#supplier">
                                                            <i class="uil uil-search me-1"></i> Search
                                                        </button>
                                                    </div> 

                                                </div>
                                                
                                                <div class="col-md-2">
                                                    <label class="form-label" for="entry_date">PO Date</label>
                                                    <input id="po_date" name="po_date" type="date" class="form-control">
                                                </div>

                                                <div class="col-md-2">
                                                    <label for="Department" class="form-label">Department</label>
                                                    <div class="input-group mb-3">
                                                        <select id="department_id" name="department_id" class="form-select">

                                                            

                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-md-2">
                                                    <label for="CI_No" class="form-label">CI No</label>
                                                    <div class="input-group mb-3">
                                                        <input id="ci_no" name="ci_no" type="text"
                                                        placeholder="CI No" class="form-control">
                                                    </div>
                                                </div>

                                                <div class="col-md-2">
                                                    <label for="BL_No" class="form-label">BL No</label>
                                                    <div class="input-group mb-3">
                                                        <input id="bl_no" name="bl_no" type="text"
                                                        placeholder="BL No" class="form-control">
                                                    </div>
                                                </div>

                                                <div class="col-md-2">
                                                    <label for="Brand" class="form-label">Brand</label>
                                                    <div class="input-group mb-3">
                                                        <select id="brand_id" name="brand_id" class="form-select">

                                                            

                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-md-2">
                                                    <label for="GRN_Status" class="form-label">GRN Status</label>
                                                    <div class="input-group mb-3">
                                                        <select id="grn_status" name="grn_status" class="form-select">

                                                            

                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-md-2">
                                                    <label for="LC?TT_No" class="form-label">LC/TT No</label>
                                                    <div class="input-group mb-3">
                                                        <input id="lc/tt_no" name="lc/tt_no" type="text"
                                                        placeholder="LC/TT No" class="form-control">
                                                    </div>
                                                </div>

                                                <div class="col-md-2">
                                                    <label for="PI_No" class="form-label">PI No</label>
                                                    <div class="input-group mb-3">
                                                        <input id="pi_no" name="pi_no" type="text"
                                                        placeholder="PI No" class="form-control">
                                                    </div>
                                                </div>

                                                <div class="col-md-2">
                                                    <label for="Order_By" class="form-label">Order By</label>
                                                    <div class="input-group mb-3">
                                                        <select id="order_by" name="order_by" class="form-select">

                                                            

                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-md-2">
                                                    <label for="Purchase_Type" class="form-label">Purchase Type</label>
                                                    <div class="input-group mb-3">
                                                        <select id="purchase_type" name="purchase_type" class="form-select">

                                                            

                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-md-2">
                                                    <label for="Container_Size" class="form-label">Container Size</label>
                                                    <div class="input-group mb-3">
                                                        <input id="container_size" name="container_size" type="text"
                                                        placeholder="Container Size" class="form-control">
                                                    </div>
                                                </div>

                                                <div class="col-md-2">
                                                    <label class="form-label" for="Invoice_date">Invoice Date</label>
                                                    <input id="invoice_date" name="invoice_date" type="date" class="form-control">
                                                </div>

                                                <div class="col-md-2">
                                                    <label for="Country" class="form-label">Country</label>
                                                    <div class="input-group mb-3">
                                                        <select id="country_id" name="country_id" class="form-select">

                                                            

                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-md-2">
                                                    <label for="VAT" class="form-label">Vat Type</label>
                                                    <div class="input-group mb-3">
                                                        <select id="vat_id" name="vat_id" class="form-select">
                                                            <option value="1">Non Vat</option>
                                                            <option value="2">Vat</option>
                                                            <option value="3">Svat</option>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-10 mt-3">
                                                    <label for="remark" class="form-label">Remarks</label>
                                                    <textarea id="remark" name="remark" class="form-control" rows="4"
                                                        placeholder="Enter any remarks or notes..."></textarea>
                                                </div>

                                                <div class="col-md-2" style="margin-top: 15px;">
                                                    <label class="form-label">Pending Debit Note Amount</label>
                                                    <input type="number" id="Qty" class="form-control"
                                                        placeholder="Quantity" oninput="calculatePayment()">
                                                </div>


                                                <hr class="my-4">


                                                <h5 class="mb-3">Item Details</h5>

                                                <div class="row align-items-end">
                                                    <div class="col-md-1 mb-3">
                                                        <label class="form-label">Item Code</label>
                                                        <input type="text" id="itemCode" class="form-control" readonly>
                                                    </div>

                                                    <div class="col-md-2 mb-3">
                                                        <label for="Description" class="form-label">Description</label>
                                                        <div class="input-group">
                                                            <input id="description" type="text" class="form-control" readonly>

                                                            <button class="btn btn-info" type="button"
                                                                id="open-item-modal">
                                                                <i class="uil uil-search me-1"></i>
                                                            </button>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-1 mb-3">
                                                        <label class="form-label">Order Quantity</label>
                                                        <input type="text" id="order_qty" class="form-control" readonly>
                                                    </div>
                                                    <div class="col-md-1 mb-3">
                                                        <label class="form-label">Rec Quantity</label>
                                                        <input type="number" id="rec_quantity" class="form-control"
                                                             oninput="calculatePayment()">
                                                    </div>
                                                    <div class="col-md-1 mb-3">
                                                        <label class="form-label">Com Cost</label>
                                                        <input type="number" id="com_cost" class="form-control"
                                                             oninput="calculatePayment()">
                                                    </div>
                                                    <div class="col-md-1 mb-3">
                                                        <label class="form-label">Dis 1</label>
                                                        <input type="number" id="dis_1" class="form-control"
                                                             oninput="calculatePayment()">
                                                    </div>
                                                    <div class="col-md-1 mb-3">
                                                        <label class="form-label">Dis 2</label>
                                                        <input type="number" id="dis_2" class="form-control"
                                                             oninput="calculatePayment()">
                                                    </div>
                                                    <div class="col-md-1 mb-3">
                                                        <label class="form-label">Dis 3</label>
                                                        <input type="number" id="dis_3" class="form-control"
                                                            placeholder="Qty" oninput="calculatePayment()">
                                                    </div>
                                                    <div class="col-md-1 mb-3">
                                                        <label class="form-label">Actual Cost</label>
                                                        <input type="number" id="actual_cost" class="form-control"
                                                             oninput="calculatePayment()" readonly>
                                                    </div>
                                                    <div class="col-md-1 mb-3">
                                                        <label class="form-label">Tax</label>
                                                        <input type="number" id="tax" class="form-control"
                                                             oninput="calculatePayment()" readonly>
                                                    </div>
                                                    <div class="col-md-1 mb-3">
                                                        <label class="form-label">List Price</label>
                                                        <input type="number" id="list_price" class="form-control"
                                                             oninput="calculatePayment()">
                                                    </div>
                                                    <div class="col-md-1 mb-3">
                                                        <label class="form-label">Cash Price</label>
                                                        <input type="number" id="cash_price" class="form-control"
                                                             oninput="calculatePayment()">
                                                    </div>
                                                    <div class="col-md-1 mb-3">
                                                        <label class="form-label">Credit Price</label>
                                                        <input type="number" id="credit_price" class="form-control"
                                                             oninput="calculatePayment()">
                                                    </div>
                                                    <div class="col-md-1 mb-3">
                                                        <label class="form-label">Margin (%)</label>
                                                        <input type="number" id="margin" class="form-control"
                                                             oninput="calculatePayment()" readonly>
                                                    </div>
                                                    <div class="col-md-1 mb-3">
                                                        <label class="form-label">Unit Total</label>
                                                        <input type="number" id="unit_total" class="form-control"
                                                             oninput="calculatePayment()" readonly>
                                                    </div>

                                                    <div class="col-md-1 mb-3">
                                                        <button type="button" class="btn btn-success w-100"
                                                            id="addItemBtn">Add</button>
                                                    </div>

                                                </div>

                                                <hr class="my-4">
                                                
                                                <div class="container" style="max-width: 1000px; margin-left: 10px;">
                                                    
                                                    <div class="col-md-3 mb-3">
                                                        <label class="form-label">Sub GRN Value</label>
                                                        <input type="number" id="itemQty" class="form-control"
                                                            oninput="calculatePayment()" readonly>
                                                    </div>
                                                    <div class="col-md-3 mb-3">
                                                        <label class="form-label">Total Discount</label>
                                                        <input type="number" id="itemQty" class="form-control"
                                                            oninput="calculatePayment()" readonly>
                                                    </div>
                                                    <div class="col-md-3 mb-3">
                                                        <label class="form-label">Total VAT Value</label>
                                                        <input type="number" id="itemQty" class="form-control"
                                                            oninput="calculatePayment()" readonly>
                                                    </div>
                                                    <div class="col-md-3 mb-3">
                                                        <label class="form-label">Total GRN Value</label>
                                                        <input type="number" id="itemQty" class="form-control"
                                                            oninput="calculatePayment()" readonly>
                                                    </div>
                                                    <div class="col-md-3 mb-3">
                                                        <label class="form-label">Total Recieved Qty</label>
                                                        <input type="number" id="itemQty" class="form-control"
                                                            oninput="calculatePayment()" readonly>
                                                    </div>
                                                    <div class="col-md-3 mb-3">
                                                        <label class="form-label">Total Order Quantity</label>
                                                        <input type="number" id="itemQty" class="form-control"
                                                            oninput="calculatePayment()" readonly>
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