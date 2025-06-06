<!doctype html>

<?php
include 'class/include.php';
include 'auth.php';

$CUSTOMER_MASTER = new CustomerMaster(NULL);

// Get the last inserted package id
$lastId = $CUSTOMER_MASTER->getLastID();
$customer_id = 'CM/00/' . $lastId + 1;
?>

<head>

    <meta charset="utf-8" />
    <title>Customer Master | <?php echo $COMPANY_PROFILE_DETAILS->name ?></title>
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

    <link href="assets/libs/select2/css/select2.min.css" rel="stylesheet" type="text/css" />


    <!-- DataTables -->
    <link href="assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css" rel="stylesheet" type="text/css" />
    <link href="assets/libs/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css" rel="stylesheet"
        type="text/css" />
    <link href="assets/libs/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css" rel="stylesheet"
        type="text/css" />




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
                            <a href="#" class="btn btn-danger delete-customer">
                                <i class="uil uil-trash-alt me-1"></i> Delete
                            </a>

                        </div>

                        <div class="col-md-4 text-md-end text-start mt-3 mt-md-0">
                            <ol class="breadcrumb m-0 justify-content-md-end">
                                <li class="breadcrumb-item"><a href="javascript: void(0);">Dashboard</a></li>
                                <li class="breadcrumb-item active">Customer Master</li>
                            </ol>
                        </div>
                    </div>

                    <!-- end page title -->

                    <div class="row">
                        <div class="col-lg-12">
                            <div id="addproduct-accordion" class="custom-accordion">
                                <div class="card">
                                    <a href="#" class="text-dark" data-bs-toggle="collapse" aria-expanded="true"
                                        aria-controls="addproduct-billinginfo-collapse">
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
                                                    <h5 class="font-size-16 mb-1">Customer Master</h5>
                                                    <p class="text-muted text-truncate mb-0">Fill all information below
                                                        to add items
                                                    </p>
                                                </div>
                                                <div class="flex-shrink-0">
                                                    <i class="mdi mdi-chevron-up accor-down-icon font-size-24"></i>
                                                </div>

                                            </div>

                                        </div>
                                    </a>

                                    <div class="p-4">
                                        <form id="form-data" autocomplete="off">
                                            <div class="row">
                                                <!-- Customer Code -->
                                                <div class="col-md-2">
                                                    <label for="customerCode" class="form-label">Customer Code</label>
                                                    <div class="input-group mb-3">
                                                        <input id="code" name="code" type="text" class="form-control"
                                                            value="<?php echo $customer_id ?>" readonly>
                                                        <button class="btn btn-info" type="button"
                                                            data-bs-toggle="modal" data-bs-target="#AllCustomerModal"><i
                                                                class="uil uil-search me-1"></i>
                                                        </button>
                                                    </div>
                                                </div>

                                                <!-- Full Name -->
                                                <div class="col-md-4">
                                                    <label for="fullName" class="form-label">Full Name <span
                                                            class="text-danger">*</span></label>
                                                    <input id="name" name="name" type="text" class="form-control"
                                                        placeholder="Enter full name">
                                                </div>

                                                <!-- Address -->
                                                <div class="col-md-3">
                                                    <label for="address" class="form-label">Address <span
                                                            class="text-danger">*</span></label>
                                                    <input id="address" name="address" type="text" class="form-control"
                                                        placeholder="Enter address">
                                                </div>

                                                <!-- Mobile 1 -->
                                                <div class="col-md-3">
                                                    <label for="mobile1" class="form-label">Mobile Number 01 <span
                                                            class="text-danger">*</span></label>
                                                    <input id="mobile_number" name="mobile_number" type="text"
                                                        class="form-control" placeholder="Enter primary mobile number">
                                                </div>

                                                <!-- Mobile 2 -->
                                                <div class="col-md-2">
                                                    <label for="mobile_number_2" class="form-label">Mobile Number
                                                        02</label>
                                                    <input id="mobile_number_2" name="mobile_number_2" type="text"
                                                        class="form-control"
                                                        placeholder="Enter secondary mobile number">
                                                </div>

                                                <!-- Email -->
                                                <div class="col-md-3">
                                                    <label for="email" class="form-label">Email <span
                                                            class="text-danger">*</span></label>
                                                    <input id="email" name="email" type="email" class="form-control"
                                                        placeholder="Enter email">
                                                </div>

                                                <!-- Contact Person -->
                                                <div class="col-md-3 ">
                                                    <label for="contactPerson" class="form-label">Contact Person <span
                                                            class="text-danger">*</span></label>
                                                    <input id="contact_person" name="contact_person" type="text"
                                                        class="form-control" placeholder="Enter contact person name">
                                                </div>

                                                <!-- Contact Person No -->
                                                <div class="col-md-3  ">
                                                    <label for="contact_person_number" class="form-label">Contact Person
                                                        No
                                                        <span class="text-danger">*</span></label>
                                                    <input id="contact_person_number" name="contact_person_number"
                                                        type="text" class="form-control"
                                                        placeholder="Enter contact person number">
                                                </div>
                                                <div
                                                    class="col-md-1 d-flex justify-content-center align-items-center mt-3 ">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" id="is_active"
                                                            name="is_active">
                                                        <label class="form-check-label" for="is_active">
                                                            Active
                                                        </label>
                                                    </div>
                                                </div>

                                                <hr class="mt-3">
                                                <!-- Credit Info -->
                                                <div class="col-md-4 mt-3">
                                                    <label for="credit_limit" class="form-label">Credit Limit <span
                                                            class="text-danger">*</span></label>
                                                    <input id="credit_limit" name="credit_limit" type="text"
                                                        class="form-control" placeholder="Enter credit limit">
                                                </div>
                                                <div class="col-md-4 mt-3">
                                                    <label for="outstanding" class="form-label">Outstanding Balance
                                                        <span class="text-danger">*</span></label>
                                                    <input id="outstanding" name="outstanding" type="text"
                                                        class="form-control" placeholder="Enter outstanding balance">
                                                </div>
                                                <div class="col-md-4 mt-3">
                                                    <label for="overdue" class="form-label">Overdue <span
                                                            class="text-danger">*</span></label>
                                                    <input id="overdue" name="overdue" type="text" class="form-control"
                                                        placeholder="Enter overdue amount">
                                                </div>

                                                <!-- VAT Details -->
                                                <div class="col-md-4 mt-3">
                                                    <label for="vat_no" class="form-label">VAT No <span
                                                            class="text-danger">*</span></label>
                                                    <input id="vat_no" name="vat_no" type="text" class="form-control"
                                                        placeholder="Enter VAT number">
                                                </div>
                                                <div class="col-md-4 mt-3">
                                                    <label for="svat_no" class="form-label">SVAT No <span
                                                            class="text-danger">*</span></label>
                                                    <input id="svat_no" name="svat_no" type="text" class="form-control"
                                                        placeholder="Enter SVAT number">
                                                </div>

                                                <!-- Category -->
                                                <div class="col-md-4 mt-3">
                                                    <label for="category" class="form-label">Customer Category
                                                        <span class="text-danger">*</span></label>
                                                    <select id="category" name="category" class="form-select select2">
                                                        <option value=""> --Select category --</option>
                                                        <?php
                                                        $CUSTOMER_CATEGORY = new CustomerCategory(NULL);
                                                        foreach ($CUSTOMER_CATEGORY->activeCategory() as $customer_category) {
                                                            ?>
                                                            <option value="<?php echo $customer_category['id'] ?>">
                                                                <?php echo $customer_category['name'] ?>
                                                            </option>
                                                        <?php } ?>
                                                    </select>
                                                </div>

                                                <div class="col-md-4 mt-3">
                                                    <label for="province" class="form-label">Province <span
                                                            class="text-danger">*</span></label>
                                                    <select id="province" name="province" class="form-select select2">
                                                        <option value="" selected> -- Select province -- </option>
                                                        <?php
                                                        $PROVINCE = new Province(null);
                                                        foreach ($PROVINCE->all() as $province) {
                                                            ?>
                                                            <option value="<?php echo $province['id'] ?>">
                                                                <?php echo $province['name'] ?>
                                                            </option>
                                                        <?php } ?>
                                                    </select>
                                                </div>

                                                <!-- Region Info -->
                                                <div class="col-md-4 mt-3">
                                                    <label for="district" class="form-label">District <span
                                                            class="text-danger">*</span></label>
                                                    <select id="district" name="district" class="form-select select2 ">
                                                        <option value="" selected>-- Select province first -- </option>
                                                        <?php
                                                        $DISTRICT = new District(null);
                                                        foreach ($DISTRICT->all() as $district) {
                                                            ?>
                                                            <option value="<?php echo $district['id'] ?>">
                                                                <?php echo $district['name'] ?>
                                                            </option>
                                                        <?php } ?>
                                                    </select>
                                                </div>

                                                <div class="col-md-4 mt-3">
                                                    <label for="vat_group" class="form-label">Customer VAT Group <span
                                                            class="text-danger">*</span></label>
                                                    <select id="vat_group" name="vat_group" class="form-select">
                                                        <option value="" selected> -- Select VAT group -- </option>
                                                        <option value="Private VAT">Private VAT</option>
                                                        <option value="GOV VAT">GOV VAT</option>
                                                        <option value="GOV NON VAT">GOV NON VAT</option>
                                                    </select>
                                                </div>

                                                <!-- Remark Note -->
                                                <div class="col-12 mt-3">
                                                    <label for="remark" class="form-label">Remark Note</label>
                                                    <textarea id="remark" name="remark" class="form-control" rows="4"
                                                        placeholder="Enter any remarks or notes about the customer..."></textarea>
                                                </div>
                                                <input type="hidden" id="customer_id" name="customer_id" />
                                            </div>
                                        </form>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div> <!-- container-fluid -->
            </div>


            <?php include 'footer.php' ?>

        </div>
    </div>

    <!-- Right bar overlay-->
    <div class="rightbar-overlay"></div>

    <!-- JAVASCRIPT -->
    <script src="assets/libs/jquery/jquery.min.js"></script>
    <!-- /////////////////////////// -->
    <script src="ajax/js/customer-master.js"></script>
    <script src="ajax/js/common.js"></script>

    <script src="assets/libs/sweetalert/sweetalert-dev.js"></script>
    <script src="assets/js/jquery.preloader.min.js"></script>

    <script src="assets/libs/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="assets/libs/metismenu/metisMenu.min.js"></script>
    <script src="assets/libs/simplebar/simplebar.min.js"></script>
    <script src="assets/libs/node-waves/waves.min.js"></script>
    <script src="assets/libs/waypoints/lib/jquery.waypoints.min.js"></script>
    <script src="assets/libs/jquery.counterup/jquery.counterup.min.js"></script>



    <script src="assets/libs/select2/js/select2.min.js"></script>
    <script src="assets/js/pages/form-advanced.init.js"></script>


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