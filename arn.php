<!doctype html>
<?php
include 'class/include.php';
include './auth.php';

$DOCUMENT_TRACKING = new DocumentTracking($doc_id);

// Get the last inserted package id
$lastId = $DOCUMENT_TRACKING->arn_id;
$arn_id = $COMPANY_PROFILE_DETAILS->company_code . '/ARN/00/' . $lastId + 1;
?>

<html lang="en">

<head>

    <meta charset="utf-8" />
    <title>ARN | <?php echo $COMPANY_PROFILE_DETAILS->name ?> </title>
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
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.14.1/themes/base/jquery-ui.css">

    <style>
        .col-lg-1 {
            width: 5.9% !important;
        }
    </style>


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
                                <a href="#" class="btn btn-primary" id="create_arn">
                                    <i class="uil uil-save me-1"></i> Save
                                </a>
                                <a href="#" class="btn btn-warning" id="search">
                                    <i class="uil uil-search me-1"></i> Search
                                </a>
                                <a href="#" class="btn btn-info" id="print">
                                    <i class="uil uil-plus me-1"></i> Print
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
                                                    <label for="arn_no" class="form-label">ARN No</label>
                                                    <div class="input-group mb-3">
                                                        <input id="arn_no" name="arn_no" type="text"
                                                            class="form-control" value="<?php echo $arn_id ?>" readonly>
                                                    </div>
                                                </div>

                                                <div class="col-md-2">
                                                    <label for="Entry_Date" class="form-label">Entry Date</label>
                                                    <div class="input-group mb-3">
                                                        <input id="entry_date" name="entry_date" type="text"
                                                            class="form-control" value="<?php echo date('Y-m-d') ?> "
                                                            readonly>
                                                    </div>
                                                </div>

                                                <div class="col-md-2">
                                                    <label for="PO_No" class="form-label">PO No</label>
                                                    <div class="input-group">
                                                        <input id="po_no" type="text" class="form-control"
                                                            placeholder="Select Po No">

                                                        <button class="btn btn-info" type="button" id="open-item-modal">
                                                            <i class="uil uil-search me-1"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                                <div class="col-md-2">
                                                    <label class="form-label" for="entry_date">PO Date</label>
                                                    <input id="po_date" name="po_date" type="text"
                                                        class="form-control date-picker" placeholder="Select Po Date">
                                                </div>

                                                <div class="col-md-4">
                                                    <label for="supplier" class="form-label">Supplier</label>
                                                    <div class="input-group mb-3">
                                                        <input id="supplier_code" name="supplier_code" type="text"
                                                            class="form-control ms-2 me-2" style="max-width: 200px;"
                                                            readonly placeholder="Select Supplier Code">
                                                        <input id="supplier_name" name="supplier_name" type="text"
                                                            class="form-control" placeholder="Select Suplier Name"
                                                            readonly>

                                                        <button class="btn btn-info" type="button"
                                                            data-bs-toggle="modal" data-bs-target="#supplierModal">
                                                            <i class="uil uil-search me-1"></i>
                                                        </button>
                                                    </div>

                                                </div>



                                                <div class="col-md-2">
                                                    <label for="Department" class="form-label">Department</label>
                                                    <div class="input-group mb-3">
                                                        <select id="department_id" name="department_id"
                                                            class="form-select">
                                                            <?php
                                                            $DEPARTMENT_MASTER = new DepartmentMaster(NULL);
                                                            foreach ($DEPARTMENT_MASTER->getActiveDepartment() as $department_master) {
                                                                ?>
                                                                <option value="<?php echo $department_master['id'] ?> ">
                                                                    <?php echo $department_master['name'] ?>
                                                                </option>
                                                            <?php } ?>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-md-2">
                                                    <label for="CI_No" class="form-label">CI No</label>
                                                    <div class="input-group mb-3">
                                                        <input id="ci_no" name="ci_no" type="text"
                                                            placeholder="Enter CI No" class="form-control">
                                                    </div>
                                                </div>

                                                <div class="col-md-2">
                                                    <label for="BL_No" class="form-label">BL No</label>
                                                    <div class="input-group mb-3">
                                                        <input id="bl_no" name="bl_no" type="text"
                                                            placeholder="Enter BL No" class="form-control">
                                                    </div>
                                                </div>

                                                <div class="col-md-2">
                                                    <label for="Brand" class="form-label">Brand</label>
                                                    <div class="input-group mb-3">
                                                        <select id="brand_id" name="brand_id" class="form-select">
                                                            <?php
                                                            $BRAND = new Brand(NULL);
                                                            foreach ($BRAND->all() as $brand) {
                                                                ?>
                                                                <option value="<?php echo $brand['id'] ?> ">
                                                                    <?php echo $brand['name'] ?>
                                                                </option>
                                                            <?php } ?>


                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-md-2">
                                                    <label for="arn_status" class="form-label">ARN Status</label>
                                                    <div class="input-group mb-3">
                                                        <select id="arn_status" name="arn_status" class="form-select">
                                                            <option value="">Select Status</option>
                                                            <option value="Pending">Pending</option>
                                                            <option value="Approved">Approved</option>
                                                            <option value="Received">Received</option>
                                                            <option value="Rejected">Rejected</option>


                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-md-2">
                                                    <label for="LC?TT_No" class="form-label">LC/TT No</label>
                                                    <div class="input-group mb-3">
                                                        <input id="lc/tt_no" name="lc/tt_no" type="text"
                                                            placeholder="Enter LC/TT No" class="form-control">
                                                    </div>
                                                </div>

                                                <div class="col-md-2">
                                                    <label for="PI_No" class="form-label">PI No</label>
                                                    <div class="input-group mb-3">
                                                        <input id="pi_no" name="pi_no" type="text"
                                                            placeholder="Enter PI No" class="form-control">
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
                                                        <select id="purchase_type" name="purchase_type"
                                                            class="form-select">
                                                            <?php
                                                            $PURCHASE_TYPE = new PurchaseType(NULL);
                                                            foreach ($PURCHASE_TYPE->all() as $purchase_type) {
                                                                ?>
                                                                <option value="<?php echo $purchase_type['id'] ?>">
                                                                    <?php echo $purchase_type['title'] ?>
                                                                </option>
                                                            <?php } ?>


                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-md-2">
                                                    <label for="Container_Size" class="form-label">Container
                                                        Size</label>
                                                    <div class="input-group mb-3">
                                                        <input id="container_size" name="container_size" type="text"
                                                            placeholder="Container Size" class="form-control">
                                                    </div>
                                                </div>

                                                <div class="col-md-2">
                                                    <label class="form-label" for="Invoice_date">Invoice Date</label>
                                                    <input id="invoice_date" name="invoice_date" type="text"
                                                        class="form-control date-picker"
                                                        placeholder="Select Invoice Date">
                                                </div>

                                                <div class="col-md-2">
                                                    <label for="Country" class="form-label">Country</label>
                                                    <div class="input-group mb-3">
                                                        <select id="country_id" name="country_id" class="form-select">

                                                            <?php
                                                            $COUNTRY = new Country(NULL);
                                                            foreach ($COUNTRY->all() as $country) {
                                                                ?>
                                                                <option value="<?php echo $country['id'] ?>">
                                                                    <?php echo $country['name'] ?>
                                                                </option>
                                                            <?php } ?>

                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-md-2">
                                                    <label for="VAT" class="form-label">Vat Type</label>
                                                    <div class="input-group mb-3">
                                                        <select id="vat_id" name="vat_id" class="form-select">
                                                            <?php
                                                            $VAT_TYPE = new VatType(NULL);
                                                            foreach ($VAT_TYPE->all() as $vat_type) {
                                                                ?>
                                                                <option value="<?php echo $vat_type['id'] ?>">
                                                                    <?php echo $vat_type['name'] ?>
                                                                </option>
                                                            <?php } ?>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-2">
                                                    <label class="form-label">Pending Debit Note Amount</label>
                                                    <input type="number" id="Qty" class="form-control"
                                                        placeholder="Quantity">
                                                </div>
                                                <div class="col-12  ">
                                                    <label for="remark" class="form-label">Remarks</label>
                                                    <textarea id="remark" name="remark" class="form-control" rows="4"
                                                        placeholder="Enter any remarks or notes..."></textarea>
                                                </div>


                                                <hr class="my-4">


                                                <h5>Item Details</h5>

                                                <div class="row gx-2 gy-2">
                                                    <!-- ────────── First Line of Fields ────────── -->


                                                    <div class="  col-sm-2 col-md-2 col-lg-2">
                                                        <label for="Description" class="form-label">Item Code</label>
                                                        <div class="input-group input-group-sm">
                                                            <input id="itemCode" name="itemCode" type="text"
                                                                class="form-control" readonly>
                                                            <button class="btn btn-info" type="button"
                                                                data-bs-toggle="modal" data-bs-target="#item_master">
                                                                <i class="uil uil-search me-1"></i>
                                                            </button>
                                                        </div>
                                                    </div>

                                                    <div class="col-6 col-sm-4 col-md-2 col-lg-1">
                                                        <label class="form-label">Order Qty</label>
                                                        <input type="text" id="order_qty"
                                                            class="form-control form-control-sm" readonly>
                                                    </div>

                                                    <div class="col-6 col-sm-4 col-md-2 col-lg-1">
                                                        <label class="form-label">Rec Qty</label>
                                                        <input type="number" id="rec_quantity"
                                                            class="form-control form-control-sm"
                                                            oninput="calculatePayment()">
                                                    </div>

                                                    <div class="col-6 col-sm-4 col-md-2 col-lg-1">
                                                        <label class="form-label">Com Cost</label>
                                                        <input type="text" id="cost" name="cost"
                                                            class="form-control form-control-sm"
                                                            oninput="calculatePayment()">
                                                    </div>

                                                    <div class="col-6 col-sm-4 col-md-2 col-lg-1">
                                                        <label class="form-label">Dis 1</label>
                                                        <input type="number" id="dis_1"
                                                            class="form-control form-control-sm"
                                                            oninput="calculatePayment()">
                                                    </div>

                                                    <div class="col-6 col-sm-4 col-md-2 col-lg-1">
                                                        <label class="form-label">Dis 2</label>
                                                        <input type="number" id="dis_2"
                                                            class="form-control form-control-sm"
                                                            oninput="calculatePayment()">
                                                    </div>

                                                    <!-- ────────── Second Line of Fields ────────── -->
                                                    <div class="col-6 col-sm-4 col-md-2 col-lg-1">
                                                        <label class="form-label">Dis 3</label>
                                                        <input type="number" id="dis_3"
                                                            class="form-control form-control-sm"
                                                            oninput="calculatePayment()">
                                                    </div>

                                                    <div class="col-6 col-sm-4 col-md-2 col-lg-1">
                                                        <label class="form-label">Actual Cost</label>
                                                        <input type="text" id="actual_cost"
                                                            class="form-control form-control-sm"
                                                            oninput="calculatePayment()" readonly>
                                                    </div>

                                                    <div class="col-6 col-sm-4 col-md-2 col-lg-1">
                                                        <label class="form-label">Tax</label>
                                                        <input type="text" id="tax" class="form-control form-control-sm"
                                                            oninput="calculatePayment()" readonly>
                                                    </div>

                                                    <div class="col-6 col-sm-4 col-md-2 col-lg-1">
                                                        <label class="form-label">List Price</label>
                                                        <input type="text" id="list_price"
                                                            class="form-control form-control-sm"
                                                            oninput="calculatePayment()">
                                                    </div>

                                                    <div class="col-6 col-sm-4 col-md-2 col-lg-1">
                                                        <label class="form-label">Cash Price</label>
                                                        <input type="text" id="cash_price"
                                                            class="form-control form-control-sm"
                                                            oninput="calculatePayment()">
                                                    </div>

                                                    <div class="col-6 col-sm-4 col-md-2 col-lg-1">
                                                        <label class="form-label">Credit Price</label>
                                                        <input type="text" id="credit_price"
                                                            class="form-control form-control-sm"
                                                            oninput="calculatePayment()">
                                                    </div>

                                                    <div class="col-6 col-sm-4 col-md-2 col-lg-1">
                                                        <label class="form-label">Margin (%)</label>
                                                        <input type="number" id="margin"
                                                            class="form-control form-control-sm"
                                                            oninput="calculatePayment()" readonly>
                                                    </div>

                                                    <div class="col-6 col-sm-4 col-md-2 col-lg-1">
                                                        <label class="form-label">Unit Total</label>
                                                        <input type="text" id="unit_total"
                                                            class="form-control form-control-sm"
                                                            oninput="calculatePayment()" readonly>
                                                    </div>

                                                    <div class="col-6 col-sm-4 col-md-2 col-lg-1 align-self-end">
                                                        <button type="button" class="btn btn-success btn-sm w-100"
                                                            id="addItemBtn">
                                                            Add
                                                        </button>
                                                    </div>
                                                </div>
                                                <table id="itemTable" class="table table-bordered table-sm mt-5">
                                                    <thead class="table-light">
                                                        <tr>
                                                            <th>Item Code</th>
                                                            <th>Order Qty</th>
                                                            <th>Rec Qty</th>
                                                            <th>Com Cost</th>
                                                            <th>Discounts (1/2/3)</th>
                                                            <th>Actual Cost</th>
                                                            <th>Unit Total</th>
                                                            <th>List Price</th>
                                                            <th>Cash Price</th>
                                                            <th>Credit Price</th>
                                                            <th>Action</th>
                                                        </tr>
                                                    </thead>

                                                    <tbody id="itemTableBody">
                                                        <tr id="noDataRow">
                                                            <td colspan="8" class="text-center">No data available</td>
                                                        </tr>
                                                    </tbody>
                                                </table>


                                                <hr class="my-4">
                                                <div class="row justify-content-end">
                                                    <div class="p-2 border rounded bg-light" style="max-width: 500px;">


                                                        <div class="row mb-2">
                                                            <div class="col-7">
                                                                <input type="text" class="form-control text_purchase3"
                                                                    value="Total Discount" disabled>
                                                            </div>
                                                            <div class="col-5">
                                                                <input type="text" id="total_discount"
                                                                    class="form-control text-end" value="0.00" disabled>
                                                            </div>
                                                        </div>

                                                        <div class="row mb-2">
                                                            <div class="col-7">
                                                                <input type="text" class="form-control text_purchase3"
                                                                    value="Total VAT Value" disabled>
                                                            </div>
                                                            <div class="col-5">
                                                                <input type="text" id="total_vat"
                                                                    class="form-control text-end" value="0.00" disabled>
                                                            </div>
                                                        </div>

                                                        <div class="row mb-2">
                                                            <div class="col-7">
                                                                <input type="text" class="form-control text_purchase3"
                                                                    value="Total Received Qty" disabled>
                                                            </div>
                                                            <div class="col-5">
                                                                <input type="text" id="total_received_qty"
                                                                    class="form-control text-end" value="0.00" disabled>
                                                            </div>
                                                        </div>

                                                        <div class="row border-top pt-2">
                                                            <div class="col-7">
                                                                <input type="text" class="form-control text_purchase3 "
                                                                    value="Total Order Quantity" disabled>
                                                            </div>
                                                            <div class="col-5">
                                                                <input type="text" id="total_order_qty"
                                                                    class="form-control  text-end" value="0.00"
                                                                    disabled>
                                                            </div>
                                                        </div>
                                                        <div class="row border-top pt-2">

                                                            <div class="col-7">
                                                                <input type="text"
                                                                    class="form-control text_purchase3 fw-bold"
                                                                    value="Total ARN Value" disabled>
                                                            </div>
                                                            <div class="col-5">
                                                                <input type="text" class="form-control text-end fw-bold"
                                                                    id="total_arn" value="0.00" disabled>
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
       <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <!-- add js files -->
        <script src="ajax/js/arn-master.js"></script>
        <script src="ajax/js/common.js"></script>



        <script src="assets/libs/sweetalert/sweetalert-dev.js"></script>
        <script src="assets/js/jquery.preloader.min.js"></script>

        <script src="assets/libs/bootstrap/js/bootstrap.bundle.min.js"></script>

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
        <script src="https://code.jquery.com/ui/1.14.1/jquery-ui.js"></script>
        <script>
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

        <!-- App js -->
        <script src="assets/js/app.js"></script>

    </body>

</html>