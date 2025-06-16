<!doctype html>
<?php
include 'class/include.php';
include './auth.php';

//doc id get by session 
$DOCUMENT_TRACKING = new DocumentTracking($doc_id);

// Get the last purchase return if 
$lastId = $DOCUMENT_TRACKING->sales_return_id;
$sales_return_id = $COMPANY_PROFILE_DETAILS->company_code . '/SR/00/0' . $lastId + 1;
?>

<html lang="en">

<head>

    <meta charset="utf-8" />
    <title>Sales Return | Minible - Admin & Dashboard Template</title>
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

                                <?php if ($PERMISSIONS['add_page']): ?>
                                <a href="#" class="btn btn-primary" id="create">
                                    <i class="uil uil-save me-1"></i> Save
                                </a>
                                <?php endif; ?>

                                <?php if ($PERMISSIONS['print_page']): ?>
                                <a href="#" class="btn btn-primary" id="print">
                                    <i class="uil uil-save me-1"></i> Print
                                </a>
                                <?php endif; ?>

                                <?php if ($PERMISSIONS['delete_page']): ?>
                                <a href="#" class="btn btn-danger delete-category">
                                    <i class="uil uil-trash-alt me-1"></i> Delete
                                </a>
                                <?php endif; ?>

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
                                                    <label for="GRN_No" class="form-label">Ref No</label>
                                                    <div class="input-group mb-3">
                                                        <input id="grn_no" name="grn_no" type="text"
                                                            placeholder="GRN No" class="form-control"
                                                            value="<?php echo $sales_return_id ?>" readonly>

                                                        <button class="btn btn-info" type="button"
                                                            data-bs-toggle="modal" data-bs-target="#grn_no">
                                                            <i class="uil uil-search me-1"></i>
                                                        </button>

                                                    </div>
                                                </div>

                                                <div class="col-md-2">
                                                    <label class="form-label" for="date">Date</label>
                                                    <input id="date" name="date" type="text" class="form-control"
                                                        placeholder="Select Sales Return Date">
                                                </div>


                                                <div class="col-md-3">
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

                                                <div class="col-md-3">
                                                    <label for="StoRef" class="form-label">Sto. Ref.</label>
                                                    <div class="input-group mb-3">
                                                        <input id="sto_ref" name="sto_ref" type="text"
                                                            class="form-control" placeholder="Sto. Ref." readonly>
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
                                                    <label for="VatType" class="form-label">Vat Type</label>
                                                    <div class="input-group mb-3">
                                                        <select id="vat_type" name="vat_type" class="form-select">

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

                                                <div class="col-md-4">
                                                    <label for="customerName" class="form-label">Customer Name</label>
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
                                                        <input id="customer_address" name="customer_address" type="text"
                                                            class="form-control" placeholder="Enter customer address"
                                                            readonly>
                                                    </div>
                                                </div>

                                                <div class="col-md-2">
                                                    <label for="InvoiceNo" class="form-label">Invoice No</label>
                                                    <div class="input-group mb-3">
                                                        <input id="invoice_no" name="invoice_no" type="text"
                                                            placeholder="Invoice No" class="form-control" readonly>

                                                        <button class="btn btn-info" type="button"
                                                            data-bs-toggle="modal" data-bs-target="#invoiceNo">
                                                            <i class="uil uil-search me-1"></i>
                                                        </button>

                                                    </div>
                                                </div>

                                                <div class="col-md-2">
                                                    <label class="form-label" for="date">Invoice Date</label>
                                                    <input id="date" name="date" type="text" class="form-control"
                                                        placeholder="Invoice Date" readonly>
                                                </div>

                                                <div class="col-md-3">
                                                    <label for="Brand" class="form-label">Brand</label>
                                                    <div class="input-group mb-3">
                                                        <select id="brand_id" name="brand_id" class="form-select">



                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-md-3">
                                                    <label for="MarketingExecutive" class="form-label">Marketing
                                                        Executive</label>
                                                    <div class="input-group mb-3">
                                                        <select id="marketing_executive" name="marketing_executive"
                                                            class="form-select">



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

                                                <hr class="my-4">


                                                <h5 class="mb-3">Item Details</h5>

                                                <!-- Table -->
                                                <div class="table-responsive  ">
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
                                              
                                                <div class="row">
                                                    <div class="col-md-8">

                                                        <label for="remark" class="form-label"></label>
                                                        <textarea id="remark" name="remark" class="form-control"
                                                            rows="4"
                                                            placeholder="Enter any remarks or notes..."></textarea>
                                                    </div>


                                                    <div class="col-md-4">
                                                        <div class="  p-2 border rounded bg-light"
                                                            style="max-width: 600px;">
                                                            <div class="row mb-2">
                                                                <div class="col-7">
                                                                    <input type="text"
                                                                        class="form-control text_purchase3"
                                                                        value="Sub Total" disabled>
                                                                </div>
                                                                <div class="col-5">
                                                                    <input type="text" class="form-control"
                                                                        id="subTotal" value="0.00" disabled>
                                                                </div>
                                                            </div>

                                                            <div class="row mb-2">
                                                                <div class="col-7">
                                                                    <input type="text"
                                                                        class="form-control text_purchase3"
                                                                        value="Discount Total:" disabled>
                                                                </div>
                                                                <div class="col-5">
                                                                    <input type="text" class="form-control"
                                                                        id="disTotal" value="0.00" disabled>
                                                                </div>
                                                            </div>

                                                            <div class="row mb-2">
                                                                <div class="col-7">
                                                                    <input type="text"
                                                                        class="form-control text_purchase3"
                                                                        value="Tax Total:" disabled>
                                                                </div>
                                                                <div class="col-5">
                                                                    <input type="text" class="form-control" id="tax"
                                                                        value="0.00" disabled>
                                                                </div>
                                                            </div>
                                                            <div class="row mb-2">
                                                                <div class="col-7">
                                                                    <input type="text"
                                                                        class="form-control text_purchase3 fw-bold"
                                                                        value="Grand Total:" disabled>
                                                                </div>
                                                                <div class="col-5">
                                                                    <input type="text" class="form-control  fw-bold"
                                                                        id="finalTotal" value="0.00" disabled>
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

        <!-- include main js  -->
        <?php include 'main-js.php' ?>

    </body>

</html>