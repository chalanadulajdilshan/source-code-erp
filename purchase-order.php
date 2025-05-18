<!doctype html>
<?php
include 'class/include.php';

$PURCHASE_ORDER = new SalesInvoice(NULL);

// Get the last inserted package id
$lastId = $PURCHASE_ORDER->getLastID();
$po_number = 'PO-00' . $lastId + 1;

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

    <body data-layout="horizontal" data-topbar="colored" class="someBlock">

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
                                <a href="#" class="btn btn-primary" id="print">
                                    <i class="uil uil-save me-1"></i> Print
                                </a>
                                <a href="#" class="btn btn-danger delete-order">
                                    <i class="uil uil-trash-alt me-1"></i> Delete
                                </a>
                            </div>

                            <div class="col-md-4 text-md-end text-start mt-3 mt-md-0">
                                <ol class="breadcrumb m-0 justify-content-md-end">
                                    <li class="breadcrumb-item"><a href="javascript: void(0);">Dashboard</a></li>
                                    <li class="breadcrumb-item active">Purchase Order </li>
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
                                                <h5 class="font-size-16 mb-1">Purchase Order Details </h5>
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
                                                    <label for="PO_No" class="form-label">PO No</label>
                                                    <div class="input-group mb-3">
                                                        <input id="po_no" name="po_no" type="text"
                                                            placeholder="PO No" class="form-control" value="<?php echo $po_number; ?>" readonly>
                                                        <button class="btn btn-info" type="button"
                                                            data-bs-toggle="modal" data-bs-target="#po_no">
                                                            <i class="uil uil-search me-1"></i> Find
                                                        </button>

                                                    </div>
                                                    <div id="POList" class="list-group position-absolute w-100">
                                                    </div>

                                                </div>

                                                <div class="col-md-2">
                                                    <label class="form-label" for="entry_date">Entry Date</label>
                                                    <input id="entry_date" name="entry_date" type="date"
                                                        class="form-control">
                                                </div>

                                                <div class="col-md-4">
                                                <label for="supplier" class="form-label">Supplier</label>
                                                    <div class="input-group mb-3">
                                                        <input id="supplier_id" name="supplier_id" type="text"
                                                            class="form-control ms-2 me-2" readonly>
                                                        <input id="supplier_name" name="supplier_name" type="text"
                                                            class="form-control" readonly>

                                                        <button class="btn btn-info" type="button"
                                                            data-bs-toggle="modal" data-bs-target="#supplierModal">
                                                            <i class="uil uil-search me-1"></i> Find
                                                        </button>
                                                    </div> 

                                                </div>

                                                <div class="col-md-2">
                                                    <label for="PI_No" class="form-label">PI No</label>
                                                    <div class="input-group mb-3">
                                                        <input id="pi_no" name="pi_no" type="text"
                                                            placeholder="PI No" class="form-control">
                                                    </div>

                                                </div>

                                                <div class="col-md-3">
                                                    <label for="supplierAddress" class="form-label">Address</label>
                                                    <div class="input-group mb-3">
                                                        <input id="supplier_address" name="supplier_address" type="text"
                                                            class="form-control" placeholder="Address">
                                                    </div>
                                                </div>

                                                <div class="col-md-2">
                                                    <label for="LC/TT_No" class="form-label">LC/TT No</label>
                                                    <div class="input-group mb-3">
                                                        <input id="lc/tt_no" name="lc/tt_no" type="text"
                                                            placeholder="LC/TT No" class="form-control">
                                                    </div>

                                                </div>

                                                <div class="col-md-2">
                                                    <label for="brand" class="form-label">Brand</label>
                                                    <div class="input-group mb-3">
                                                        <select id="brand" name="brand" class="form-select">
                                                            <option value="">-- Select Brand --</option>
                                                        <?php
                                                        $BRAND = new Brand(NULL);
                                                        foreach ($BRAND->activeBrands() as $brand) {
                                                            echo "<option value='{$brand['id']}'>{$brand['name']}</option>";
                                                        }
                                                        ?>
                                                        </select>
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
                                                    <label for="Country" class="form-label">Country</label>
                                                    <div class="input-group mb-3">
                                                        <select id="country_id" name="country_id" class="form-select">
                                                        <option value="">-- Select Country --</option>
                                                        
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-md-2">
                                                    <label for="CI_no" class="form-label">CI No</label>
                                                    <div class="input-group mb-3">
                                                        <input id="ci_no" name="ci_no" type="text"
                                                            placeholder="CI No" class="form-control">
                                                    </div>

                                                </div>

                                                <div class="col-md-2">
                                                    <label for="Department" class="form-label">Department</label>
                                                    <div class="input-group mb-3">
                                                        <select id="department_id" name="department_id" class="form-select">
                                                            <option value="">-- Select Department --</option>
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
                                                    <label for="Order_By" class="form-label">Order By</label>
                                                    <div class="input-group mb-3">
                                                        <select id="order_by" name="order_by" class="form-select">

                                                            
                                                        
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-5 mt-3">
                                                    <label for="remark" class="form-label">Remarks</label>
                                                    <textarea id="remark" name="remark" class="form-control" rows="4"
                                                        placeholder="Enter any remarks or notes..."></textarea>
                                                </div>


                                                <hr class="my-4">


                                                <h5 class="mb-3">Item Details</h5>

                                                <div class="row align-items-end">
                                                    <div class="col-md-2">
                                                        <label for="itemCode" class="form-label">Item Code</label>
                                                        <div class="input-group">
                                                            <input id="itemCode" type="text" class="form-control"
                                                                placeholder="Item Code">
                                                                <button class="btn btn-info" type="button"
                                                                id="open-item-modal">
                                                                <i class="uil uil-search me-1"></i> Find
                                                            </button>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-3">
                                                        <label for="Description" class="form-label">Description</label>
                                                        <div class="input-group">
                                                            <input id="description" type="text" class="form-control"
                                                                placeholder="Description">
 
                                                        </div>
                                                    </div>

                                                    <div class="col-md-2">
                                                        <label for="Pattern" class="form-label">Pattern</label>
                                                        <div class="input-group">
                                                            <input id="pattern" type="text" class="form-control"
                                                                placeholder="Pattern">
                                                        </div>
                                                    </div>

                                                    <div class="col-md-1">
                                                        <label class="form-label">Quantity</label>
                                                        <input type="number" id="qty" class="form-control"
                                                            placeholder="Quantity" oninput="calculatePayment()">
                                                    </div>
                                                    
                                                    <div class="col-md-1">
                                                        <label class="form-label">Rate</label>
                                                        <input type="number" id="rate" class="form-control"
                                                            placeholder="Rate" oninput="calculatePayment()">
                                                    </div>

                                                    <div class="col-md-1">
                                                        <button type="button" class="btn btn-success w-100"
                                                            id="addBtn">Add</button>
                                                    </div>
                                                </div>

                                                <div class="col-1 md-3 mt-4">
                                                    <label class="form-label">Total Quantity</label>
                                                    <input type="text" id="itemQty" class="form-control"
                                                        placeholder="Total Quantity" oninput="calculatePayment()">
                                                </div>

                                            </div>
                                            
                                            <!-- Table -->
                                            <div class="table-responsive mt-4">
                                                <table class="table table-bordered" id="invoiceTable">
                                                    <thead class="table-light">
                                                        <tr>
                                                            <th>Code</th>
                                                            <th>Description</th>
                                                            <th>Pattern</th>
                                                            <th>Qty</th>
                                                            <th>Rate</th>
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

        <script src="ajax/js/purchase-order.js"></script>

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