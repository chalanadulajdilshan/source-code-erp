<!doctype html>
<?php
include 'class/include.php';
include './auth.php';


//doc id get by session 
$DOCUMENT_TRACKING = new DocumentTracking($doc_id);

// Get the last inserted quotation
$lastId = $DOCUMENT_TRACKING->quotation_id;
$quotation_id = $COMPANY_PROFILE_DETAILS->company_code . '/QUO/00/0' . ($lastId + 1);
?>

<html lang="en">

<head>

    <meta charset="utf-8" />
    <title> Manage Quotation | <?php echo $COMPANY_PROFILE_DETAILS->name ?></title>
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
                            <a href="#" class="btn btn-success" id="new">
                                <i class="uil uil-plus me-1"></i> New
                            </a>

                            <?php if ($PERMISSIONS['add_page']): ?>
                                <a href="#" class="btn btn-primary" id="create">
                                    <i class="uil uil-save me-1"></i> Save
                                </a>
                            <?php endif; ?>

                            <?php if ($PERMISSIONS['edit_page']): ?>
                                <a href="#" class="btn btn-warning" id="update">
                                    <i class="uil uil-edit me-1"></i> Update
                                </a>
                            <?php endif; ?>

                            <?php if ($PERMISSIONS['print_page']): ?>
                                <a href="#" class="btn btn-info" id="print">
                                    <i class="uil uil-print me-1"></i> Print
                                </a>
                            <?php endif; ?>


                            <?php if ($PERMISSIONS['delete_page']): ?>
                                <a href="#" class="btn btn-danger delete-quotation  " style="display:none">
                                    <i class="uil uil-trash-alt me-1"></i> Delete
                                </a>
                            <?php endif; ?>

                        </div>

                        <div class="col-md-4 text-md-end text-start mt-3 mt-md-0">
                            <ol class="breadcrumb m-0 justify-content-md-end">
                                <li class="breadcrumb-item"><a href="javascript: void(0);">Dashboard</a></li>
                                <li class="breadcrumb-item active"> Manage Quotation </li>
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
                                                <div class="avatar-title rounded-circle bg-soft-primary text-primary">
                                                    01
                                                </div>
                                            </div>
                                        </div>
                                        <div class="flex-grow-1 overflow-hidden">
                                            <h5 class="font-size-16 mb-1">Manage Quotation </h5>
                                            <p class="text-muted text-truncate mb-0">Fill all information below to
                                                Manage Quotation </p>
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
                                                <label for="customerCode" class="form-label">Quotation No</label>
                                                <div class="input-group mb-3">
                                                    <input type="text" id="quotation_id" name="quotation_id"
                                                        value="<?php echo $quotation_id; ?>" class="form-control"
                                                        readonly>

                                                    <button class="btn btn-info" type="button" data-bs-toggle="modal"
                                                        data-bs-target="#quotationModel">
                                                        <i class="uil uil-search me-1"></i>
                                                    </button>
                                                </div>
                                            </div>

                                            <div class="col-md-2">
                                                <label for="name" class="form-label">Quotation Date</label>
                                                <div class="input-group" id="datepicker2">

                                                    <input type="text" class="form-control date-picker" id="date"
                                                        name="date"> <span class="input-group-text"><i
                                                            class="mdi mdi-calendar"></i></span>
                                                </div>
                                            </div>



                                            <div class="col-md-2">
                                                <label for="customerCode" class="form-label">Customer Code</label>
                                                <div class="input-group mb-3">
                                                    <input id="customer_code" name="customer_code" type="text"
                                                        placeholder="Customer code" class="form-control" readonly>
                                                    <button class="btn btn-info" type="button" data-bs-toggle="modal"
                                                        data-bs-target="#customerModal">
                                                        <i class="uil uil-search me-1"></i>
                                                    </button>
                                                </div>
                                            </div>

                                            <div class="col-md-3">
                                                <label for="customerName" class="form-label">Customer Name</label>
                                                <div class="input-group mb-3">
                                                    <input id="customer_name" name="customer_name" type="text"
                                                        class="form-control" placeholder="Enter Customer Name" readonly>
                                                </div>
                                            </div>

                                            <div class="col-md-3">
                                                <label for="customerAddress" class="form-label">Customer
                                                    Address</label>
                                                <div class="input-group mb-3">
                                                    <input id="customer_address" name="customer_address" type="text"
                                                        class="form-control" placeholder="Enter customer address"
                                                        readonly>
                                                </div>
                                            </div>

                                            <div class="col-md-3">
                                                <label for="mobileNumber" class="form-label">Mobile Number</label>
                                                <div class="input-group mb-3">
                                                    <input id="customer_mobile" name="customer_mobile" type="text"
                                                        class="form-control" placeholder="Enter Mobile Number" readonly>
                                                </div>
                                            </div>



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

                                            <div class="col-md-3">
                                                <label for="vat_type" class="form-label">Vat Type</label>
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

                                            <div class="col-md-3">
                                                <label for="department_id" class="form-label">Department</label>
                                                <div class="input-group mb-3">
                                                    <select id="department_id" name="department_id" class="form-select">

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
                                                <label for="sales_type" class="form-label">Sales Type</label>
                                                <div class="input-group mb-3">
                                                    <select id="sales_type" name="sales_type" class="form-select">
                                                        <?php
                                                        $SALES_TYPE = new SalesType(NULL);
                                                        foreach ($SALES_TYPE->getSalesTypeByStatus(1) as $sales_type) {
                                                            ?>
                                                            <option value="<?php echo $sales_type['id'] ?>">
                                                                <?php echo $sales_type['name'] ?>
                                                            </option>
                                                        <?php } ?>

                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-md-3">
                                                <label for="payment_type" class="form-label">Payment Type</label>
                                                <div class="input-group mb-3">
                                                    <select id="payment_type" name="payment_type" class="form-select">
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


                                            <div class="col-md-3">
                                                <label for="payment_type" class="form-label">Marketing Executive
                                                </label>
                                                <div class="input-group mb-3">
                                                    <select id="marketing_executive_id" name="marketing_executive_id"
                                                        class="form-select">

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

                                            <div class="col-12">
                                                <label for="remark" class="form-label">Remark Note</label>
                                                <textarea id="remark" name="remark" class="form-control" rows="4"
                                                    placeholder="Enter any remarks or notes about the quotation..."></textarea>
                                            </div>


                                            <!-- hidden values -->
                                            <input type="hidden" id="item_id">
                                            <input type="hidden" id="customer_id">

                                            <input type="hidden" id="availableQty">
                                            <input type="hidden" id="id">
                                            <input type="hidden" value="<?php echo $company_id ?>">
                                            <input type="hidden" name="vat" id="vat"
                                                value="<?php echo $DOCUMENT_TRACKING->vat_percentage ?>">

                                            <hr class="my-4">

                                            <h5 class="mb-3">Add Quotation Items</h5>


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
                                                <div class="col-md-2 cus_width">
                                                    <label class="form-label">Discount (%)</label>
                                                    <input type="number" id="itemDiscount" class="form-control"
                                                        placeholder="Discount" oninput="calculatePayment()">
                                                </div>
                                                <div class="col-md-2 cus_width hidden cus_width_hidden">
                                                    <label class="form-label">Vat Amount</label>
                                                    <input type="text" id="vat_amount" class="form-control"
                                                        placeholder="Vat Amount" readonly>
                                                </div>
                                                <div class="col-md-2 cus_width">
                                                    <label class="form-label">Total Amount</label>
                                                    <input type="text" id="itemPayment" class="form-control"
                                                        placeholder="Total Amount" readonly>
                                                </div>
                                                <div class="col-md-1">
                                                    <button type="button" class="btn btn-success w-100"
                                                        id="addItemBtn">Add</button>
                                                </div>
                                            </div>


                                            <!-- Table -->
                                            <div class="table-responsive mt-4">
                                                <table class="table table-bordered">
                                                    <thead class="table-light">
                                                        <tr>
                                                            <th>Code</th>
                                                            <th>Name</th>
                                                            <th>Price</th>
                                                            <th>Qty</th>
                                                            <th>Discount</th>
                                                            <th>Amount</th>
                                                            <th class="th_vat hidden">Vat Amount</th>
                                                            <th>Total</th>
                                                            <th>Action</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="quotationItemsBody">
                                                        <tr id="noItemRow">
                                                            <td colspan="8" class="text-center text-muted">No items
                                                                added</td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                            <hr>
                                            <div class="row">
                                                <div class="col-md-5">
                                                    <div class="  p-2 border rounded bg-light"
                                                        style="max-width: 500px;">
                                                        <div class="row mb-2">
                                                            <div class="col-5">
                                                                <input type="text" class="form-control "
                                                                    value="Stock Level" disabled>
                                                            </div>
                                                            <div class="col-7">
                                                                <input type="text" class="form-control" value="0.00"
                                                                    id="stock_level" disabled>
                                                            </div>
                                                        </div>

                                                        <div class="row mb-2">
                                                            <div class="col-5">
                                                                <input type="text" class="form-control  "
                                                                    value="Credit Period  " disabled>
                                                            </div>
                                                            <div class="col-7">
                                                                <select class="form-control" id="credit_period"
                                                                    name="credit_period">

                                                                    <?php
                                                                    $CREDIT_PERIOD = new CreditPeriod(NULL);
                                                                    foreach ($CREDIT_PERIOD->getCreditPeriodByStatus(1) as $Credit_period) {
                                                                        ?>
                                                                        <option value="<?php echo $Credit_period['id'] ?>">
                                                                            <?php echo $Credit_period['days'] . ' ' . "Days" ?>
                                                                        </option>
                                                                    <?php } ?>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="row mb-2">
                                                            <div class="col-5">
                                                                <input type="text" class="form-control  "
                                                                    value="Validate Days  " disabled>
                                                            </div>
                                                            <div class="col-7">
                                                                <input type="text" id="validity" name="validity"
                                                                    class="form-control  "
                                                                    placeholder="Enter Validate Days " value="7" />
                                                            </div>
                                                        </div>

                                                    </div>
                                                </div>


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
                                                                <input type="text" class="form-control" id="finalTotal"
                                                                    value="0.00" disabled>
                                                            </div>
                                                        </div>

                                                        <div class="row mb-2">
                                                            <div class="col-7">
                                                                <input type="text" class="form-control  "
                                                                    value="Discount Total:" disabled>
                                                            </div>
                                                            <div class="col-5">
                                                                <input type="text" class="form-control" id="disTotal"
                                                                    value="0.00" disabled>
                                                            </div>
                                                        </div>

                                                        <div class="row mb-2 hidden vat_total">
                                                            <div class="col-7">
                                                                <input type="text" class="form-control  "
                                                                    value="Vat Total:" disabled>
                                                            </div>
                                                            <div class="col-5">
                                                                <input type="text" class="form-control" id="vatTotal"
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


    <!-- model open here -->
    <div class="modal fade bs-example-modal-xl" id="quotationModel" tabindex="-1" role="dialog"
        aria-labelledby="myExtraLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="myExtraLargeModalLabel">Manage Quotation</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    </button>
                </div>
                <div class="modal-body">

                    <div class="row">
                        <div class="col-12">

                            <table id="quotation_table" class="table table-bordered dt-responsive nowrap"
                                style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                <thead>
                                    <tr>
                                        <th>#ID</th>
                                        <th>Quotation No</th>
                                        <th>Date</th>
                                        <th>Customer Name</th>
                                        <th>Company</th>
                                        <th>Department</th>
                                        <th>Final Total</th>

                                    </tr>
                                </thead>

                                <tbody>
                                    <?php
                                    $QUOTATION = new Quotation(null);
                                    foreach ($QUOTATION->all() as $key => $quotation) {
                                        $key++;
                                        $CUSTOMER = new CustomerMaster($quotation['customer_id']);
                                        $COMPANY = new CompanyProfile($quotation['company_id']);
                                        $DEPARTMENT_MASTER = new DepartmentMaster($quotation['department_id']);
                                        ?>
                                        <tr class="select-model" data-id="<?php echo $quotation['id']; ?>"
                                            data-quotation_no="<?php echo htmlspecialchars($quotation['quotation_no']); ?>"
                                            data-date="<?php echo htmlspecialchars($quotation['date']); ?>"
                                            data-customer_name="<?php echo htmlspecialchars($quotation['customer_id']); ?>"
                                            data-company_id="<?php echo htmlspecialchars($quotation['company_id']); ?>"
                                            data-department_id="<?php echo htmlspecialchars($quotation['department_id']); ?>"
                                            data-sub_total="<?php echo htmlspecialchars($quotation['sub_total']); ?>"
                                            data-discount="<?php echo htmlspecialchars($quotation['discount']); ?>"
                                            data-grand_total="<?php echo htmlspecialchars($quotation['grand_total']); ?>">

                                            <td><?php echo $key; ?></td>
                                            <td><?php echo htmlspecialchars($quotation['quotation_no']); ?></td>
                                            <td><?php echo htmlspecialchars($quotation['date']); ?></td>
                                            <td><?php echo htmlspecialchars($CUSTOMER->name); ?></td>
                                            <td><?php echo htmlspecialchars($COMPANY->name); ?></td>
                                            <td><?php echo htmlspecialchars($DEPARTMENT_MASTER->name); ?></td>
                                            <td><?php echo htmlspecialchars($quotation['grand_total']); ?></td>
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
    <!-- model close here -->

    <!-- Right bar overlay-->
    <div class="rightbar-overlay"></div>

    <!-- JAVASCRIPT -->
    <script src="assets/libs/jquery/jquery.min.js"></script>
    <!-- /////////////////////////// -->
    <script src="ajax/js/quotation.js"></script>


    <!-- include main js  -->
    <?php include 'main-js.php' ?>

    <!-- App js -->
    <script src="assets/js/app.js"></script>

    <script>
        $('#quotation_table').DataTable();
        $('#customerTable').DataTable();    
    </script>

</body>

</html>