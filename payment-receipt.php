<!doctype html>
<?php
include 'class/include.php';
include './auth.php';

//doc id get by session 
$DOCUMENT_TRACKING = new DocumentTracking($doc_id);

// Get the last inserted quotation
$lastId = $DOCUMENT_TRACKING->payment_receipt_id;
$payment_receipt_id = $COMPANY_PROFILE_DETAILS->company_code . '/PR/00/0' . ($lastId + 1);

?>

<html lang="en">

<head>

    <meta charset="utf-8" />
    <title> Manage Payment Receipt | <?php echo $COMPANY_PROFILE_DETAILS->name ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="<?php echo $COMPANY_PROFILE_DETAILS->name ?>" name="author" />
    <!-- include main CSS -->
    <?php include 'main-css.php' ?>

    <style>
        .btn-danger {
            color: #fff;
            background-color: #f46a6a !important;
            border-color: #f46a6a;
            padding: 6px !important;
            margin: 4px !important;
        }
    </style>

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

                            <?php if ($PERMISSIONS['delete_page']): ?>
                                <a href="#" class="btn btn-danger delete-recipt  " style="display:none">
                                    <i class="uil uil-trash-alt me-1"></i> Delete
                                </a>
                            <?php endif; ?>

                        </div>

                        <div class="col-md-4 text-md-end text-start mt-3 mt-md-0">
                            <ol class="breadcrumb m-0 justify-content-md-end">
                                <li class="breadcrumb-item"><a href="javascript: void(0);">Dashboard</a></li>
                                <li class="breadcrumb-item active"> Manage Payment Receipt </li>
                            </ol>
                        </div>
                    </div>
                    <!--- Hidden Values -->


                    <!-- end page title -->
                    <section>
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
                                                <h5 class="font-size-16 mb-1">Manage Payment Receipt </h5>
                                                <p class="text-muted text-truncate mb-0">Fill all information below to
                                                    Manage Payment Receipt </p>
                                            </div>
                                            <div class="flex-shrink-0">
                                                <i class="mdi mdi-chevron-up accor-down-icon font-size-24"></i>
                                            </div>
                                        </div>

                                    </div>

                                    <div class="p-4">
                                        <form id="form-data" autocomplete="off">
                                            <div class="row">
                                                <!-- hidden customer id -->
                                                <input type="hidden" id="customer_id">
                                                <div class="col-md-2">
                                                    <label for="reciptNo" class="form-label">Recipt No</label>
                                                    <div class="input-group mb-3">
                                                        <input type="text" id="code" name="code"
                                                            value="<?php echo $payment_receipt_id ?>"
                                                            class="form-control" readonly>

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

                                                <div class="col-md-3">
                                                    <label for="customerAddress" class="form-label">Customer
                                                        Address</label>
                                                    <div class="input-group mb-3">
                                                        <input id="customer_address" name="customer_address" type="text"
                                                            class="form-control" placeholder="Enter customer address"
                                                            readonly>
                                                    </div>
                                                </div>

                                                <div class="col-md-2">
                                                    <label for="name" class="form-label">Cash Amount</label>
                                                    <div class="input-group" id="datepicker2">
                                                        <input type="number" class="form-control" id="cash_total"
                                                            placeholder="Enter Cash Amount" name="cash_total" min="0"
                                                            step="0.01">
                                                    </div>
                                                </div>

                                                <div class="col-md-3">
                                                    <label for="name" class="form-label">Cash T/T Date</label>
                                                    <div class="input-group" id="datepicker2">
                                                        <input type="text" class="form-control date-picker" id="date"
                                                            name="date"> <span class="input-group-text"><i
                                                                class="mdi mdi-calendar"></i></span>
                                                    </div>
                                                </div>

                                                <div class="col-md-3">
                                                    <label for="entry_date" class="form-label">Entry Date</label>
                                                    <div class="input-group" id="datepicker2">
                                                        <input type="texentry_datet" class="form-control date-picker"
                                                            id="entry_date" name="entry_date"> <span
                                                            class="input-group-text"><i
                                                                class="mdi mdi-calendar"></i></span>
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
                                                    <label for="credit_account" class="form-label">Debit
                                                        Account</label>
                                                    <div class="input-group mb-3">
                                                        <input id="credit_account" name="credit_account" type="text"
                                                            placeholder="Credit Account" class="form-control" readonly>
                                                        <button class="btn btn-info" type="button"
                                                            data-bs-toggle="modal" data-bs-target="#creditAccount">
                                                            <i class="uil uil-search me-1"></i>
                                                        </button>
                                                    </div>
                                                </div>

                                                <div class="col-md-2">
                                                    <label for="ref_no" class="form-label">Debit Acc Name</label>
                                                    <div class="input-group mb-3">
                                                        <input id="ref_no" name="ref_no" type="text"
                                                            placeholder="Debit Acc Name" class="form-control" readonly>
                                                    </div>
                                                </div>

                                                <div class="col-md-2">
                                                    <label for="credit_account" class="form-label">Credit
                                                        Account</label>
                                                    <div class="input-group mb-3">
                                                        <input id="credit_account" name="credit_account" type="text"
                                                            placeholder="Credit Account" class="form-control" readonly>
                                                        <button class="btn btn-info" type="button"
                                                            data-bs-toggle="modal" data-bs-target="#creditAccount">
                                                            <i class="uil uil-search me-1"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <label for="ref_no" class="form-label">Credit Acc Name</label>
                                                    <div class="input-group mb-3">
                                                        <input id="ref_no" name="ref_no" type="text"
                                                            placeholder="Credit Acc Name " class="form-control"
                                                            readonly>
                                                    </div>
                                                </div>

                                                <div class="col-md-7">
                                                    <label for="creditName" class="form-label">#Remark</label>
                                                    <div class="input-group mb-3">
                                                        <input id="creditName" name="creditName" type="text"
                                                            class="form-control" placeholder="">
                                                    </div>
                                                </div>

                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>

                    <section>
                        <div class="row d-flex align-items-stretch">
                            <div class="col-md-7 d-flex">
                                <div class="card w-100 h-100">
                                    <div class="p-4">
                                        <form id="form-data" autocomplete="off">
                                            <div class="row">
                                                <div class="row align-items-center mb-3">
                                                    <div class="col-md-6">
                                                        <h5 class="mb-0">Add Cheque Details</h5>
                                                    </div>
                                                    <div class="col-md-6 text-end">
                                                        <div class="d-inline-flex align-items-center">
                                                            <label for="cheque_total"
                                                                class="form-label me-2 mb-0 text-danger"
                                                                style="white-space: nowrap;">Cheque Total:</label>
                                                            <input id="cheque_total" name="cheque_total" type="text"
                                                                placeholder="Cheque Total Amount" class="form-control"
                                                                readonly>
                                                        </div>
                                                    </div>
                                                </div>
                                                <hr>
                                                <div class="row align-items-end">
                                                    <div class="col-md-2">
                                                        <label for="cheque_no" class="form-label">Cheque
                                                            No</label>
                                                        <div class="input-group">
                                                            <input id="cheque_no" type="text" class="form-control"
                                                                placeholder="No">

                                                        </div>
                                                    </div>

                                                    <div class="col-md-2">
                                                        <label for="cheque_date" class="form-label">Cheque
                                                            Date</label>
                                                        <div class="input-group" id="datepicker2">
                                                            <input type="text" class="form-control date-picker"
                                                                id="cheque_date" name="cheque_date"
                                                                placeholder="Cheque Date">

                                                        </div>
                                                    </div>

                                                    <div class="col-md-4">
                                                        <label for="bank_branch" class="form-label">Bank &
                                                            Branch</label>
                                                        <div class="input-group">
                                                            <input type="hidden" id="bank_branch">
                                                            <input id="bank_branch_name" type="text"
                                                                class="form-control" placeholder="Bank & Branch"
                                                                readonly>
                                                            <button class="btn btn-info" type="button"
                                                                data-bs-toggle="modal" data-bs-target="#branch_master">
                                                                <i class="uil uil-search me-1"></i>
                                                            </button>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-3">
                                                        <label class="form-label">Amount</label>
                                                        <input type="number" id="amount" class="form-control"
                                                            placeholder="Amount">
                                                    </div>

                                                    <div class="col-md-1">
                                                        <button type="button" class="btn btn-success  "
                                                            id="add_cheque">Add</button>
                                                    </div>
                                                </div>

                                                <!-- Table -->
                                                <div class="table-responsive mt-4">
                                                    <table class="table table-bordered" id="chequeBody">
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
                                                                <td colspan="5" class="text-center text-muted">No
                                                                    items added</td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>

                            <!-- Right Column (Form 2) -->
                            <div class="col-md-5 d-flex">
                                <div class="card w-100 h-100">
                                    <div class="p-4">
                                        <h5 class="mb-3">Payment Utilization Summary</h5>
                                        <hr>

                                        <div class="table-responsive mt-4"
                                            style="max-height: 250px; overflow-y: auto; ">

                                            <table class="table table-bordered">
                                                <thead class="table-light">
                                                    <tr>
                                                        <th>Inv No</th>
                                                        <th>Inv Date</th>
                                                        <th>Pay Type</th>
                                                        <th>Pay Date</th>
                                                        <th>Amount</th>
                                                        <th>Days</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="utilizationBody">
                                                    <tr class="no-data">
                                                        <td colspan="6" class="text-center text-muted">No payment data
                                                            available</td>
                                                    </tr>
                                                </tbody>

                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </section>



                    <section>
                        <div class="row mt-4">
                            <div class="  col-md-12">
                                <div class="card p-4">
                                    <form id="form-data-3" autocomplete="off">

                                        <div class="row align-items-center mb-3">
                                            <div class="col-md-2">
                                                <h5 class="mb-0">Invoice Summary Details</h5>
                                            </div>
                                            <div class="col-md-3 text-end">
                                                <div class="d-inline-flex align-items-center">
                                                    <label for="cheque_total" class="form-label me-2 mb-0 text-danger"
                                                        style="white-space: nowrap;">Total Outstanding Amount:</label>
                                                    <input id="outstanding" name="outstanding" type="text"
                                                        placeholder="  Outstanding Amount " class="form-control"
                                                        readonly>
                                                </div>
                                            </div>
                                            <div class="col-md-3 text-end">
                                                <div class="d-inline-flex align-items-center">
                                                    <label for="cheque_total" class="form-label me-2 mb-0 text-danger"
                                                        style="white-space: nowrap;">Cheque Available Balance :</label>
                                                    <input id="cheque_balance" name="cheque_balance" type="text"
                                                        placeholder="Cheque Balance " class="form-control" readonly>
                                                </div>
                                            </div>
                                            <div class="col-md-3 text-end">
                                                <div class="d-inline-flex align-items-center">
                                                    <label for="cheque_total" class="form-label me-2 mb-0 text-danger"
                                                        style="white-space: nowrap;">Cash Available Balance:</label>
                                                    <input id="cash_balance" name="cash_balance" type="text"
                                                        placeholder="Cash Balance " class="form-control" readonly>
                                                </div>
                                            </div>
                                            <div class="col-md-1 text-end">
                                                <button type="button" class="btn btn-warning" id="utilize">
                                                    <i class="uil uil-sync me-1"></i> Utilize
                                                </button>
                                            </div>

                                        </div>
                                        <!-- Table -->
                                        <div class="table-responsive mt-4">
                                            <table class="table table-bordered" id="invoiceTable">
                                                <thead class="table-light">
                                                    <tr>

                                                        <th>Invoice Date</th>
                                                        <th>Invoice No</th>
                                                        <th>Invoice Value</th>
                                                        <th>Paid</th>
                                                        <th>Overdue</th>
                                                        <th>Chq Pay</th>
                                                        <th>Cash Pay</th>
                                                        <th>Inv Balance</th>
                                                        <th>Action </th>
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
                                        <div class="row mt-4">
                                            <!-- Total Outstanding -->
                                            <div class="col-md-8"></div>

                                            <div class="col-md-4">
                                                <div class="  p-2 border rounded bg-light" style="max-width: 600px;">
                                                    <div class="row mb-2">
                                                        <div class="col-7">
                                                            <input type="text" class="form-control  " value="Sub Total"
                                                                disabled>
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
                    </section>
                </div>
            </div>

            <!-- //////////////// Model Detials //////////////////// -->
            <!-- model open here -->
            <div class="modal fade " id="branch_master" tabindex="-1" role="dialog"
                aria-labelledby="myExtraLargeModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-xl">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="myExtraLargeModalLabel">Manage Bank Branches</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-12">


                                    <table id="datatable" class="table table-bordered dt-responsive nowrap"
                                        style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                        <thead>
                                            <tr>
                                                <th>#id</th>
                                                <th>Bank</th>
                                                <th>Branch</th>
                                                <th>Address</th>
                                                <th>Phone Number</th>
                                                <th>City</th>
                                                <th>Status</th>

                                            </tr>
                                        </thead>


                                        <tbody>
                                            <?php
                                            $BRANCH = new Branch(null);
                                            foreach ($BRANCH->getByStatus(1) as $key => $branch) {
                                                $key++;
                                                $BANK = new Bank($branch['bank_id']);
                                            ?>
                                                <tr class="select-branch" data-id="<?php echo $branch['id']; ?>"
                                                    data-bankid="<?php echo $branch['bank_id']; ?>"
                                                    data-code="<?php echo htmlspecialchars($branch['code']); ?>"
                                                    data-name="<?php echo htmlspecialchars($branch['name']); ?>"
                                                    data-address="<?php echo htmlspecialchars($branch['address']); ?>"
                                                    data-phone="<?php echo htmlspecialchars($branch['phone_number']); ?>"
                                                    data-city="<?php echo htmlspecialchars($branch['city']); ?>"
                                                    data-active="<?php echo $branch['active_status']; ?>">

                                                    <td><?php echo $key; ?></td>
                                                    <td><?php echo htmlspecialchars($BANK->code . ' - ' . $BANK->name); ?>
                                                    </td>
                                                    <td><?php echo htmlspecialchars($branch['code'] . ' - ' . $branch['name']); ?>
                                                    </td>
                                                    <td><?php echo htmlspecialchars($branch['address']); ?></td>
                                                    <td><?php echo htmlspecialchars($branch['phone_number']); ?></td>
                                                    <td><?php echo htmlspecialchars($branch['city']); ?></td>
                                                    <td>
                                                        <?php if ($branch['active_status'] == 1): ?>
                                                            <span class="badge bg-soft-success font-size-12">Active</span>
                                                        <?php else: ?>
                                                            <span class="badge bg-soft-danger font-size-12">Inactive</span>
                                                        <?php endif; ?>
                                                    </td>
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
            <?php include 'footer.php' ?>

            <!-- Right bar overlay-->
            <div class="rightbar-overlay"></div>

            <!-- JAVASCRIPT -->
            <script src="assets/libs/jquery/jquery.min.js"></script>
            <!-- /////////////////////////// -->
            <script src="ajax/js/payment-receipt.js"></script>


            <!-- include main js  -->
            <?php include 'main-js.php' ?>

            <!-- App js -->
            <script src="assets/js/app.js"></script>
            <script src="https://code.jquery.com/ui/1.14.1/jquery-ui.js"></script>
            <script>
                $('#quotation_table').DataTable();
                $(function() {
                    // Initialize the datepicker
                    $(".date-picker").datepicker({
                        dateFormat: 'yy-mm-dd',
                        minDate: 0
                    });


                });
            </script>

</body>

</html>