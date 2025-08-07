<!doctype html>
<?php
include 'class/include.php';
include 'auth.php';
?>
<html lang="en">

<head>

    <meta charset="utf-8" />
    <title>Brand Master | <?php echo $COMPANY_PROFILE_DETAILS->name ?> </title>
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
                                <a href="#" class="btn btn-danger delete-brand">
                                    <i class="uil uil-trash-alt me-1"></i> Delete
                                </a>
                            <?php endif; ?>

                        </div>

                        <div class="col-md-4 text-md-end text-start mt-3 mt-md-0">
                            <ol class="breadcrumb m-0 justify-content-md-end">
                                <li class="breadcrumb-item"><a href="javascript: void(0);">Dashboard</a></li>
                                <li class="breadcrumb-item active">Brand Master</li>
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
                                                <h5 class="font-size-16 mb-1">Brand Master</h5>
                                                <p class="text-muted text-truncate mb-0">Fill all information below to
                                                    Item Brands</p>
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

                                                <!-- Brand Category -->
                                                <div class="col-md-4">
                                                    <label for="category_id" class="form-label">Brand Category <span
                                                            class="text-danger">*</span></label>
                                                    <select id="category_id" name="category_id" class="form-select"
                                                        required>

                                                        <?php
                                                        $BRAND_CATEGORY = new BrandCategory(NULL);
                                                        foreach ($BRAND_CATEGORY->all() as $brand_category) {
                                                            ?>
                                                            <option value="<?php echo $brand_category['id']; ?>">
                                                                <?php echo $brand_category['name']; ?>
                                                            </option>
                                                        <?php } ?>
                                                    </select>
                                                </div>


                                                <div class="col-md-3">
                                                    <label class="form-label" for="itemCode">Brand Name </label>
                                                    <div class="input-group mb-3">
                                                        <input id="name" name="name" type="text" class="form-control"
                                                            placeholder="Enter Brand Name">
                                                        <button class="btn btn-info" type="button"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#brand_master">
                                                            <i class="uil uil-search me-1"></i>  
                                                        </button>
                                                    </div>
                                                </div>

                                                <!-- Direct Discount -->
                                                <div class="col-md-3">
                                                    <label for="discount" class="form-label">Direct Discount %</label>
                                                    <div class="input-group mb-3">
                                                        <input id="discount" name="discount" type="text"
                                                            class="form-control"
                                                            placeholder="Enter Discount Percentage">
                                                    </div>
                                                </div>

                                                <!-- Active Status -->
                                                <div class="col-md-1 d-flex justify-content-center align-items-center">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox"
                                                            id="activeStatus" name="activeStatus" value="1">
                                                        <label class="form-check-label"
                                                            for="activeStatus">Active</label>
                                                    </div>
                                                </div>

                                                <!-- Remark Note -->
                                                <div class="col-12">
                                                    <label for="remark" class="form-label">Remark Note</label>
                                                    <textarea id="remark" name="remark" class="form-control" rows="4"
                                                        placeholder="Enter any remarks or notes about the brand..."></textarea>
                                                </div>
                                                <input type="hidden" id="brand_id" name="brand_id">

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
    <div class="modal fade " id="brand_master" tabindex="-1" role="dialog" aria-labelledby="brandModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="brandModalLabel">Manage Brands</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <div class="row">
                        <div class="col-12">

                            <table  class="datatable table table-bordered dt-responsive nowrap"
                                style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                                <thead>
                                    <tr>
                                        <th>#ID</th>
                                        <th>Category</th>
                                        <th>Brand</th>
                                        <th>Discount %</th>
                                        <th>Status</th>
                                        <th>Remark</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    <?php
                                    $BRAND = new Brand(NULL);
                                    foreach ($BRAND->all() as $key => $brand) {
                                        $key++;
                                        $CATEGORY = new BrandCategory($brand['category_id']);
                                        ?>
                                        <tr class="select-brand" data-id="<?php echo $brand['id']; ?>"
                                            data-category="<?php echo $brand['category_id']; ?>"
                                            data-name="<?php echo htmlspecialchars($brand['name']); ?>"
                                            data-discount="<?php echo htmlspecialchars($brand['discount']); ?>"
                                            data-remark="<?php echo htmlspecialchars($brand['remark']); ?>"
                                            data-active="<?php echo $brand['is_active']; ?>">

                                            <td><?php echo $key; ?></td>
                                            <td><?php echo htmlspecialchars($CATEGORY->name); ?></td>
                                            <td><?php echo htmlspecialchars($brand['name']); ?></td>
                                            <td><?php echo htmlspecialchars($brand['discount']); ?>%</td>
                                            <td>
                                                <?php if ($brand['is_active'] == 1): ?>
                                                    <span class="badge bg-soft-success font-size-12">Active</span>
                                                <?php else: ?>
                                                    <span class="badge bg-soft-danger font-size-12">Inactive</span>
                                                <?php endif; ?>
                                            </td>
                                            <td><?php echo htmlspecialchars($brand['remark']); ?></td>
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
    <script src="ajax/js/brand.js"></script>

    <!-- include main js  -->
    <?php include 'main-js.php' ?>

</body>

</html>