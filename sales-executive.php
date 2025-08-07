<!doctype html>
<?php
include 'class/include.php';
include './auth.php';


$MARKETING_EXECUTIVE = new MarketingExecutive(NULL);

// Get the last inserted package id
$lastId = $MARKETING_EXECUTIVE->getLastID();
$marketing_ex_id = 'ME/0' . ($lastId + 1);
?>

<html lang="en">

<meta charset="utf-8" />
<title>Sales Executive | <?php echo $COMPANY_PROFILE_DETAILS->name ?> </title>
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
                            <a href="#" class="btn btn-danger delete-executive">
                                <i class="uil uil-trash-alt me-1"></i> Delete
                            </a>
                            <?php endif; ?>

                        </div>

                        <div class="col-md-4 text-md-end text-start mt-3 mt-md-0">
                            <ol class="breadcrumb m-0 justify-content-md-end">
                                <li class="breadcrumb-item"><a href="javascript: void(0);">Dashboard</a></li>
                                <li class="breadcrumb-item active">Marketing Executive Master</li>
                            </ol>
                        </div>
                    </div>

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
                                            <h5 class="font-size-16 mb-1">Marketing Executive Master</h5>
                                            <p class="text-muted text-truncate mb-0">Fill all information below to
                                                Marketing Executive</p>
                                        </div>
                                        <div class="flex-shrink-0">
                                            <i class="mdi mdi-chevron-up accor-down-icon font-size-24"></i>
                                        </div>
                                    </div>

                                </div>

                                <div class="p-4">
                                    <form id="form-data" autocomplete="off">
                                        <div class="row">
                                            <!-- Department Code -->
                                            <div class="col-md-3">
                                                <label class="form-label" for="itemCode">Marketing Executive
                                                    Code</label>
                                                <div class="input-group mb-3">
                                                    <input id="code" name="code" type="text" class="form-control"
                                                        placeholder="Enter Department Code" readonly
                                                        value="<?php echo $marketing_ex_id ?>">
                                                    <button class="btn btn-info" type="button" data-bs-toggle="modal"
                                                        data-bs-target="#executivetModel">
                                                        <i class="uil uil-search me-1"></i>
                                                    </button>
                                                </div>
                                            </div>

                                            <!-- Department Name -->
                                            <div class="col-md-3">
                                                <label for="name" class="form-label">Marketing Executive Name</label>
                                                <div class="input-group mb-3">
                                                    <input id="full_name" name="full_name" type="text"
                                                        class="form-control" placeholder="Enter Name">
                                                </div>
                                            </div>


                                            <div class="col-md-3">
                                                <label class="form-label" for="nic">NIC</label>
                                                <input id="nic" name="nic" type="text" class="form-control"
                                                    placeholder="NIC">
                                            </div>

                                            <!-- Mobile Number -->
                                            <div class="col-md-3">
                                                <label class="form-label" for="mobile_number">Mobile Number</label>
                                                <input id="mobile_number" name="mobile_number" type="text"
                                                    class="form-control" placeholder="Mobile Number">
                                            </div>

                                            <!-- WhatsApp Number -->
                                            <div class="col-md-3">
                                                <label class="form-label" for="whatsapp_number">WhatsApp Number</label>
                                                <input id="whatsapp_number" name="whatsapp_number" type="text"
                                                    class="form-control" placeholder="WhatsApp Number">
                                            </div>

                                            <!-- Target Month -->
                                            <div class="col-md-3">
                                                <label class="form-label" for="target_month">Target Month</label>
                                                <input id="target_month" name="target_month" type="month"
                                                    class="form-control">
                                            </div>

                                            <!-- Target -->
                                            <div class="col-md-3">
                                                <label class="form-label" for="target">Target</label>
                                                <input id="target" name="target" type="number" class="form-control"
                                                    placeholder="Target Amount">
                                            </div>

                                            <!-- Joined Date -->
                                            <div class="col-md-2">
                                                <label class="form-label" for="joined_date">Joined Date</label>
                                                <input id="joined_date" name="joined_date" type="date"
                                                    class="form-control">
                                            </div>

                                            <!-- Active Checkbox -->
                                            <div class="col-md-1 d-flex align-items-center">
                                                <div class="form-check mt-3">
                                                    <input class="form-check-input" type="checkbox" id="is_active"
                                                        name="is_active">
                                                    <label class="form-check-label" for="is_active">Active</label>
                                                </div>
                                            </div>

                                            <!-- Remarks -->
                                            <div class="col-md-12 mt-3">
                                                <label class="form-label" for="remark">Remarks</label>
                                                <textarea id="remark" name="remark" class="form-control" rows="3"
                                                    placeholder="Remarks..."></textarea>
                                            </div>

                                            <!-- Hidden ID (for update) -->
                                            <input type="hidden" id="executive_id" name="id">
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
    </div>
    <!-- END layout-wrapper -->

    <!-- ---sale executive model----- -->
    <div class="modal fade bs-example-modal-xl" id="executivetModel" tabindex="-1" role="dialog"
        aria-labelledby="marketingExecutiveModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title" id="marketingExecutiveModalLabel">Select Marketing Executive</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <div class="row">
                        <div class="col-12">

                            <table  class="datatable table table-bordered dt-responsive nowrap"
                                style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                <thead>
                                    <tr>
                                        <th>#Id</th>
                                        <th>Code</th>
                                        <th>Full Name</th>
                                        <th>NIC</th>
                                        <th>Mobile</th>
                                        <th>Target Month</th>
                                        <th>Target</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    <?php
                                    $EXEC = new MarketingExecutive(NULL);
                                    foreach ($EXEC->all() as $key => $executive) {
                                        $key++;
                                        ?>
                                        <tr class="select-executive" data-id="<?php echo $executive['id']; ?>"
                                            data-code="<?php echo htmlspecialchars($executive['code']); ?>"
                                            data-fullname="<?php echo htmlspecialchars($executive['full_name']); ?>"
                                            data-nic="<?php echo htmlspecialchars($executive['nic']); ?>"
                                            data-mobile="<?php echo htmlspecialchars($executive['mobile_number']); ?>"
                                            data-whatsapp_number="<?php echo htmlspecialchars($executive['whatsapp_number']); ?>"
                                            data-target-month="<?php echo htmlspecialchars($executive['target_month']); ?>"
                                            data-target="<?php echo htmlspecialchars($executive['target']); ?>"
                                            data-active="<?php echo $executive['is_active']; ?>">

                                            <td><?php echo $key; ?></td>
                                            <td><?php echo htmlspecialchars($executive['code']); ?></td>
                                            <td><?php echo htmlspecialchars($executive['full_name']); ?></td>
                                            <td><?php echo htmlspecialchars($executive['nic']); ?></td>
                                            <td><?php echo htmlspecialchars($executive['mobile_number']); ?></td>
                                            <td><?php echo htmlspecialchars($executive['target_month']); ?></td>
                                            <td><?php echo number_format($executive['target'], 2); ?></td>
                                            <td>
                                                <?php if ($executive['is_active'] == 1): ?>
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

    <!-- ---sale executive model end----- -->


    <!-- Right bar overlay-->
    <div class="rightbar-overlay"></div>

    <!-- JAVASCRIPT -->
    <script src="assets/libs/jquery/jquery.min.js"></script>
    <!-- /////////////////////////// -->
    <script src="ajax/js/marketing-executive.js"></script>

    <!-- include main js  -->
    <?php include 'main-js.php' ?>

</body>

</html>