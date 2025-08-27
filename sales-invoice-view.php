<!doctype html>
<?php
include 'class/include.php';
include './auth.php';

$invoice_id = $_GET['invoice_id'] ?? null;

$SALES_INVOICE = new SalesInvoice($invoice_id);

$COMPANY_PROFILE_DETAILS = new CompanyProfile($SALES_INVOICE->company_id);
$DEPARTMENT_MASTER = new DepartmentMaster($SALES_INVOICE->department_id);
$SALES_INVOICE_ITEMS = new SalesInvoiceItem($invoice_id);
$CUSTOMER = new CustomerMaster($SALES_INVOICE->customer_id);

?>

<html lang="en">

<head>

    <meta charset="utf-8" />
    <title>Sales Invoice | <?php echo $COMPANY_PROFILE_DETAILS->name ?> </title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="<?php echo $COMPANY_PROFILE_DETAILS->name ?>" name="author" />
    <!-- include main CSS -->
    <?php include 'main-css.php' ?>

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
                            <!-- <a href="#" class="btn btn-success" id="new">
                                <i class="uil uil-plus me-1"></i> New
                            </a>

                            <?php if ($PERMISSIONS['add_page']): ?>
                                <a href="#" class="btn btn-primary" id="payment">
                                    <i class="uil uil-save me-1"></i> Payment
                                </a>
                            <?php endif; ?>

                            <?php if ($PERMISSIONS['edit_page']): ?>
                                <a href="#" class="btn btn-warning" id="update">
                                    <i class="uil uil-edit me-1"></i> Update
                                </a>
                            <?php endif; ?>

                            <a href="#" class="btn btn-info" id="print">
                                <i class="uil uil-print me-1"></i> Print
                            </a>

                            <?php if ($PERMISSIONS['delete_page']): ?>
                                <a href="#" class="btn btn-danger cancel-category" style="display: none;">
                                    <i class="uil uil-ban me-1"></i> Cancel
                                </a>
                            <?php endif; ?> -->

                        </div>

                        <div class="col-md-4 text-md-end text-start mt-3 mt-md-0">
                            <ol class="breadcrumb m-0 justify-content-md-end">
                                <li class="breadcrumb-item"><a href="javascript: void(0);">Dashboard</a></li>
                                <li class="breadcrumb-item active">Sales Invoice </li>
                            </ol>
                        </div>
                    </div>
                    <!--- Hidden Values -->
                    <input type="hidden" id="item_id" name="item_id">
                    <input type="hidden" id="availableQty">
                    <input type="hidden" id="customer_id">

                    <!-- end page title -->

                    <div class="row">
                        <div class="col-lg-12">
                            <div class="card">
                                <div class="p-4">
                                    <div class="d-flex align-items-center">
                                        <div class="flex-shrink-0 me-3">
                                            <div class="avatar-xs">
                                                <div class="avatar-title rounded-circle bg-soft-primary text-primary">
                                                    01
                                                </div>
                                            </div>
                                        </div>
                                        <div class="flex-grow-1 overflow-hidden">
                                            <h5 class="font-size-16 mb-1">Sales Invoice </h5>
                                            <p class="text-muted text-truncate mb-0">Fill all information below to add
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
                                                <div class=" ">
                                                    <label class="form-label fw-bold">Payment Type:</label><br />
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="radio" name="payment_type"
                                                            id="cash" value="1" checked>
                                                        <label class="form-check-label" for="cash">Cash</label>
                                                    </div>
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="radio" name="payment_type"
                                                            id="credit" value="2">
                                                        <label class="form-check-label" for="credit">Credit</label>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-2">
                                                <label for="InvoiceCode" class="form-label">Invoice No</label>
                                                <div class="input-group mb-3">
                                                    <input id="invoice_no" name="invoice_no" type="text"
                                                        class="form-control" value="<?php echo $SALES_INVOICE->invoice_no ?>" readonly>
                                                    <!-- <button class="btn btn-info" type="button" data-bs-toggle="modal"
                                                        data-bs-target="#invoiceModal">
                                                        <i class="uil uil-search me-1"></i>
                                                    </button> -->
                                                </div>
                                            </div>
                                            <!-- INVOICE ID HIDDEN -->
                                            <input type="hidden" id="invoice_id" name="invoice_id" />
                                            <!-- company ID -->
                                            <div class="col-md-3">
                                                <label for="bankId" class="form-label">Company</label>
                                                <div class="input-group mb-3">
                                                    <select id="company_id" name="company_id" class="form-select">

                                                        <option value="<?php echo $COMPANY_PROFILE_DETAILS->id ?>">
                                                            <?php echo $COMPANY_PROFILE_DETAILS->name ?>
                                                        </option>
                                                    </select>
                                                </div>
                                            </div>



                                            <div class="col-md-2">
                                                <label for="customerCode" class="form-label">Customer Code</label>
                                                <div class="input-group mb-3">
                                                    <input id="customer_code" name="customer_code" value="<?php echo $CUSTOMER->code ?>" type="text"
                                                        class="form-control" readonly>
                                                </div>
                                            </div>



                                            <div class="col-md-3">
                                                <label for="customerName" class="form-label">Customer Name <span class="text-danger">*</span></label>
                                                <div class="input-group mb-3">
                                                    <input id="customer_name" name="customer_name" value="<?php echo $CUSTOMER->name ?>" type="text"
                                                        class="form-control" placeholder="Enter Customer Name">
                                                </div>
                                            </div>

                                            <div class="col-md-2">
                                                <label for="customerAddress" class="form-label">Customer
                                                    Address</label>
                                                <div class="input-group mb-3">
                                                    <input id="customer_address" name="customer_address" value="<?php echo $CUSTOMER->address ?>" type="text"
                                                        class="form-control" placeholder="Enter customer address">
                                                </div>
                                            </div>

                                            <div class="col-md-2">
                                                <label for="mobileNumber" class="form-label">Mobile Number</label>
                                                <div class="input-group mb-3">
                                                    <input id="customer_mobile" name="customer_mobile" value="<?php echo $CUSTOMER->mobile_number ?>" type="text"
                                                        class="form-control" placeholder="Enter Mobile Number">
                                                </div>
                                            </div>



                                            <div class="col-md-2">
                                                <label for="vat_type" class="form-label">Vat Type</label>
                                                <div class="input-group mb-3">
                                                    <select id="vat_type" name="vat_type" class="form-select">
                                                        <option value="1">VAT</option>
                                                        <option value="2">Non-VAT</option>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-md-3">
                                                <label for="department" class="form-label">Department</label>
                                                <div class="input-group mb-3">
                                                    <select id="department_id" name="department_id" class="form-select">
                                                        <option value="<?php echo $DEPARTMENT_MASTER->id ?>">
                                                            <?php echo $DEPARTMENT_MASTER->name ?>
                                                        </option>
                                                    </select>
                                                </div>
                                            </div>


                                            <div class="col-md-3">
                                                <label for="customerCode" class="form-label">Dag Ref No</label>
                                                <div class="input-group mb-3">
                                                    <input id="ref_no" name="ref_no" type="text" class="form-control"
                                                        placeholder="Select Dag Ref No" readonly>
                                                    <button class="btn btn-info" type="button" data-bs-toggle="modal"
                                                        data-bs-target="#dagModel">
                                                        <i class="uil uil-search me-1"></i>
                                                    </button>
                                                </div>

                                                <input type="hidden" id="dag_id" name="dag_id" />
                                            </div>

                                            <div class="col-md-3 hidden">
                                                <label for="quotationCode" class="form-label">Quotation ref No</label>
                                                <div class="input-group mb-3">
                                                    <input id="quotation_ref_no" name="quotation_ref_no" type="text"
                                                        class="form-control" placeholder="Select Quotation ref No" readonly>

                                                </div>
                                                <input type="hidden" id="quotation_id" name="quotation_id" />
                                            </div>


                                            <hr class="my-4">
                                            <div class="row align-items-end" id="addItemTable">
                                                <div class="col-md-2">
                                                    <label for="itemCode" class="form-label">Item
                                                        Code</label>
                                                    <div class="input-group">
                                                        <input id="itemCode" type="text" class="form-control"
                                                            placeholder="Item Code" readonly>
                                                        <button class="btn btn-info" type="button"
                                                            data-bs-toggle="modal" data-bs-target="#item_master">
                                                            <i class="uil uil-search me-1"></i>
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
                                                    <input type="number" id="itemDiscount" class="form-control"
                                                        placeholder="Discount" oninput="calculatePayment()">
                                                </div>
                                                <div class="col-md-2">
                                                    <label class="form-label">Payment</label>
                                                    <input type="number" id="itemPayment" class="form-control"
                                                        placeholder="Payment" readonly>
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
                                                            <th>Total</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="invoiceItemsBody">
                                                        <?php foreach ($SALES_INVOICE_ITEMS->getItemsByInvoiceId($invoice_id) as $item) { ?>
                                                            <tr>
                                                                <td><?php echo $item['item_code'] ?></td>
                                                                <td><?php echo $item['item_name'] ?></td>
                                                                <td><?php echo $item['price'] ?></td>
                                                                <td><?php echo $item['quantity'] ?></td>
                                                                <td><?php echo $item['discount'] ?></td>
                                                                <td><?php echo $item['total'] ?></td>

                                                            </tr>
                                                        <?php } ?>
                                                    </tbody>

                                                </table>

                                            </div>


                                            <hr>
                                            <div class="row">
                                                <div class="col-md-5">
                                                    <div class="p-2 border rounded bg-light" style="max-width: 500px;">

                                                        <div class="row mb-2 align-items-center">
                                                            <div class="col-5">
                                                                <label class="form-label">Credit Period</label>
                                                            </div>
                                                            <div class="col-7">
                                                                <select class="form-control select2">
                                                                    <option>-- Select Credit Period --</option>
                                                                </select>
                                                            </div>
                                                        </div>

                                                        <div class="row mb-2 align-items-center">
                                                            <div class="col-5">
                                                                <label class="form-label">Invoice Remarks</label>
                                                            </div>
                                                            <div class="col-7">
                                                                <select class="form-control">
                                                                    <option>-- Select Remark --</option>
                                                                    <?php if (!empty($SALES_INVOICE->remark)) { ?>
                                                                        <option value="1"><?php echo $SALES_INVOICE->remark; ?></option>
                                                                    <?php } ?>
                                                                </select>
                                                            </div>
                                                        </div>

                                                    </div>

                                                </div>


                                                <div class="col-md-3"></div>

                                                <div class="col-md-4 mb-4">
                                                    <div class="  p-2 border rounded bg-light"
                                                        style="max-width: 600px;">
                                                        <div class="row mb-2">
                                                            <div class="col-7">
                                                                <input type="text" class="form-control text_purchase3"
                                                                    value="Sub Total" disabled>
                                                            </div>
                                                            <div class="col-5">
                                                                <input type="text" class="form-control" id="subTotal"
                                                                    value="<?php echo $SALES_INVOICE->sub_total ?>" disabled>
                                                            </div>
                                                        </div>

                                                        <div class="row mb-2">
                                                            <div class="col-7">
                                                                <input type="text" class="form-control text_purchase3"
                                                                    value="Discount Total:" disabled>
                                                            </div>
                                                            <div class="col-5">
                                                                <input type="text" class="form-control" id="disTotal"
                                                                    value="<?php echo $SALES_INVOICE->discount ?>" disabled>
                                                            </div>
                                                        </div>

                                                        <div class="row mb-2">
                                                            <div class="col-7">
                                                                <input type="text" class="form-control text_purchase3"
                                                                    value="Tax Total:" disabled>
                                                            </div>
                                                            <div class="col-5">
                                                                <input type="text" class="form-control" id="tax"
                                                                    value="<?php echo $SALES_INVOICE->tax ?>" disabled>
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
                                                                    id="finalTotal" value="<?php echo $SALES_INVOICE->grand_total ?>" disabled>
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

    <!-- Right bar overlay-->
    <div class="rightbar-overlay"></div>

    <!-- JAVASCRIPT -->
    <script src="assets/libs/jquery/jquery.min.js"></script>
    <!-- /////////////////////////// -->
    <!-- include main js  -->
    <?php include 'main-js.php' ?>

</body>

</html>