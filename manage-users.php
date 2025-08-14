<!doctype html>
<?php
include 'class/include.php';
include './auth.php';

$USER = new User(NUll);

// Get the last inserted package id
$lastId = $USER->getLastID();
$user_id = 'US00' . ($lastId + 1);
?>
<html lang="en">


<head>

    <meta charset="utf-8" />
    <title>Manage Users | <?php echo $COMPANY_PROFILE_DETAILS->name ?> </title>
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

                        </div>

                        <div class="col-md-4 text-md-end text-start mt-3 mt-md-0">
                            <ol class="breadcrumb m-0 justify-content-md-end">
                                <li class="breadcrumb-item"><a href="javascript: void(0);">Dashboard</a></li>
                                <li class="breadcrumb-item active"> Users Management </li>
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
                                                <h5 class="font-size-16 mb-1">Manage Users </h5>
                                                <p class="text-muted text-truncate mb-0">Fill all information below
                                                    to Manage Users
                                                </p>
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
                                                    <label class="form-label" for="itemCode">User Code</label>
                                                    <div class="input-group mb-3">
                                                        <input id="code" name="code" type="text" class="form-control"
                                                            placeholder="Enter Category Code" readonly
                                                            value="<?php echo $user_id ?>">
                                                        <button class="btn btn-info" type="button"
                                                            data-bs-toggle="modal" data-bs-target="#userModal">
                                                            <i class="uil uil-search me-1"></i>
                                                        </button>
                                                    </div>
                                                </div>

                                                <div class="col-md-2">
                                                    <label for="customerCode" class="form-label">Select User
                                                        Type</label>

                                                    <select id="type" name="type" class="form-select">
                                                        <option selected disabled>Select User Type</option>
                                                        <?php
                                                        $USER_TYPE = new UserType(NULL);
                                                        foreach ($USER_TYPE->getActiveUserType() as $user_type) {
                                                            ?>
                                                            <option value="<?php echo $user_type['id'] ?>">
                                                                <?php echo $user_type['name'] ?>
                                                            </option>
                                                        <?php } ?>
                                                    </select>
                                                </div>

                                                <div class="col-md-3">
                                                    <label for="bankId" class="form-label">Company</label>
                                                    <div class="input-group mb-3">
                                                        <select id="company_id" name="company_id" class="form-select">

                                                            <?php
                                                            $COMPANY = new CompanyProfile(NULL);
                                                            foreach ($COMPANY->getActiveCompany() as $company) {
                                                                ?>
                                                                <option value="<?php echo $company['id'] ?>">
                                                                    <?php echo $company['name'] ?>
                                                                </option>
                                                            <?php } ?>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-2">
                                                    <label for="bankId" class="form-label">Department</label>
                                                    <div class="input-group mb-3">
                                                        <select id="department_id" name="department_id"
                                                            class="form-select">
                                                            <option value="">--Select Department--</option>
                                                            <?php
                                                            $DEPARTMENT_MASTER = new DepartmentMaster(NULL);
                                                            foreach ($DEPARTMENT_MASTER->all() as $department) {
                                                                ?>
                                                                <option value="<?php echo $department['id'] ?>">
                                                                    <?php echo $department['name'] ?>
                                                                </option>
                                                            <?php } ?>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-md-3">
                                                    <label for="username" class="form-label">Name</label>
                                                    <div class="input-group mb-3">
                                                        <input id="name" name="name" type="text" class="form-control"
                                                            placeholder="Enter Name">

                                                    </div>
                                                </div>

                                                <div class="col-md-2">
                                                    <label for="username" class="form-label">User Name</label>
                                                    <div class="input-group mb-3">
                                                        <input id="username" name="username" type="text"
                                                            class="form-control" placeholder="Enter User name">

                                                    </div>
                                                </div>

                                                <div class="col-md-3">
                                                    <label for="customerCode" class="form-label">Password</label>
                                                    <div class="input-group mb-3">
                                                        <input id="password" name="password" type="text"
                                                            class="form-control" placeholder="Enter Password">

                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <label for="customerCode" class="form-label">Email Address</label>
                                                    <div class="input-group mb-3">
                                                        <input id="email" name="email" type="text" class="form-control"
                                                            placeholder="Enter Email Address">

                                                    </div>
                                                </div>

                                                <div class="col-md-3">
                                                    <label for="customerCode" class="form-label"> Mobile Number </label>
                                                    <div class="input-group mb-3">
                                                        <input id="phone" name="phone" type="text" class="form-control"
                                                            placeholder="Enter Mobile Number">

                                                    </div>
                                                </div>

                                                <div class="col-md-1 d-flex justify-content-center align-items-center">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" id="active"
                                                            name="active">
                                                        <label class="form-check-label" for="active">
                                                            Active
                                                        </label>
                                                    </div>
                                                </div>
                                                <input type="hidden" name="user_id" id="user_id" />
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
    <!-- END layout-wrapper -->
    <div class="modal fade" id="userModal" tabindex="-1" role="dialog" aria-labelledby="userModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title" id="userModalLabel">Manage Users</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <div class="row">
                        <div class="col-12">

                            <table  class="datatable table table-bordered dt-responsive nowrap"
                                style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Company </th>
                                        <th>Department </th>
                                        <th>Code</th>
                                        <th>Name</th>
                                        <th> User Name</th>
                                        <th>mobile Number</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    <?php
                                    $USER = new User(NULL);
                                    foreach ($USER->all() as $key => $user) {
                                        $key++;
                                        $COMPANY_PROFILE = new CompanyProfile($user['company_id']);
                                        $DEPARTMENT_MASTER = new DepartmentMaster($user['department_id'])
                                            ?>
                                        <tr class="select-user" data-id="<?php echo $user['id']; ?>"
                                            data-code="<?php echo htmlspecialchars($user['code']); ?>"
                                            data-name="<?php echo htmlspecialchars($user['name']); ?>"
                                            data-username="<?php echo htmlspecialchars($user['username']); ?>"
                                            data-phone="<?php echo htmlspecialchars($user['phone']); ?>"
                                            data-email="<?php echo htmlspecialchars($user['email']); ?>"
                                            data-type="<?php echo $user['type']; ?>"
                                            data-company_id="<?php echo $user['company_id']; ?>"
                                            data-department_id="<?php echo $user['department_id']; ?>"
                                            data-show_password="<?php echo $user['show_password']; ?>"
                                            data-active="<?php echo $user['isActive']; ?>">

                                            <td><?php echo $key; ?></td>
                                            <td><?php echo $COMPANY_PROFILE->name ?></td>
                                            <td><?php echo $DEPARTMENT_MASTER->name ?></td>
                                            <td><?php echo htmlspecialchars($user['code']); ?></td>
                                            <td><?php echo htmlspecialchars($user['name']); ?></td>
                                            <td><?php echo htmlspecialchars($user['username']); ?></td>
                                            <td><?php echo htmlspecialchars($user['phone']); ?></td>
                                            <td>
                                                <?php if ($user['isActive'] == 1): ?>
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


    <!-- Right bar overlay-->
    <div class="rightbar-overlay"></div>

    <!-- JAVASCRIPT -->
    <script src="assets/libs/jquery/jquery.min.js"></script>
    <!-- /////////////////////////// -->
    <script src="ajax/js/user.js"></script>


    <!-- include main js  -->
    <?php include 'main-js.php' ?>

</body>

</html>