<!doctype html>
<?php
include 'class/include.php';
include 'auth.php';

$DAG_COMPANY = new DagCompany();

// Get the last inserted package id
$lastId = $DAG_COMPANY->getLastID();
$company_id = 'DC00' . ($lastId + 1);

?>
<html lang="en">

<head>

    <meta charset="utf-8" />
    <title>Dag Company | <?php echo $COMPANY_PROFILE_DETAILS->name ?> </title>

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
                                <a href="#" class="btn btn-danger delete-dag-company">
                                    <i class="uil uil-trash-alt me-1"></i> Delete
                                </a>
                            <?php endif; ?>



                        </div>

                        <div class="col-md-4 text-md-end text-start mt-3 mt-md-0">
                            <ol class="breadcrumb m-0 justify-content-md-end">
                                <li class="breadcrumb-item"><a href="javascript: void(0);">Dashboard</a></li>
                                <li class="breadcrumb-item active">DAG Company</li>
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
                                            <h5 class="font-size-16 mb-1">DAG Company</h5>
                                            <p class="text-muted text-truncate mb-0">Fill all information below</p>
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
                                                <label for="name" class="form-label">Name</label>
                                                <div class="input-group mb-3">
                                                    <input id="name" name="name" type="text" placeholder="Enter Name"
                                                        class="form-control">
                                                </div>
                                            </div>

                                            <div class="col-md-2">
                                                <label class="form-label" for="code">Ref No </label>
                                                <div class="input-group mb-3">
                                                    <input id="code" name="code" type="text"
                                                        value="<?php echo $company_id; ?>" placeholder="Ref No"
                                                        class="form-control" readonly>
                                                    <button class="btn btn-info" type="button" data-bs-toggle="modal"
                                                        data-bs-target="#dagCompanyModel">
                                                        <i class="uil uil-search me-1"></i>
                                                    </button>
                                                </div>
                                            </div>

                                            <div class="col-md-4">
                                                <label for="address" class="form-label">Address <span
                                                        class="text-danger">*</span></label>
                                                <input id="address" name="address" type="text" class="form-control"
                                                    placeholder="Enter address">
                                            </div>

                                            <div class="col-md-2">
                                                <label for="contactPerson" class="form-label">Contact Person <span
                                                        class="text-danger">*</span></label>
                                                <input id="contact_person" name="contact_person" type="text"
                                                    class="form-control" placeholder="Contact person name">
                                            </div>

                                            <div class="col-md-2">
                                                <label for="phone_number" class="form-label">Phone Number
                                                    <span class="text-danger">*</span></label>
                                                <input id="phone_number" name="phone_number" type="text"
                                                    class="form-control" placeholder="Enter phone number">
                                            </div>

                                            <div class="col-md-3">
                                                <label for="email" class="form-label">Email <span
                                                        class="text-danger">*</span></label>
                                                <input id="email" name="email" type="email" class="form-control"
                                                    placeholder="Enter email">
                                            </div>

                                            <div class="col-md-1 d-flex justify-content-center align-items-center">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" id="is_active"
                                                        name="is_active">
                                                    <label class="form-check-label" for="is_active">
                                                        Active
                                                    </label>
                                                </div>
                                            </div>

                                            <div class="col-12 mt-3">
                                                <label for="remark" class="form-label">Remark</label>
                                                <textarea id="remark" name="remark" class="form-control" rows="4"
                                                    placeholder="Enter any remarks..."></textarea>
                                            </div>

                                        </div>
                                        <input type="hidden" id="id" name="id" value="0">

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


    <!-- model open here -->
    <div class="modal fade bs-example-modal-xl" id="dagCompanyModel" tabindex="-1" role="dialog"
        aria-labelledby="myExtraLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="myExtraLargeModalLabel">Manage DAG Company</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12">


                            <table id="dagCompany" class="table table-bordered dt-responsive nowrap"
                                style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Name</th>
                                        <th>Ref No</th>
                                        <th>Address</th>
                                        <th>Contact Person</th>
                                        <th>Phone Number</th>
                                        <th>Email</th>
                                        <th>Is Active</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>


                                <tbody>
                                    <?php
                                    $DAG = new DagCompany(null);
                                    foreach ($DAG->all() as $key => $dag) {
                                        $key++;
                                        ?>
                                        <tr class="select-dagcompany" data-id="<?php echo $dag['id']; ?>"
                                            data-name="<?php echo htmlspecialchars($dag['name']); ?>"
                                            data-code="<?php echo htmlspecialchars($dag['code']); ?>"
                                            data-address="<?php echo htmlspecialchars($dag['address']); ?>"
                                            data-contact_person="<?php echo htmlspecialchars($dag['contact_person']); ?>"
                                            data-phone_number="<?php echo htmlspecialchars($dag['phone_number']); ?>"
                                            data-email="<?php echo htmlspecialchars($dag['email']); ?>"
                                            data-is_active="<?php echo htmlspecialchars($dag['is_active']); ?>"
                                            data-remark="<?php echo htmlspecialchars($dag['remark']); ?>">

                                            <td><?php echo $key; ?></td>
                                            <td><?php echo htmlspecialchars($dag['name']); ?></td>
                                            <td><?php echo htmlspecialchars($dag['code']); ?></td>
                                            <td><?php echo htmlspecialchars($dag['address']); ?></td>
                                            <td><?php echo htmlspecialchars($dag['contact_person']); ?></td>
                                            <td><?php echo htmlspecialchars($dag['phone_number']); ?></td>
                                            <td><?php echo htmlspecialchars($dag['email']); ?></td>
                                            <td>
                                                <?php if ($dag['is_active'] == 1): ?>
                                                    <span class="badge bg-soft-success font-size-12">Active</span>
                                                <?php else: ?>
                                                    <span class="badge bg-soft-danger font-size-12">Inactive</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <a
                                                    href="dag-view-company-vise.php?page_id=53&id= <?php echo $dag['id']; ?>">

                                                    <div class="badge bg-pill bg-soft-success font-size-14"><i
                                                            class="fas fa-eye p-1"></i></div>
                                                </a>

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
    <script src="ajax/js/dag-company.js"></script>


    <!-- include main js  -->
    <?php include 'main-js.php' ?>
    <script>
        $('#dagCompany').DataTable();
    </script>
</body>

</html>