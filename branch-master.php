<!doctype html>
<?php
include 'class/include.php';
include 'auth.php';
?>
<html lang="en">

<head>

    <meta charset="utf-8" />
    <title>Branch Master | <?php echo $COMPANY_PROFILE_DETAILS->name ?> </title>
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
                                <a href="#" class="btn btn-danger delete-branch">
                                    <i class="uil uil-trash-alt me-1"></i> Delete
                                </a>
                            <?php endif; ?>

                        </div>

                        <div class="col-md-4 text-md-end text-start mt-3 mt-md-0">
                            <ol class="breadcrumb m-0 justify-content-md-end">
                                <li class="breadcrumb-item"><a href="javascript: void(0);">Dashboard</a></li>
                                <li class="breadcrumb-item active">Branch Master</li>
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
                                            <h5 class="font-size-16 mb-1">Branch Master</h5>
                                            <p class="text-muted text-truncate mb-0">Fill all information below to add
                                                branches</p>
                                        </div>
                                        <div class="flex-shrink-0">
                                            <i class="mdi mdi-chevron-up accor-down-icon font-size-24"></i>
                                        </div>
                                    </div>

                                </div>

                                <div class="p-4">
                                    <form id="form-data" autocomplete="off">
                                        <div class="row">

                                            <!-- Bank ID -->
                                            <div class="col-md-3">
                                                <label for="bankId" class="form-label">Bank ID</label>
                                                <div class="input-group mb-3">
                                                    <select id="bankId" name="bankId" class="form-select">
                                                        <option value="">-- Select Bank Name --</option>
                                                        <?php
                                                        $BANK = new Bank(NULL);
                                                        foreach ($BANK->all() as $key => $bank) { ?>
                                                            <option value="<?php echo $bank['id']; ?>">
                                                                <?php echo $bank['code'] . ' - ' . $bank['name']; ?>
                                                            </option>
                                                        <?php } ?>

                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-md-3">
                                                <label class="form-label" for="itemCode">Branch Code</label>
                                                <div class="input-group mb-3">
                                                    <input id="code" name="code" type="text" class="form-control"
                                                        placeholder="Enter Branch Code">
                                                    <button class="btn btn-info" type="button" data-bs-toggle="modal"
                                                        data-bs-target="#branch_master">
                                                        <i class="uil uil-search me-1"></i>
                                                    </button>
                                                </div>
                                            </div>

                                            <!-- Branch Name -->
                                            <div class="col-md-3">
                                                <label for="name" class="form-label">Branch Name</label>
                                                <div class="input-group mb-3">
                                                    <input id="name" name="name" type="text" class="form-control"
                                                        placeholder="Enter Branch Name">
                                                </div>
                                            </div>

                                            <!-- Address -->
                                            <div class="col-md-3">
                                                <label for="address" class="form-label">Address</label>
                                                <div class="input-group mb-3">
                                                    <input id="address" name="address" type="text" class="form-control"
                                                        placeholder="Enter Address">
                                                </div>
                                            </div>

                                            <!-- Phone Number -->
                                            <div class="col-md-3">
                                                <label for="phoneNumber" class="form-label">Phone Number</label>
                                                <div class="input-group mb-3">
                                                    <input id="phoneNumber" name="phoneNumber" type="text"
                                                        class="form-control" placeholder="Enter Phone Number">
                                                </div>
                                            </div>

                                            <!-- City -->
                                            <div class="col-md-3">
                                                <label for="city" class="form-label">City</label>
                                                <div class="input-group mb-3">
                                                    <input id="city" name="city" type="text" class="form-control"
                                                        placeholder="Enter City">
                                                </div>
                                            </div>

                                            <!-- Active Status -->
                                            <div class="col-md-1 d-flex justify-content-center align-items-center">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" id="activeStatus"
                                                        name="activeStatus">
                                                    <label class="form-check-label" for="activeStatus">
                                                        Active
                                                    </label>
                                                </div>
                                            </div>


                                        </div>

                                        <!-- Remark Note -->
                                        <div class="col-12">
                                            <label for="remark" class="form-label">Remark Note</label>
                                            <textarea id="remark" name="remark" class="form-control" rows="4"
                                                placeholder="Enter any remarks or notes about the branch..."></textarea>
                                        </div>
                                        <input type="hidden" id="branch_id" name="branch_id">
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



    <!-- model open here -->
    <div class="modal fade " id="branch_master" tabindex="-1" role="dialog" aria-labelledby="myExtraLargeModalLabel"
        aria-hidden="true">
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


                            <table  class="datatable table table-bordered dt-responsive nowrap"
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
                                    foreach ($BRANCH->all() as $key => $branch) {
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
                                            <td><?php echo htmlspecialchars($BANK->code . ' - ' . $BANK->name); ?></td>
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




    <!-- Right bar overlay-->
    <div class="rightbar-overlay"></div>

    <!-- JAVASCRIPT -->
    <script src="assets/libs/jquery/jquery.min.js"></script>
    <!-- /////////////////////////// -->
    <script src="ajax/js/branch.js"></script>


    <!-- include main js  -->
    <?php include 'main-js.php' ?>

    <script>
        $('#bank_table').DataTable();
    </script>

</body>

</html>