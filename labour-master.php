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
    <title>Labour Master | <?php echo $COMPANY_PROFILE_DETAILS->name ?> </title>
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

                            <?php if ($PERMISSIONS['delete_page']): ?>
                            <a href="#" class="btn btn-danger delete-labour">
                                <i class="uil uil-trash-alt me-1"></i> Delete
                            </a>
                            <?php endif; ?>

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

    <!-- include main js  -->
    <?php include 'main-js.php' ?>

</body>

</html>