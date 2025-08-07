<!doctype html>
<?php
include 'class/include.php';
include 'auth.php';

$EMPLOYEE_MASTER = new EmployeeMaster();

// Get the last inserted package id
$lastId = $EMPLOYEE_MASTER->getLastID();
$employee_id = 'EM/0' . ($lastId + 1);

?>
<html lang="en">

<head>

    <meta charset="utf-8" />
    <title>Employee Master | <?php echo $COMPANY_PROFILE_DETAILS->name ?> </title>
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
                                <a href="#" class="btn btn-danger delete-employee-master">
                                    <i class="uil uil-trash-alt me-1"></i> Delete
                                </a>
                            <?php endif; ?>

                        </div>

                        <div class="col-md-4 text-md-end text-start mt-3 mt-md-0">
                            <ol class="breadcrumb m-0 justify-content-md-end">
                                <li class="breadcrumb-item"><a href="javascript: void(0);">Dashboard</a></li>
                                <li class="breadcrumb-item active">EMPLOYEE MASTER</li>
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
                                            <h5 class="font-size-16 mb-1">EMPLOYEE MASTER</h5>
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

                                            <h2 class="font-size-16 mb-1">PERSONAL INFORMATION</h2>

                                            <div class="col-md-2">
                                                <label class="form-label" for="code">Ref No </label>
                                                <div class="input-group mb-3">
                                                    <input id="code" name="code" type="text"
                                                        value="<?php echo $employee_id; ?>" placeholder="Ref No"
                                                        class="form-control" readonly>
                                                    <button class="btn btn-info" type="button" data-bs-toggle="modal"
                                                        data-bs-target="#employeeModel">
                                                        <i class="uil uil-search me-1"></i>
                                                    </button>
                                                </div>
                                            </div>

                                            <div class="col-md-2">
                                                <label for="name" class="form-label">Name</label>
                                                <div class="input-group mb-3">
                                                    <input id="name" name="name" type="text" placeholder="Employee Name"
                                                        class="form-control">
                                                </div>
                                            </div>

                                            <div class="col-md-2">
                                                <label for="full_name" class="form-label">Full Name</label>
                                                <div class="input-group mb-3">
                                                    <input id="full_name" name="full_name" type="text"
                                                        placeholder="Employee Name" class="form-control">
                                                </div>
                                            </div>

                                            <div class="col-md-2">
                                                <label for="gender" class="form-label">Gender <span
                                                        class="text-danger">*</span></label>
                                                <select id="gender" name="gender" class="form-select">
                                                    <option value="" selected> -- Select Gender -- </option>
                                                    <option value="male">Male</option>
                                                    <option value="female">Female</option>
                                                    <option value="other">Other</option>
                                                </select>
                                            </div>

                                            <div class="col-md-2">
                                                <label class="form-label" for="birthday">Birth Day</label>
                                                <input id="birthday" name="birthday" type="date" class="form-control">
                                            </div>

                                            <div class="col-md-2">
                                                <label for="nic_no" class="form-label">NIC No</label>
                                                <div class="input-group mb-3">
                                                    <input id="nic_no" name="nic_no" type="text" placeholder="NIC No"
                                                        class="form-control">
                                                </div>
                                            </div>

                                            <div class="col-md-2">
                                                <label for="mobile_1" class="form-label">Mobile No 1</label>
                                                <div class="input-group mb-3">
                                                    <input id="mobile_1" name="mobile_1" type="text"
                                                        placeholder="Mobile No 1" class="form-control">
                                                </div>
                                            </div>

                                            <div class="col-md-2">
                                                <label for="mobile_2" class="form-label">Mobile No 2</label>
                                                <div class="input-group mb-3">
                                                    <input id="mobile_2" name="mobile_2" type="text"
                                                        placeholder="Mobile No 2" class="form-control">
                                                </div>
                                            </div>

                                            <div class="col-md-2">
                                                <label for="email" class="form-label">Email <span
                                                        class="text-danger">*</span></label>
                                                <input id="email" name="email" type="email" class="form-control"
                                                    placeholder="Email">
                                            </div>

                                            <h2 class="font-size-16 mb-1">EMPLOYEE INFORMATION</h2>

                                            <div class="col-md-2">
                                                <label for="epf_available" class="form-label">EPF Available <span
                                                        class="text-danger">*</span></label>
                                                <select id="epf_available" name="epf_available" class="form-select">
                                                    <option value="" selected>-- Select EPF Available --</option>
                                                    <option value="available">Available</option>
                                                    <option value="not_available">Not Available</option>
                                                </select>
                                            </div>

                                            <div class="col-md-2">
                                                <label for="epf_no" class="form-label">EPF No</label>
                                                <div class="input-group mb-3">
                                                    <input id="epf_no" name="epf_no" type="text" placeholder="EPF No"
                                                        class="form-control" disabled>
                                                </div>
                                            </div>

                                            <div class="col-md-2">
                                                <label for="finger_print_no" class="form-label">Finger Print No</label>
                                                <div class="input-group mb-3">
                                                    <input id="finger_print_no" name="finger_print_no" type="text"
                                                        placeholder="Finger Print No" class="form-control">
                                                </div>
                                            </div>

                                            <div class="col-md-2">
                                                <label for="department_id" class="form-label">Department <span
                                                        class="text-danger">*</span></label>
                                                <select id="department_id" name="department_id" class="form-select"
                                                    required>
                                                    <option value=""> --Select Department --</option>
                                                    <?php
                                                    $DEPARTMENT_MASTER = new DepartmentMaster(NULL);
                                                    foreach ($DEPARTMENT_MASTER->getActiveDepartment() as $department_master) {
                                                        ?>
                                                        <option value="<?php echo $department_master['id']; ?>">
                                                            <?php echo $department_master['name']; ?>
                                                        </option>
                                                    <?php } ?>
                                                </select>
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
    <div class="modal fade bs-example-modal-xl" id="employeeModel" tabindex="-1" role="dialog"
        aria-labelledby="myExtraLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="myExtraLargeModalLabel">Manage Employee</h5>
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
                                        <th>#</th>
                                        <th>Ref No</th>
                                        <th>Name</th>
                                        <th>Birth Day</th>
                                        <th>NIC No</th>
                                        <th>Mobile No 1</th>
                                        <th>EPF Available</th>
                                        <th>EPF No</th>
                                        <th>Finger Print No</th>
                                        <th>Department</th>
                                    </tr>
                                </thead>


                                <tbody>
                                    <?php
                                    $EMPLOYEE = new EmployeeMaster(null);
                                    foreach ($EMPLOYEE->all() as $key => $employee) {
                                        $key++;
                                        $DEPARTMENT_MASTER = new DepartmentMaster($employee['department_id']);
                                        ?>
                                        <tr class="select-employee" data-id="<?php echo $employee['id']; ?>"
                                            data-code="<?php echo htmlspecialchars($employee['code']); ?>"
                                            data-name="<?php echo htmlspecialchars($employee['name']); ?>"
                                            data-full_name="<?php echo htmlspecialchars($employee['full_name']); ?>"
                                            data-gender="<?php echo htmlspecialchars($employee['gender']); ?>"
                                            data-birthday="<?php echo htmlspecialchars($employee['birthday']); ?>"
                                            data-nic_no="<?php echo htmlspecialchars($employee['nic_no']); ?>"
                                            data-mobile_1="<?php echo htmlspecialchars($employee['mobile_1']); ?>"
                                            data-mobile_2="<?php echo htmlspecialchars($employee['mobile_2']); ?>"
                                            data-email="<?php echo htmlspecialchars($employee['email']); ?>"
                                            data-epf_available="<?php echo htmlspecialchars($employee['epf_available']); ?>"
                                            data-epf_no="<?php echo htmlspecialchars($employee['epf_no']); ?>"
                                            data-finger_print_no="<?php echo htmlspecialchars($employee['finger_print_no']); ?>"
                                            data-department_id="<?php echo htmlspecialchars($employee['department_id']); ?>">

                                            <td><?php echo $key; ?></td>
                                            <td><?php echo htmlspecialchars($employee['code']); ?></td>
                                            <td><?php echo htmlspecialchars($employee['name']); ?></td>
                                            <td><?php echo htmlspecialchars($employee['birthday']); ?></td>
                                            <td><?php echo htmlspecialchars($employee['nic_no']); ?></td>
                                            <td><?php echo htmlspecialchars($employee['mobile_1']); ?></td>
                                            <td>
                                                <?php if ($employee['epf_available'] == 1): ?>
                                                    <span class="badge bg-soft-success font-size-12">Available</span>
                                                <?php else: ?>
                                                    <span class="badge bg-soft-danger font-size-12">Not Available</span>
                                                <?php endif; ?>
                                            </td>
                                            <td><?php echo htmlspecialchars($employee['epf_no']); ?></td>
                                            <td><?php echo htmlspecialchars($employee['finger_print_no']); ?></td>
                                            <td><?php echo htmlspecialchars($DEPARTMENT_MASTER->name); ?></td>
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
    <script src="ajax/js/employee-master.js"></script>


    <!-- include main js  -->
    <?php include 'main-js.php' ?>
    <script>
        $('#employee_table').DataTable();  
    </script>
</body>

</html>