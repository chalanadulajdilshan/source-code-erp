<!doctype html>
<?php
include 'class/include.php';

?>

<html lang="en">

<head>

    <meta charset="utf-8" />
    <title> Manage Utilization | <?php echo $COMPANY_PROFILE_DETAILS->name ?></title>
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

                                <?php if ($PERMISSIONS['edit_page']): ?>
                                <a href="#" class="btn btn-warning" id="update">
                                    <i class="uil uil-edit me-1"></i> Update
                                </a>
                                <?php endif; ?>

                                <?php if ($PERMISSIONS['delete_page']): ?>
                                <a href="#" class="btn btn-danger delete-utilization  " style="display:none">
                                    <i class="uil uil-trash-alt me-1"></i> Delete
                                </a>
                                <?php endif; ?>

                            </div>

                            <div class="col-md-4 text-md-end text-start mt-3 mt-md-0">
                                <ol class="breadcrumb m-0 justify-content-md-end">
                                    <li class="breadcrumb-item"><a href="javascript: void(0);">Dashboard</a></li>
                                    <li class="breadcrumb-item active"> Manage Utilization </li>
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
                                                    <div
                                                        class="avatar-title rounded-circle bg-soft-primary text-primary">
                                                        01
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="flex-grow-1 overflow-hidden">
                                                <h5 class="font-size-16 mb-1">Manage Utilization </h5>
                                                <p class="text-muted text-truncate mb-0">Fill all information below to
                                                    Manage Sales Summary </p>
                                            </div>
                                            <div class="flex-shrink-0">
                                                <i class="mdi mdi-chevron-up accor-down-icon font-size-24"></i>
                                            </div>
                                        </div>

                                        <div class="p-4">
                                            <form id="form-data" autocomplete="off">
                                                <div class="row">

                                                    <div class="col-md-2">
                                                        <label class="form-label" for="code">Ref No </label>
                                                        <div class="input-group mb-3">
                                                            <input id="code" name="code" type="text" value=""
                                                                placeholder="Ref No" class="form-control" readonly>
                                                            <button class="btn btn-info" type="button"
                                                                data-bs-toggle="modal"
                                                                data-bs-target="#vehicleModel">
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

                                                    <div class="col-md-2">
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

                                                    <div class="col-md-3">
                                                        <label for="name" class="form-label">ARN/GRN/DGRN/CRN/PAY</label>
                                                        <div class="input-group mb-3">
                                                            <input id="type_no" name="type_no" type="text"
                                                            placeholder="ARN/GRN/DGRN/CRN/PAY" class="form-control">
                                                        </div>
                                                    </div>

                                                    <div class="col-md-2">
                                                        <label for="pending_balance" class="form-label">Pending Balance</label>
                                                        <div class="input-group mb-3">
                                                            <input id="pending_balance" name="pending_balance" type="text"
                                                            placeholder="Pending Balance" class="form-control">
                                                        </div>
                                                    </div>

                                                    <div class="col-md-2">
                                                        <label for="remainong_balance" class="form-label">Remainong Balance</label>
                                                        <div class="input-group mb-3">
                                                            <input id="remainong_balance" name="remainong_balance" type="text"
                                                            placeholder="Pending Balance" class="form-control">
                                                        </div>
                                                    </div>

                                                    <div class="col-md-2">
                                                        <label for="date" class="form-label">Date</label>
                                                        <div class="input-group" id="datepicker2">
                                                            <input type="texentry_datet" class="form-control date-picker" id="date"
                                                                name="date"> <span class="input-group-text"><i
                                                                    class="mdi mdi-calendar"></i></span>
                                                        </div>
                                                    </div>

                                                    <div class=" ">
                                                        <div class="form-check form-check-inline">
                                                            <input class="form-check-input" type="checkbox"
                                                                name="type[]" id="settle_invoice" value="settle_invoice">
                                                            <label class="form-check-label" for="settle_invoice">Settle Invoice</label>
                                                        </div>

                                                        <div class="form-check form-check-inline">
                                                            <input class="form-check-input" type="checkbox"
                                                                name="type[]" id="cash_pay" value="cash_pay">
                                                            <label class="form-check-label" for="cash_pay">Cash Pay</label>
                                                        </div>

                                                        <div class="form-check form-check-inline">
                                                            <input class="form-check-input" type="checkbox"
                                                                name="type[]" id="return_cheque" value="return_cheque">
                                                            <label class="form-check-label" for="return_cheque">Return Cheque</label>
                                                        </div>
                                                    </div>


                                                    <hr class="my-4">
                                                    <div class="row align-items-end">
                                                        
                                                        <div class="col-md-3"></div>

                                                            <div class="col-md-2">
                                                                <button type="button" class="btn btn-danger w-100">Invoice</button>
                                                            </div>
                                                            
                                                            <div class="col-md-2">
                                                                <button type="button" class="btn btn-danger w-100">Settlement</button>
                                                            </div>

                                                            <div class="col-md-2">
                                                                <label for="cash_total" class="form-label">Cash Total</label>
                                                                <div class="input-group mb-3">
                                                                    <input id="cash_total" name="cash_total" type="text"
                                                                    placeholder="Cash Total" class="form-control">
                                                                </div>
                                                            </div>

                                                        </div>
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
                                                                                disabled>
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
                                                                                <option value="0"> -- Select Credit Period --
                                                                                </option>
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
                                                                                placeholder="Enter Validate Days " />
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
                                                </div>
                                            </form>
                                        </div>
                                    </div> 
                                </div> 
                            </div> 
                        </div> 
                    </div>
                </div>
            </div>
        </div>


        <!-- Right bar overlay-->
        <div class="rightbar-overlay"></div>

        <!-- JAVASCRIPT -->
        <script src="assets/libs/jquery/jquery.min.js"></script>
        <!-- /////////////////////////// -->
        
        <script src="ajax/js/common.js"></script>
 

        <!-- include main js  -->
        <?php include 'main-js.php' ?>

        <!-- App js -->
        <script src="assets/js/app.js"></script>
        <script src="https://code.jquery.com/ui/1.14.1/jquery-ui.js"></script>
        <script>
            $('#quotation_table').DataTable();
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