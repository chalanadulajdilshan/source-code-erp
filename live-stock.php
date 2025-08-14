<!doctype html>
<?php
include 'class/include.php';
include 'auth.php';

// Fetch departments for the filter
$DEPARTMENT_MASTER = new DepartmentMaster();
$departments = $DEPARTMENT_MASTER->all();
?>

<html lang="en">

<head>
    <meta charset="utf-8" />
    <title>Live Stocks | <?php echo $COMPANY_PROFILE_DETAILS->name ?> </title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="<?php echo $COMPANY_PROFILE_DETAILS->name ?>" name="author" />
    <!-- include main CSS -->
    <?php include 'main-css.php' ?>
    <!-- Select2 CSS -->
    <link href="assets/libs/select2/css/select2.min.css" rel="stylesheet" type="text/css" />
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
                    <!-- start page title -->
                    <div class="row">
                        <div class="col-12">
                            <div class="page-title-box d-flex align-items-center justify-content-between">
                                <h4 class="mb-0">Live Stock</h4>
                            </div>
                        </div>
                    </div>
                    <!-- end page title -->

                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body">
                                    <!-- Department Filter -->
                                    <div class="row mb-3">
                                        <div class="col-md-3">
                                            <label for="filter_department_id" class="form-label">Filter by Department</label>
                                            <select class="form-control select2" id="filter_department_id" name="filter_department_id">
                                                <option value="">All Departments</option>
                                                <?php foreach ($departments as $department): ?>
                                                    <option value="<?php echo $department['id']; ?>">
                                                        <?php echo htmlspecialchars($department['name']); ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="table-responsive">
                                        <table class="table table-bordered dt-responsive nowrap" id="stockTable"
                                            style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                            <thead class="table-light">
                                                <tr>
                                                    <th>Item Code</th>
                                                    <th>Item Description</th>
                                                    <th>Brand</th>
                                                    <th>Pattern</th>
                                                    <th>Category</th>
                                                    <th>Cost</th>
                                                    <th>Selling</th>
                                                    <th>Dealer Price</th>
                                                    <th>Quantity</th>
                                                    <th>Stock Status</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <!-- Data will be loaded by DataTables -->
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- container-fluid -->
            </div>
            <!-- End Page-content -->
        </div>
        <!-- end main content-->
    </div>
    <!-- END layout-wrapper -->

    <!-- JAVASCRIPT -->
    <script src="assets/libs/jquery/jquery.min.js"></script>
    <script src="assets/libs/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- Select2 JS -->
    <script src="assets/libs/select2/js/select2.min.js"></script>
    <!-- Datatables -->
    <script src="assets/libs/datatables.net/js/jquery.dataTables.min.js"></script>
    <script src="assets/libs/datatables.net-bs4/js/dataTables.bootstrap4.min.js"></script>
    <script src="assets/libs/datatables.net-responsive/js/dataTables.responsive.min.js"></script>
    <script src="assets/libs/datatables.net-responsive-bs4/js/responsive.bootstrap4.min.js"></script>
    
    <!-- include main js  -->
    <?php include 'main-js.php' ?>
    
    <!-- Live Stock JS -->
    <script src="ajax/js/live-stock.js"></script>

</body>

</html>