<!doctype html>
<?php
include 'class/include.php';
include 'auth.php';

$DAG_VIEW = new Dag();

// Get the last inserted package id
$lastId = $DAG_VIEW->getLastID();
$company_id = 'DV00' . ($lastId + 1);

?>
<html lang="en">

<head>

    <meta charset="utf-8" />
    <title>DAG View | <?php echo $COMPANY_PROFILE_DETAILS->name ?> </title>

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
                                            <h5 class="font-size-16 mb-1">DAG View</h5>
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

                                            <div class="col-md-3">
                                                <label for="department_id" class="form-label">Department</label>
                                                <div class="input-group mb-3">
                                                    <select id="department_id" name="department_id" class="form-select">

                                                        <?php
                                                        $DEPARTMENT_MASTER = new DepartmentMaster(NUll);
                                                        foreach ($DEPARTMENT_MASTER->getActiveDepartment() as $departments) {
                                                            if ($US->type != 1) {
                                                                if ($departments['id'] == $US->department_id) {
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
                                                <label for="company_id" class="form-label">DAG Company</label>
                                                <div class="input-group mb-3">
                                                    <select id="company_id" name="company_id" class="form-select">

                                                        <?php
                                                        $DAG_COMPANY = new DagCompany(NUll);
                                                        foreach ($DAG_COMPANY->getActiveDagCompany() as $dag_company) {
                                                            if ($US->type != 1) {
                                                                if ($dag_company['id'] == $US->id) {  // Changed to == and using id instead of dag_company_id
                                                        ?>
                                                                    <option value="<?php echo $dag_company['id'] ?>">
                                                                        <?php echo $dag_company['name'] ?>
                                                                    </option>
                                                                <?php }
                                                            } else {
                                                                ?>
                                                                <option value="<?php echo $dag_company['id'] ?>">
                                                                    <?php echo $dag_company['name'] ?>
                                                                </option>
                                                        <?php
                                                            }
                                                        } ?>

                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-md-3">
                                                <label for="dag_status" class="form-label">DAG Status</label>
                                                <div class="input-group mb-3">
                                                    <select id="dag_status" name="dag_status" class="form-select">
                                                        <option value="0">Inactive</option>
                                                        <option value="1">Active</option>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-md-2">
                                                <label class="form-label" for="code">Ref No </label>
                                                <div class="input-group mb-3">
                                                    <input id="code" name="code" type="text"
                                                        value="<?php echo $company_id; ?>" placeholder="Ref No"
                                                        class="form-control" readonly>
                                                    <button class="btn btn-info" type="button" data-bs-toggle="modal"
                                                        data-bs-target="#dagviewModel">
                                                        <i class="uil uil-search me-1"></i>
                                                    </button>
                                                </div>
                                            </div>

                                            <div class="col-md-3">
                                                <label for="invoice_no" class="form-label">Invoice No </label>
                                                <input id="invoice_no" name="invoice_no" type="text" class="form-control"
                                                    placeholder="Enter Invoice No">
                                            </div>

                                            <div class="col-md-3">
                                                <label for="dateInput" class="form-label">Date (YYYY-MM)</label>
                                                <div class="input-group">
                                                    <input type="month" class="form-control" id="dateInput" name="date" autocomplete="off">
                                                </div>
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
    <div class="modal fade bs-example-modal-xl" id="beltModel" tabindex="-1" role="dialog" aria-labelledby="myExtraLargeModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="myExtraLargeModalLabel">Manage Belt Types</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12">

                            <table class="datatable table table-bordered dt-responsive nowrap"
                                style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Ref No</th>
                                        <th>Belt Name</th>
                                        <th>Is Active</th>

                                    </tr>
                                </thead>

                                <tbody>
                                    <?php
                                    $BELT = new BeltMaster(null);
                                    foreach ($BELT->all() as $key => $belt) {
                                        $key++;
                                    ?>
                                        <tr class="select-belt" data-id="<?php echo $belt['id']; ?>"
                                            data-code="<?php echo htmlspecialchars($belt['code']); ?>"
                                            data-name="<?php echo htmlspecialchars($belt['name']); ?>"
                                            data-is_active="<?php echo htmlspecialchars($belt['is_active']); ?>">

                                            <td><?php echo $key; ?></td>
                                            <td><?php echo htmlspecialchars($belt['code']); ?></td>
                                            <td><?php echo htmlspecialchars($belt['name']); ?></td>
                                            <td>
                                                <?php if ($belt['is_active'] == 1): ?>
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
    <script src="ajax/js/belt-master.js"></script>

    <script>
        $(document).ready(function() {
            // Set current month as default
            const now = new Date();
            const currentMonth = now.getFullYear() + '-' + String(now.getMonth() + 1).padStart(2, '0');
            $('#dateInput').val(currentMonth);

            // Clicking on the calendar icon should focus the input
            $('.input-group-text').on('click', function() {
                $('#dateInput').focus();
            });
        });
    </script>

    <!-- include main js  -->
    <?php include 'main-js.php' ?>

</body>

</html>