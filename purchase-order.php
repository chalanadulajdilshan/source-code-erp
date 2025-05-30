<!doctype html>
<?php
include 'class/include.php';
include './auth.php';


//doc id get by session 
$DOCUMENT_TRACKING = new DocumentTracking($doc_id);

// Get the last inserted quotation
$lastId = $DOCUMENT_TRACKING->po_id;
$po_id = $COMPANY_PROFILE_DETAILS->company_code . '/PO/00/0' . $lastId + 1;

?>

<html lang="en">

<head>

    <meta charset="utf-8" />
    <title>Purchase Order | <?php echo $COMPANY_PROFILE_DETAILS->name ?></title>
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
                                <a href="#" class="btn btn-danger delete-purchase-order">
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
                        <input type="hidden" id="purchase_order_id">

                        

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
                                                        <input id="po_no" name="po_no" type="text" placeholder="PO No"
                                                            class="form-control" value="<?php echo $po_id; ?>" readonly>
                                                        <button class="btn btn-info" type="button"
                                                            data-bs-toggle="modal" data-bs-target="#po_number_modal">
                                                            <i class="uil uil-search me-1"></i>
                                                        </button>
                                                    </div>
                                                </div>

                                                <div class="col-md-2">
                                                    <label for="name" class="form-label">Purchase Date</label>
                                                    <div class="input-group" id="datepicker2">
                                                        <input type="text" class="form-control date-picker"
                                                            id="order_date" name="order_date">
                                                    </div>
                                                </div>

                                                <input type="hidden" id="supplier_id">

                                                <div class="col-md-5">
                                                    <label for="supplier" class="form-label">Supplier</label>
                                                    <div class="input-group mb-3">
                                                        <div class="input-group-prepend" style="flex: 0 0 auto;">
                                                            <input id="supplier_code" name="supplier_code" type="text"
                                                                class="form-control ms-10 me-2" style="width: 200px;"
                                                                placeholder="Supplier Code" readonly>
                                                        </div>
                                                        <input id="supplier_name" name="supplier_name" type="text"
                                                            class="form-control" placeholder="Supplier Name" readonly>

                                                        <button class="btn btn-info" type="button"
                                                            data-bs-toggle="modal" data-bs-target="#supplierModal">
                                                            <i class="uil uil-search me-1"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <label for="supplierAddress" class="form-label">Address</label>
                                                    <div class="input-group mb-3">
                                                        <input id="supplier_address" name="supplier_address" type="text"
                                                            class="form-control" placeholder="Address" readonly>
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
                                                    <label for="LC_TT_No" class="form-label">LC / TT No</label>
                                                    <div class="input-group mb-3">
                                                        <input id="lc_tt_no" name="lc_tt_no" type="text"
                                                            placeholder="Enter LC / TT No" class="form-control">
                                                    </div>

                                                </div>

                                                <div class="col-md-3">
                                                    <label for="brand" class="form-label">Brand</label>
                                                    <div class="input-group mb-3">
                                                        <select id="brand" name="brand" class="form-select">
                                                            <option value="">-- All Brands --</option>
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
                                                            placeholder="Enter BL No" class="form-control">
                                                    </div>

                                                </div>

                                                <div class="col-md-3">
                                                    <label for="Country" class="form-label">Country</label>
                                                    <div class="input-group mb-3">
                                                        <select id="country" name="Country" class="form-select">
                                                            <option value="">-- Select Country --</option>
                                                            <?php
                                                            $COUNTRY = new Country(NULL);
                                                            foreach ($COUNTRY->activeCountry() as $country) {
                                                                echo "<option value='{$country['id']}'>{$country['name']}</option>";
                                                            }
                                                            ?>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-md-2">
                                                    <label for="CI_no" class="form-label">CI No</label>
                                                    <div class="input-group mb-3">
                                                        <input id="ci_no" name="ci_no" type="text"
                                                            placeholder="Enter CI No" class="form-control">
                                                    </div>

                                                </div>

                                                <div class="col-md-2">
                                                    <label for="Department" class="form-label">Department</label>
                                                    <div class="input-group mb-3">
                                                        <select id="department_id" name="department_id"
                                                            class="form-select">
                                                            <?php
                                                            $DEPARTMENT_MASTER = new DepartmentMaster(NUll);
                                                            foreach ($DEPARTMENT_MASTER->getActiveDepartment() as $departments) {
                                                                if ($US->type != 1) {
                                                                    if ($departments['id'] = $US->department_id) {
                                                                        ?>
                                                                        <option value="<?php echo $departments['id'] ?>">
                                                                            <?php echo $departments['name'] ?>
                                                                        </option>
                                                                    <?php }
                                                                } else {
                                                                    ?>
                                                                    <option value="<?php echo $departments['id'] ?>">
                                                                        <?php echo $departments['name'] ?>
                                                                    </option>
                                                                    <?php
                                                                }
                                                            } ?>


                                                        </select>
                                                    </div>
                                                </div>


                                                <div class="col-md-8 ">
                                                    <label for="remark" class="form-label">Remarks</label>
                                                    <input type="text" class="form-control" name="remark" id="remark"
                                                        placeholder="Enter any remarks or notes...">
                                                </div>


                                                <hr class="my-4">


                                                <h5 class="mb-3">Item Details</h5>

                                                <div class="row align-items-end">
                                                    <div class="col-md-2">
                                                        <label for="itemCode" class="form-label">Item Code</label>
                                                        <div class="input-group">
                                                            <input id="itemCode" type="text" class="form-control"
                                                                placeholder="Item Code" readonly>
                                                            <button class="btn btn-info" type="button"
                                                                data-bs-toggle="modal" data-bs-target="#item_master">
                                                                <i class="uil uil-search me-1"></i>
                                                            </button>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-3">
                                                        <label class="form-label">Quantity</label>
                                                        <input type="number" id="qty" class="form-control"
                                                            placeholder="Quantity" oninput="calculatePayment()">
                                                    </div>

                                                    <div class="col-md-3">
                                                        <label class="form-label">Rate</label>
                                                        <input type="number" id="rate" class="form-control"
                                                            placeholder="Rate" oninput="calculatePayment()">
                                                    </div>

                                                    <div class="col-md-3">
                                                        <label class="form-label">Amount</label>
                                                        <input type="number" id="itemPayment" class="form-control"
                                                            placeholder="Amount" readonly>
                                                    </div>

                                                    <div class="col-md-1">
                                                        <button type="button" class="btn btn-success w-100"
                                                            id="addItemBtn">Add</button>
                                                    </div>
                                                </div>

                                            </div>

                                            <!-- Table -->
                                            <div class="table-responsive mt-4">
                                                <table class="table table-bordered" id="invoiceTable">
                                                    <thead class="table-light">
                                                        <tr>
                                                            <th>Code and Name </th>
                                                            <th>Qty</th>
                                                            <th>Rate</th>
                                                            <th>Total Amount</th>
                                                            <th>Action</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="purchaseOrderBody">
                                                        <tr id="noItemRow">
                                                            <td colspan="5" class="text-center text-muted">
                                                                No items
                                                                added</td>
                                                        </tr>
                                                    </tbody>

                                                </table>

                                            </div>


                                            <div class="row">

                                                <div class="col-md-8"></div>
                                                <div class="col-md-4">
                                                    <div class="  p-2 border rounded bg-light"
                                                        style="max-width: 600px;">
                                                        <div class="row mb-2">
                                                            <div class="col-7">
                                                                <input type="text" class="form-control  "
                                                                    value="Sub Total" disabled>
                                                            </div>
                                                            <div class="col-5">
                                                                <input type="text" class="form-control" id="finalTotal"
                                                                    value="0.00" disabled>
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

        <!-- model open here -->
        <div class="modal fade bs-example-modal-xl" id="po_number_modal" tabindex="-1" role="dialog"
            aria-labelledby="myExtraLargeModalLabel" aria-hidden="true">

            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="myExtraLargeModalLabel">Manage Purchase Orders</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-12">
                                <table id="purchase_table" class="table table-bordered dt-responsive nowrap"
                                    style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                    <thead>
                                        <tr>
                                            <th>#id</th>
                                            <th>PO No</th>
                                            <th>Order Date</th>
                                            <th>Supplier Code and Name</th>
                                            <th>PI No</th>
                                            <th>LC/TT No</th>
                                            <th>Department</th>
                                            <th>Grand Total</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        <?php
                                        $PURCHASE_ORDER = new PurchaseOrder(null);
                                        foreach ($PURCHASE_ORDER->all() as $key => $purchase_order) {
                                            $CUSTOMER_MASTER = new CustomerMaster($purchase_order['supplier_id']);
                                            $DEPARTMENT_MASTER = new DepartmentMaster($purchase_order['department']);
                                            $key++;
                                            ?>
                                            <tr class="select-purchase-order" data-id="<?= $purchase_order['id']; ?>"
                                                data-po_number="<?= htmlspecialchars($purchase_order['po_number']); ?>"
                                                data-order_date="<?= htmlspecialchars($purchase_order['order_date']); ?>"
                                                data-supplier_id="<?= htmlspecialchars($purchase_order['supplier_id']); ?>"
                                                data-supplier_code="<?= htmlspecialchars($CUSTOMER_MASTER->code); ?>"
                                                data-supplier_name="<?= htmlspecialchars($CUSTOMER_MASTER->name); ?>"
                                                data-supplier_address="<?= htmlspecialchars($CUSTOMER_MASTER->address); ?>"
                                                data-pi_no="<?= htmlspecialchars($purchase_order['pi_no']); ?>"
                                                data-address="<?= htmlspecialchars($purchase_order['address']); ?>"
                                                data-lc_tt_no="<?= htmlspecialchars($purchase_order['lc_tt_no']); ?>"
                                                data-brand="<?= htmlspecialchars($purchase_order['brand']); ?>"
                                                data-bl_no="<?= htmlspecialchars($purchase_order['bl_no']); ?>"
                                                data-country="<?= htmlspecialchars($purchase_order['country']); ?>"
                                                data-department="<?= htmlspecialchars($purchase_order['department']); ?>"
                                                data-grand_total="<?= htmlspecialchars($purchase_order['grand_total']); ?>"
                                                data-order_by="<?= htmlspecialchars($purchase_order['order_by']); ?>"
                                                data-remarks="<?= htmlspecialchars($purchase_order['remarks']); ?>">
                                                <td><?= $key; ?></td>
                                                <td><?= htmlspecialchars($purchase_order['po_number']); ?></td>
                                                <td><?= htmlspecialchars($purchase_order['order_date']); ?></td>
                                                <td><?= htmlspecialchars($CUSTOMER_MASTER->code . ' - ' . $CUSTOMER_MASTER->name); ?>
                                                </td>
                                                <td><?= htmlspecialchars($purchase_order['pi_no']); ?></td>
                                                <td><?= htmlspecialchars($purchase_order['lc_tt_no']); ?></td>
                                                <td><?= htmlspecialchars($DEPARTMENT_MASTER->name); ?></td>
                                                <td><?= htmlspecialchars($purchase_order['grand_total']); ?></td>
                                            </tr>
                                        <?php } ?>
                                    </tbody>

                                </table>
                            </div> <!-- end col -->
                        </div> <!-- end row -->
                    </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div>

        <!-- Right bar overlay-->
        <div class="rightbar-overlay"></div>

        <!-- JAVASCRIPT -->
        <script src="assets/libs/jquery/jquery.min.js"></script>
        <script src="ajax/js/purchase-order.js"></script>
        <script src="ajax/js/common.js"></script>

        <!-- /////////////////////////// -->

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
        <script src="https://code.jquery.com/ui/1.14.1/jquery-ui.js"></script>
        <script>
            $('#purchase_table').DataTable();
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