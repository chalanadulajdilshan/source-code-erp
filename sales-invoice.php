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
                                    <i class="uil uil-save me-1"></i> Payment
                                </a>
                                <a href="#" class="btn btn-warning" id="update">
                                    <i class="uil uil-edit me-1"></i> Update
                                </a>
                                <a href="#" class="btn btn-danger delete-category">
                                    <i class="uil uil-trash-alt me-1"></i> Delete
                                </a>

                            </div>

                            <div class="col-md-4 text-md-end text-start mt-3 mt-md-0">
                                <ol class="breadcrumb m-0 justify-content-md-end">
                                    <li class="breadcrumb-item"><a href="javascript: void(0);">Dashboard</a></li>
                                    <li class="breadcrumb-item active">Sales Invoice </li>
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
                                                <h5 class="font-size-16 mb-1">Sales Invoice </h5>
                                                <p class="text-muted text-truncate mb-0">Fill all information below to
                                                    add
                                                    Invoice</p>
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
                                                    <label for="InvoiceCode" class="form-label">Invoice No</label>
                                                    <div class="input-group mb-3">
                                                        <input id="invoice_no" name="invoice_no" type="text"
                                                            class="form-control" value="<?php echo $invoice_id ?>"
                                                            readonly>
                                                        <button class="btn btn-info" type="button"
                                                            data-bs-toggle="modal" data-bs-target="#invoiceModal">
                                                            <i class="uil uil-search me-1"></i> Find
                                                        </button>
                                                    </div>
                                                </div>

                                                <!-- company ID -->
                                                <div class="col-md-3">
                                                    <label for="bankId" class="form-label">Company</label>
                                                    <div class="input-group mb-3">
                                                        <select id="company_id" name="company_id" class="form-select">

                                                            <?php
                                                            $COMPANY = new CompanyProfile(NULL);
                                                            foreach ($COMPANY->getActiveCompany() as $company) {
                                                                ?>
                                                                <option value="<?php echo $company['id'] ?>">
                                                                    <?php echo $company['name'] ?>
                                                                </option>
                                                            <?php } ?>
                                                        </select>
                                                    </div>
                                                </div>




                                                <div class="col-md-2">
                                                    <label for="customerCode" class="form-label">Customer Code</label>
                                                    <div class="input-group mb-3">
                                                        <input id="customer_code" name="customer_code" type="text"
                                                            placeholder="Customer code" class="form-control">
                                                        <button class="btn btn-info" type="button"
                                                            data-bs-toggle="modal" data-bs-target="#customerModal">
                                                            <i class="uil uil-search me-1"></i> Find
                                                        </button>

                                                    </div>
                                                    <div id="customerList" class="list-group position-absolute w-100">
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

                                                <div class="col-md-2">
                                                    <label for="customerAddress" class="form-label">Customer
                                                        Address</label>
                                                    <div class="input-group mb-3">
                                                        <input id="customer_address" name="customer_address" type="text"
                                                            class="form-control" placeholder="Enter customer address"
                                                            readonly>
                                                    </div>
                                                </div>

                                                <div class="col-md-2">
                                                    <label for="mobileNumber" class="form-label">Mobile Number</label>
                                                    <div class="input-group mb-3">
                                                        <input id="customer_mobile" name="customer_mobile" type="text"
                                                            class="form-control" placeholder="Enter Mobile Number"
                                                            readonly>
                                                    </div>
                                                </div>



                                                <div class="col-md-2">
                                                    <label for="vat_type" class="form-label">Vat Type</label>
                                                    <div class="input-group mb-3">
                                                        <select id="vat_type" name="vat_type" class="form-select">
                                                            <?php
                                                            $VAT_TYPE = new VatType(NULL);
                                                            foreach ($VAT_TYPE->getActiveTypes() as $vat_type) {
                                                                ?>
                                                                <option value="<?php echo $vat_type['id']?>"><?php echo $vat_type['vat_type_name']?></option>
                                                            <?php } ?>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-md-2">
                                                    <label for="department" class="form-label">Department</label>
                                                    <div class="input-group mb-3">
                                                        <select id="department_id" name="department_id"
                                                            class="form-select">
                                                            <?php
                                                            $DEPARTMENT_MASTER = new DepartmentMaster(NULL);
                                                            foreach ($DEPARTMENT_MASTER->getActiveDepartment() as $departments) {
                                                                ?>
                                                                <option value="<?php echo $departments['id'] ?>">
                                                                    <?php echo $departments['name'] ?>
                                                                </option>
                                                            <?php } ?>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-md-2">
                                                    <label for="sales_type" class="form-label">Sales Type</label>
                                                    <div class="input-group mb-3">
                                                        <select id="sales_type" name="sales_type" class="form-select">
                                                            <option value="1">Whole Sales</option>
                                                            <option value="2">Retail Sales</option>
                                                        </select>
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
                                                    <label for="sales_type" class="form-label">Sales Type</label>
                                                    <div class="input-group mb-3">
                                                        <select id="sales_type" name="sales_type" class="form-select">
                                                            <option value="1">Whole Sales</option>
                                                            <option value="2">Retail Sales</option>
                                                        </select>
                                                    </div>
                                                </div>

                                                <hr class="my-4">

                                                <ul class="nav nav-pills nav-justified bg-light left-width"
                                                    role="tablist">
                                                    <li class="nav-item waves-effect waves-light">
                                                        <a class="nav-link active" data-bs-toggle="tab"
                                                            href="#navpills2-home" role="tab" aria-selected="true">
                                                            <span class="d-block d-sm-none"><i
                                                                    class="fas fa-home"></i></span>
                                                            <span class="d-none d-sm-block">Product</span>
                                                        </a>
                                                    </li>
                                                    <li class="nav-item waves-effect waves-light">
                                                        <a class="nav-link " data-bs-toggle="tab"
                                                            href="#navpills2-profile" role="tab" aria-selected="false">
                                                            <span class="d-block d-sm-none"><i
                                                                    class="far fa-user"></i></span>
                                                            <span class="d-none d-sm-block">Dag</span>
                                                        </a>
                                                    </li>
                                                </ul>

                                                <div class="tab-content p-3 text-muted">
                                                    <div class="tab-pane active" id="navpills2-home" role="tabpanel">



                                                        <div class="row align-items-end">
                                                            <div class="col-md-2">
                                                                <label for="itemCode" class="form-label">Item
                                                                    Code</label>
                                                                <div class="input-group">
                                                                    <input id="itemCode" type="text"
                                                                        class="form-control" placeholder="Item Code"
                                                                        readonly>
                                                                    <button class="btn btn-info" type="button"
                                                                        id="open-item-modal">
                                                                        <i class="uil uil-search me-1"></i> Find
                                                                    </button>
                                                                </div>
                                                            </div>

                                                            <div class="col-md-2">
                                                                <label class="form-label">Name</label>
                                                                <input type="text" id="itemName" class="form-control"
                                                                    placeholder="Name" readonly>
                                                            </div>
                                                            <div class="col-md-2">
                                                                <label class="form-label">Price</label>
                                                                <input type="number" id="itemPrice" class="form-control"
                                                                    placeholder="Price" oninput="calculatePayment()">
                                                            </div>
                                                            <div class="col-md-1">
                                                                <label class="form-label">Qty</label>
                                                                <input type="number" id="itemQty" class="form-control"
                                                                    placeholder="Qty" oninput="calculatePayment()">
                                                            </div>
                                                            <div class="col-md-2">
                                                                <label class="form-label">Discount (%)</label>
                                                                <input type="number" id="itemDiscount"
                                                                    class="form-control" placeholder="Discount"
                                                                    oninput="calculatePayment()">
                                                            </div>
                                                            <div class="col-md-2">
                                                                <label class="form-label">Payment</label>
                                                                <input type="number" id="itemPayment"
                                                                    class="form-control" placeholder="Payment" readonly>
                                                            </div>
                                                            <div class="col-md-1">
                                                                <button type="button" class="btn btn-success w-100"
                                                                    id="addItemBtn">Add</button>
                                                            </div>
                                                        </div>



                                                        <!-- Table -->
                                                        <div class="table-responsive mt-4">
                                                            <table class="table table-bordered" id="invoiceTable">
                                                                <thead class="table-light">
                                                                    <tr>
                                                                        <th>Code</th>
                                                                        <th>Name</th>
                                                                        <th>Price</th>
                                                                        <th>Qty</th>
                                                                        <th>Discount</th>
                                                                        <th>Payment</th>
                                                                        <th>Total</th>
                                                                        <th>Action</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody id="invoiceItemsBody">
                                                                    <tr id="noItemRow">
                                                                        <td colspan="8" class="text-center text-muted">
                                                                            No items
                                                                            added</td>
                                                                    </tr>
                                                                </tbody>

                                                            </table>

                                                        </div>
                                                    </div>
                                                    <div class="tab-pane " id="navpills2-profile" role="tabpanel">
                                                        <p class="mb-0">
                                                            Food truck fixie locavore, accusamus mcsweeney's marfa nulla
                                                            single-origin coffee squid. Exercitation +1 labore velit,
                                                            blog
                                                            sartorial PBR leggings next level wes anderson artisan four
                                                            loko
                                                            farm-to-table craft beer twee. Qui photo booth letterpress,
                                                            commodo enim craft beer mlkshk aliquip jean shorts ullamco
                                                            ad
                                                            vinyl cillum PBR. Homo nostrud organic, assumenda labore
                                                            aesthetic magna 8-bit.
                                                        </p>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-3">
                                                            <div class="d-flex justify-content-between mb-2">
                                                                <div><strong>Stock Level:</strong></div>
                                                                <div id=""><strong>0.00</strong></div>
                                                            </div>

                                                            <div class="d-flex justify-content-between mb-2">
                                                                <div><strong>Credit Period:</strong></div>
                                                                <div id=""><strong>0.00</strong></div>
                                                            </div>

                                                            <div class="d-flex justify-content-between mb-2">
                                                                <div><strong>Remark:</strong></div>
                                                                <div id=""><strong>0.00</strong></div>
                                                            </div>


                                                        </div>

                                                        <div class="col-md-6"></div>

                                                        <div class="col-md-3">
                                                            <div class="d-flex justify-content-between mb-2">
                                                                <div><strong>Sub Total:</strong></div>
                                                                <div id="subTotal" class="price-highlight">
                                                                    <strong>0.00</strong>
                                                                </div>
                                                            </div>

                                                            <div class="d-flex justify-content-between mb-2">
                                                                <div><strong>Discount Total:</strong></div>
                                                                <div id="disTotal" class="price-highlight">
                                                                    <strong>0.00</strong>
                                                                </div>
                                                            </div>

                                                            <div class="d-flex justify-content-between mb-2">
                                                                <div><strong>Tax Total:</strong></div>
                                                                <div id="tax" class="price-highlight">
                                                                    <strong>0.00</strong>
                                                                </div>
                                                            </div>

                                                            <div class="d-flex justify-content-between border-top pt-2">
                                                                <div><strong>Grand Total:</strong></div>
                                                                <div id="finalTotal" class="price-highlight">
                                                                    <strong>0.00</strong>
                                                                </div>
                                                            </div>
                                                        </div>

                                                    </div>
                                                    <hr>
                                                    <div class="row">
                                                        <div class="  p-2 border rounded bg-light"
                                                            style="max-width: 500px;">
                                                            <div class="row mb-2">
                                                                <div class="col-7">
                                                                    <input type="text"
                                                                        class="form-control text_purchase3"
                                                                        value="Outstanding Invoice Amount" disabled>
                                                                </div>
                                                                <div class="col-5">
                                                                    <input type="text" class="form-control" value="0.00"
                                                                        disabled>
                                                                </div>
                                                            </div>

                                                            <div class="row mb-2">
                                                                <div class="col-7">
                                                                    <input type="text"
                                                                        class="form-control text_purchase3"
                                                                        value="Return Cheque Amount" disabled>
                                                                </div>
                                                                <div class="col-5">
                                                                    <input type="text" class="form-control" value="0.00"
                                                                        disabled>
                                                                </div>
                                                            </div>

                                                            <div class="row mb-2">
                                                                <div class="col-7">
                                                                    <input type="text"
                                                                        class="form-control text_purchase3"
                                                                        value="Pending Cheque Amount" disabled>
                                                                </div>
                                                                <div class="col-5">
                                                                    <input type="text" class="form-control" value="0.00"
                                                                        disabled>
                                                                </div>
                                                            </div>

                                                            <div class="row mb-2">
                                                                <div class="col-7">
                                                                    <input type="text"
                                                                        class="form-control text_purchase3"
                                                                        value="PSD Cheque Settlements" disabled>
                                                                </div>
                                                                <div class="col-5">
                                                                    <input type="text" class="form-control" value="0.00"
                                                                        disabled>
                                                                </div>
                                                            </div>

                                                            <div class="row border-top pt-2">
                                                                <div class="col-7">
                                                                    <input type="text"
                                                                        class="form-control text_purchase3 fw-bold"
                                                                        value="Total" disabled>
                                                                </div>
                                                                <div class="col-5">
                                                                    <input type="text" class="form-control fw-bold"
                                                                        value="0.00" disabled>
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