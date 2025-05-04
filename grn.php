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
                                <a href="#" class="btn btn-danger delete-category">
                                    <i class="uil uil-trash-alt me-1"></i> Delete
                                </a>

                            </div>

                            <div class="col-md-4 text-md-end text-start mt-3 mt-md-0">
                                <ol class="breadcrumb m-0 justify-content-md-end">
                                    <li class="breadcrumb-item"><a href="javascript: void(0);">Dashboard</a></li>
                                    <li class="breadcrumb-item active">GRN </li>
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
                                                <h5 class="font-size-16 mb-1">Enter Sales Return Details </h5>
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
                                                            placeholder="GRN No" class="form-control">

                                                        <button class="btn btn-info" type="button"
                                                            data-bs-toggle="modal" data-bs-target="#grn_no">
                                                            View
                                                        </button>

                                                    </div>
                                                </div>

                                                <div class="col-md-2">
                                                    <label class="form-label" for="date">Date</label>
                                                    <input id="date" name="date" type="date"
                                                        class="form-control">
                                                </div>


                                                <div class="col-md-2">
                                                    <label for="Department" class="form-label">Department</label>
                                                    <div class="input-group mb-3">
                                                        <select id="department_id" name="department_id" class="form-select">

                                                            

                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-md-2">
                                                    <label for="StoRef" class="form-label">Sto. Ref.</label>
                                                    <div class="input-group mb-3">
                                                        <input id="sto_ref" name="sto_ref" type="text"
                                                            class="form-control" placeholder="Sto. Ref." readonly>
                                                    </div>
                                                </div>

                                                <div class="col-md-2">
                                                    <label for="payment_type" class="form-label">Payment Type</label>
                                                    <div class="input-group mb-3">
                                                        <select id="payment_type" name="payment_type" class="form-select">
                                                            
                                                        

                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-md-2">
                                                    <label for="VatType" class="form-label">Vat Type</label>
                                                    <div class="input-group mb-3">
                                                        <select id="vat_type" name="vat_type" class="form-select">
                                                            <option value="1">VAT Invoice</option>
                                                            <option value="2">Non VAT Invoice</option>
                                                            <option value="3">SVAT Invoice</option>
                                                            <option value="4">EVAT Invoice</option>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-md-2">
                                                    <label for="Customer" class="form-label">Customer</label>
                                                    <div class="input-group mb-3">
                                                        <input id="customer_id" name="customer_id" type="text"
                                                            placeholder="Customer" class="form-control">

                                                        <button class="btn btn-info" type="button"
                                                            data-bs-toggle="modal" data-bs-target="#customer">
                                                            <i class="uil uil-search me-1"></i> Search
                                                        </button>

                                                    </div>
                                                </div>

                                                <div class="col-md-2">
                                                    <label for="InvoiceNo" class="form-label">Invoice No</label>
                                                    <div class="input-group mb-3">
                                                        <input id="invoice_no" name="invoice_no" type="text"
                                                            placeholder="Invoice No" class="form-control">

                                                        <button class="btn btn-info" type="button"
                                                            data-bs-toggle="modal" data-bs-target="#invoiceNo">
                                                            <i class="uil uil-search me-1"></i> Search
                                                        </button>

                                                    </div>
                                                </div>

                                                <div class="col-md-3">
                                                    <label for="Address" class="form-label">Address</label>
                                                    <div class="input-group mb-3">
                                                        <input id="address" name="address" type="text"
                                                            class="form-control" placeholder="Address">
                                                    </div>
                                                </div>

                                                <div class="col-md-2">
                                                    <label class="form-label" for="date">Invoice Date</label>
                                                    <input id="date" name="date" type="date"
                                                        class="form-control">
                                                </div>

                                                <div class="col-md-3">
                                                    <label for="Brand" class="form-label">Brand</label>
                                                    <div class="input-group mb-3">
                                                        <select id="brand_id" name="brand_id" class="form-select">

                                                            

                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-md-3">
                                                    <label for="MarketingExecutive" class="form-label">Marketing Executive</label>
                                                    <div class="input-group mb-3">
                                                        <select id="marketing_executive" name="marketing_executive" class="form-select">

                                                            

                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-md-2">
                                                    <label for="CostCenter" class="form-label">Cost Center</label>
                                                    <div class="input-group mb-3">
                                                        <select id="cost_center" name="cost_center" class="form-select">

                                                            

                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-md-2">
                                                    <label for="Serial_No" class="form-label">Serial No (MC)</label>
                                                    <div class="input-group mb-3">
                                                        <input id="serial_no" name="serial_no" type="text"
                                                            class="form-control" placeholder="Serial No (MC)">
                                                    </div>
                                                </div>

                                                <div class="col-md-1 d-flex justify-content-center align-items-center">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" id="is_active"
                                                        name="is_active">
                                                    <label class="form-check-label" for="is_active">
                                                        Invoice No
                                                    </label>
                                                </div>
                                            </div>


                                                <hr class="my-4">


                                                <h5 class="mb-3">Item Details</h5>

                                                <!-- Table -->
                                                <div class="table-responsive mt-4">
                                                    <table class="table table-bordered" id="invoiceTable">
                                                        <thead class="table-light">
                                                            <tr>
                                                                <th>Code</th>
                                                                <th>Description</th>
                                                                <th>Rate</th>
                                                                <th>Qty</th>
                                                                <th>Pre. Req. Qty</th>
                                                                <th>Req. Qty</th>
                                                                <th>Discount</th>
                                                                <th>Sub Total</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody id="invoiceItemsBody">
                                                            <tr id="noItemRow">
                                                                <td colspan="8" class="text-center text-muted">No items
                                                                    added</td>
                                                            </tr>
                                                        </tbody>
                                                    </table>

                                                </div>

                                                <div class="col-6 mt-3">
                                                    <label for="remark" class="form-label"></label>
                                                    <textarea id="remark" name="remark" class="form-control" rows="4"
                                                        placeholder="Enter any remarks or notes..."></textarea>
                                                </div>

                                                <div class="container" style="max-width: 400px;">
                                                    
                                                    <div class="col-md-6">
                                                        <label for="SubTotal" class="form-label">Sub Total</label>
                                                        <div class="input-group mb-3">
                                                            <input id="sub_total" name="sub_total" type="text"
                                                            placeholder="Sub Total" class="form-control" readonly>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <label for="Discount" class="form-label">Discount</label>
                                                        <div class="input-group mb-3">
                                                            <input id="discount" name="discount" type="text"
                                                            placeholder="Discount" class="form-control" readonly>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <label for="Tax" class="form-label">Tax</label>
                                                        <div class="input-group mb-3">
                                                            <input id="tax" name="tax" type="text"
                                                            placeholder="Tax" class="form-control" readonly>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <label for="InvoiceTotal" class="form-label">Invoice Total</label>
                                                        <div class="input-group mb-3">
                                                            <input id="invoice_total" name="invoice_total" type="text"
                                                            placeholder="Invoice Total" class="form-control" readonly>
                                                        </div>
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