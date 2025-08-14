<!doctype html>
<?php
include 'class/include.php';
include './auth.php';

?>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <title>Manage User Permission | <?php echo $COMPANY_PROFILE_DETAILS->name ?> </title>
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

                        </div>

                        <div class="col-md-4 text-md-end text-start mt-3 mt-md-0">
                            <ol class="breadcrumb m-0 justify-content-md-end">
                                <li class="breadcrumb-item"><a href="javascript: void(0);">Dashboard</a></li>
                                <li class="breadcrumb-item active"> User Permission Management </li>
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
                                                <h5 class="font-size-16 mb-1">Manage User Permissions</h5>
                                                <p class="text-muted text-truncate mb-0">Select a user to manage their permissions
                                                </p>
                                            </div>
                                            <div class="flex-shrink-0">
                                                <i class="mdi mdi-chevron-up accor-down-icon font-size-24"></i>
                                            </div>

                                        </div>

                                    </div>

                                    <div class="p-4">
                                        <form id="permissionsForm" method="post" action="save_permissions.php"
                                            autocomplete="off">
                                            <div class="row">
                                                <div class="col-md-3">
                                                    <label for="userType" class="form-label">Filter by User Type (Optional)</label>
                                                    <select id="userType" name="userType" class="form-select">
                                                        <option value="">All User Types</option>
                                                        <?php
                                                        $USER_TYPE = new UserType(null);
                                                        foreach ($USER_TYPE->getActiveUserType() as $user_type) {
                                                            ?>
                                                            <option value="<?php echo $user_type['id']; ?>">
                                                                <?php echo $user_type['name']; ?>
                                                            </option>
                                                        <?php } ?>
                                                    </select>
                                                </div>
                                                <div class="col-md-4">
                                                    <label for="selectUser" class="form-label">Select User <span class="text-danger">*</span></label>
                                                    <select id="selectUser" name="selectUser" class="form-select" required>
                                                        <option value="">Select User</option>
                                                        <?php
                                                        $USER = new User(null);
                                                        $users = $USER->getActiveUsers();
                                                        foreach ($users as $user) {
                                                            ?>
                                                            <option value="<?php echo $user['id']; ?>" data-user-type="<?php echo $user['type']; ?>">
                                                                <?php echo $user['name'] . ' (' . $user['email'] . ')'; ?>
                                                            </option>
                                                        <?php } ?>
                                                    </select>
                                                </div>
                                                <div class="mb-3 col-md-3">
                                                    <label for="searchInput" class="form-label">Search pages</label>
                                                    <input type="text" id="searchInput" class="form-control" placeholder="Search pages...">
                                                </div>
                                            </div>

                                            <div class="mt-3 " id="permissionsTable"
                                                style="display: none; margin-top:10px">

                                                <table class="table thead-light">
                                                    <thead>
                                                        <tr>
                                                            <th>Id</th>
                                                            <th>Page Category</th>
                                                            <th>Page</th>
                                                            <th>Add</th>
                                                            <th>Edit</th>
                                                            <th>Delete</th>
                                                            <th>View</th>
                                                            <th>Print</th>
                                                            <th>Others</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="permissionsTableBody">
                                                        <!-- Dynamic content will be injected here -->
                                                    </tbody>
                                                </table>

                                                <a href="#" class="btn btn-primary float-end" id="create">
                                                    <i class="uil uil-save me-1"></i> Save Permissions
                                                </a>
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

    <!-- Right bar overlay-->
    <div class="rightbar-overlay"></div>

    <!-- JAVASCRIPT -->
    <script src="assets/libs/jquery/jquery.min.js"></script>
    <!-- /////////////////////////// -->
    <script src="ajax/js/user-permissions.js"></script>

    <script>
        // Search functionality for permissions table
        $(document).ready(function() {
            $('#searchInput').on('keyup', function() {
                var value = $(this).val().toLowerCase();
                $('#permissionsTableBody tr').filter(function() {
                    $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
                });
            });

            // Filter users by user type
            $('#userType').on('change', function() {
                const selectedUserType = $(this).val();
                const userSelect = $('#selectUser');
                
                // Clear current selection
                userSelect.val('');
                $('#permissionsTable').hide();
                $('#permissionsTableBody').empty();
                
                // Show/hide users based on selected user type
                userSelect.find('option').each(function() {
                    if ($(this).val() === '') {
                        $(this).show(); // Always show the default option
                        return;
                    }
                    
                    if (selectedUserType === '' || $(this).data('user-type') == selectedUserType) {
                        $(this).show();
                    } else {
                        $(this).hide();
                    }
                });
            });
        });
    </script>

    <!-- include main js  -->
    <?php include 'main-js.php' ?>

</body>

</html>