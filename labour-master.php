<!doctype html>
<?php
include 'class/include.php';
include 'auth.php';

$USER = new User($_SESSION['id']);
$COMPANY = new CompanyProfile($USER->company_id);

$LABOUR_MASTER = new LabourMaster(NULL);

// Get the last inserted package id
$lastId = $LABOUR_MASTER->getLastID();
$labour_id = $COMPANY->company_code . '/00/0' . $lastId + 1;
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
                            <a href="#" class="btn btn-danger delete-labour">
                                <i class="uil uil-trash-alt me-1"></i> Delete
                            </a>

                        </div>

                        <div class="col-md-4 text-md-end text-start mt-3 mt-md-0">
                            <ol class="breadcrumb m-0 justify-content-md-end">
                                <li class="breadcrumb-item"><a href="javascript: void(0);">Dashboard</a></li>
                                <li class="breadcrumb-item active">Labour Master</li>
                            </ol>
                        </div>
                    </div>

                    <!-- end page title -->
                    <div class="row">
                        <div class="col-lg-12">
                            <div id="addproduct-accordion" class="custom-accordion">
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
                                                <h5 class="font-size-16 mb-1"> Labour Master </h5>
                                                <p class="text-muted text-truncate mb-0">Fill all information below to
                                                    Labour Master</p>
                                            </div>
                                            <div class="flex-shrink-0">
                                                <i class="mdi mdi-chevron-up accor-down-icon font-size-24"></i>
                                            </div>
                                        </div>

                                    </div>

                                    <div class="p-4">
                                        <form id="form-data" method="post" enctype="multipart/form-data"
                                            autocomplete="off">


                                            <div class="row">

                                                <!-- Labour type -->
                                                <div class="col-md-2">
                                                    <label for="type" class="form-label">Labour Type <span
                                                            class="text-danger">*</span></label>
                                                    <select id="type" name="type" class="form-select" required>

                                                        <?php
                                                        $LABOUR_TYPE = new LabourType(NULL);
                                                        foreach ($LABOUR_TYPE->getActiveLabourType() as $labour_type) {
                                                            ?>
                                                            <option value="<?php echo $labour_type['id']; ?>">
                                                                <?php echo $labour_type['name']; ?>
                                                            </option>
                                                        <?php } ?>
                                                    </select>
                                                </div>

                                                <!-- labour name -->
                                                <div class="col-md-2">
                                                    <label class="form-label" for="itemCode">Labour Code</label>
                                                    <div class="input-group mb-3">
                                                        <input id="code" name="code" type="text" class="form-control"
                                                            placeholder="Enter labour code"
                                                            value="<?php echo $labour_id ?>" readonly>
                                                        <button class="btn btn-info" type="button"
                                                            data-bs-toggle="modal" data-bs-target="#labourMasterModal">
                                                            <i class="uil uil-search me-1"></i> Find
                                                        </button>
                                                    </div>
                                                </div>


                                                <!-- labour name -->
                                                <div class="col-md-3">
                                                    <label for="name" class="form-label"> Name</label>
                                                    <div class="input-group mb-3">
                                                        <input id="name" name="name" type="text" class="form-control"
                                                            placeholder="Enter labour name">
                                                    </div>
                                                </div>

                                                <!-- Active Status -->
                                                <div class="col-md-1 d-flex justify-content-center align-items-center">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox"
                                                            id="is_active" name="is_active"  >
                                                        <label class="form-check-label"
                                                            for="is_active">Active</label>
                                                    </div>
                                                </div>
                                                <input type="hidden" id="id" name="id">

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
        <!-- end main content-->

    </div>

    <!-- model start here -->
    <div class="modal fade bs-example-modal-xl" id="labourMasterModal" tabindex="-1" role="dialog"
        aria-labelledby="labourModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="labourModalLabel">Manage Labour</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <div class="row">
                        <div class="col-12">

                            <table id="datatable" class="table table-bordered dt-responsive nowrap"
                                style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                <thead>
                                    <tr>
                                        <th>#ID</th>
                                        <th>Code</th>
                                        <th>Type</th>
                                        <th>Name</th>                                    
                                        <th>Status</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    <?php
                                    $LABOUR = new LabourMaster(NULL);
                                    $allLabours = $LABOUR->all();

                                    if (!empty($allLabours)) {
                                        foreach ($allLabours as $key => $labour) {
                                            $key++;
                                            $TYPE = new LabourType($labour['type']);
                                            ?>
                                            <tr class="select-labour" data-id="<?php echo $labour['id']; ?>"
                                                data-code="<?php echo htmlspecialchars($labour['code']); ?>"
                                                data-name="<?php echo htmlspecialchars($labour['name']); ?>"
                                                data-type="<?php echo $labour['type']; ?>"
                                                data-active="<?php echo $labour['is_active']; ?>">

                                                <td><?php echo $key; ?></td>
                                                <td><?php echo htmlspecialchars($labour['name']); ?></td>
                                                <td><?php echo htmlspecialchars($labour['code']); ?></td>                                              
                                                <td><?php echo htmlspecialchars($TYPE->name); ?></td>
                                                <td>
                                                    <?php if ($labour['is_active'] == 1): ?>
                                                        <span class="badge bg-soft-success font-size-12">Active</span>
                                                    <?php else: ?>
                                                        <span class="badge bg-soft-danger font-size-12">Inactive</span>
                                                    <?php endif; ?>
                                                </td>
                                            </tr>
                                        <?php }
                                    } else { ?>
                                        <tr>
                                            <td colspan="5" class="text-center text-muted">No data available</td>
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


    <!-- model end here -->

    <!-- Right bar overlay-->
    <div class="rightbar-overlay"></div>

    <!-- JAVASCRIPT -->
    <script src="assets/libs/jquery/jquery.min.js"></script>
    <!-- /////////////////////////// -->
    <script src="ajax/js/labour-master.js"></script>

    <script src="assets/libs/sweetalert/sweetalert-dev.js"></script>
    <script src="assets/js/jquery.preloader.min.js"></script>
    <!-- JAVASCRIPT -->

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
    <script src="assets/libs/apexcharts/apexcharts.min.js"></script>

    <script src="assets/js/pages/dashboard.init.js"></script>

    <!-- App js -->
    <script src="assets/js/app.js"></script>

</body>

</html>