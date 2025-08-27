<!doctype html>
<?php
include 'class/include.php';
include 'auth.php';

?>
<html lang="en">

<head>

    <meta charset="utf-8" />
    <title> Manage Pages | <?php echo $COMPANY_PROFILE_DETAILS->name ?> </title>
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
                            <a href="#" class="btn btn-primary" id="create">
                                <i class="uil uil-save me-1"></i> Save
                            </a>
                            <a href="#" class="btn btn-warning" id="update">
                                <i class="uil uil-edit me-1"></i> Update
                            </a>
                            <button class="btn btn-info" type="button" data-bs-toggle="modal"
                                data-bs-target="#non-permissionModal">
                                <i class="uil uil-lock me-1"></i> Non Permission
                            </button>

                            <!-- <a href="#" class="btn btn-danger delete-branch">
                                <i class="uil uil-trash-alt me-1"></i> Delete
                            </a> -->

                        </div>

                        <div class="col-md-4 text-md-end text-start mt-3 mt-md-0">
                            <ol class="breadcrumb m-0 justify-content-md-end">
                                <li class="breadcrumb-item"><a href="javascript: void(0);">Dashboard</a></li>
                                <li class="breadcrumb-item active">Pages</li>
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
                                            <h5 class="font-size-16 mb-1">Pages</h5>
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
                                                <label for="Group" class="form-label">Page Category </label>
                                                <div class="input-group mb-3">
                                                    <select id="page_category" name="page_category" class="form-select">

                                                        <option value="">-- Select Category --</option>
                                                        <?php
                                                        $PAGE_CATEGORY = new PageCategory(NULL);
                                                        foreach ($PAGE_CATEGORY->getActiveCategory() as $key => $page_category) {
                                                        ?>
                                                            <option value="<?php echo $page_category['id']; ?>">
                                                                <?php echo $page_category['name']; ?>
                                                            </option>
                                                        <?php
                                                        }
                                                        ?>

                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-3" style="display: none;">
                                                <label for="Group" class="form-label">Page Sub Category </label>
                                                <div class="input-group mb-3">
                                                    <select id="sub_page_category" name="sub_page_category"
                                                        class="form-select">

                                                        <option value="">-- Select Category --</option>
                                                        <?php
                                                        $DEFAULT_DATA = new DefaultData();
                                                        foreach ($DEFAULT_DATA->pagesSubCategory() as $key => $page_category) {
                                                        ?>
                                                            <option value="<?php echo $key; ?>">
                                                                <?php echo $page_category; ?>
                                                            </option>
                                                        <?php
                                                        }
                                                        ?>

                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-md-3">
                                                <label class="form-label" for="page_name">Page Name</label>
                                                <div class="input-group mb-3">
                                                    <input id="page_name" name="page_name" type="text"
                                                        placeholder="Enter Page Name" class="form-control">
                                                    <button class="btn btn-info" type="button" data-bs-toggle="modal"
                                                        data-bs-target="#manage-pages">
                                                        <i class="uil uil-search me-1"></i>
                                                    </button>
                                                </div>
                                            </div>


                                            <div class="col-md-3">
                                                <label for="PageUrl" class="form-label">Page Url</label>
                                                <div class="input-group mb-3">
                                                    <input id="page_url" name="page_url" type="text"
                                                        placeholder="Enter Page Url" class="form-control">
                                                </div>
                                            </div>
                                            <input type="hidden" id="page_id" name="page_id" value="0">
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

    <div class="modal fade bs-example-modal-xl" id="manage-pages" tabindex="-1" role="dialog"
        aria-labelledby="categoryModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title" id="categoryModalLabel">Manage Pages</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <div class="row">
                        <div class="col-12">

                            <table id="datatable2" class="table table-bordered dt-responsive nowrap"
                                style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Page Category</th>
                                        <th>Page Name</th>
                                        <th>Page Url</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    <?php
                                    $PAGES = new Pages(NULL);
                                    foreach ($PAGES->all() as $key => $page) {
                                        $PAGE_CATEGORY = new PageCategory($page['page_category']);
                                        $key++;
                                    ?>
                                        <tr class="select-pages" data-id="<?php echo $page['id']; ?>"
                                            data-category="<?php echo htmlspecialchars($page['page_category']); ?>"
                                            data-name="<?php echo htmlspecialchars($page['page_name']); ?>"
                                            data-url="<?php echo htmlspecialchars($page['page_url']); ?>">

                                            <td><?php echo $key; ?></td>
                                            <td><?php echo htmlspecialchars($PAGE_CATEGORY->name); ?></td>
                                            <td><?php echo htmlspecialchars($page['page_name']); ?></td>
                                            <td><?php echo htmlspecialchars($page['page_url']); ?></td>

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


    <div class="modal fade" id="non-permissionModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg"> <!-- bigger modal for table -->
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Non Permission Required Pages</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <!-- Form to Add/Edit Page -->
                    <form id="nonPermissionForm" class="mb-4">
                        <input type="hidden" id="page_id" name="page_id">

                        <div class="row">
                            <div class="col-md-6">
                                <label for="page" class="form-label">Page</label>
                                <input type="text" id="page" name="page" class="form-control" required>
                            </div>
                            <div class="col-md-3 d-flex align-items-end">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="is_active" name="is_active">
                                </div>
                            </div>
                            <div class="col-md-3 d-flex align-items-end">
                                <button type="submit" class="btn btn-success me-2" id="saveBtn">Save</button>
                                <button type="button" class="btn btn-primary" id="updateBtn" style="display:none;">Update</button>
                            </div>
                        </div>
                    </form>

                    <!-- Table to View Pages -->
                    <table class="table table-bordered table-striped" id="pagesTable">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Page</th>
                                <th>Is Active</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- dynamically filled -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>




    <!-- Right bar overlay-->
    <div class="rightbar-overlay"></div>

    <!-- JAVASCRIPT -->
    <script src="assets/libs/jquery/jquery.min.js"></script>
    <!-- /////////////////////////// -->
    <script src="ajax/js/pages.js"></script>
    <script src="ajax/js/non-permission-page.js"></script>


    <!-- include main js  -->
    <?php include 'main-js.php' ?>


    <!-- App js -->
    <script src="assets/js/app.js"></script>
    <script>
        $(document).ready(function() {
            $('#datatable2').DataTable({
                responsive: true
            });
        });
    </script>
</body>

</html>