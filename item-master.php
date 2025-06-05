<!doctype html>
<?php
include 'class/include.php';
include 'auth.php';

$ITEM_MASTER = new ItemMaster(null);

// Get the last inserted package id
$lastId = $ITEM_MASTER->getLastID();
$item_id = 'IM' . str_pad($lastId + 1, 3, '0', STR_PAD_LEFT);

?>
<html lang="en">

<head>

    <meta charset="utf-8" />
    <title>Item Master | <?php echo $COMPANY_PROFILE_DETAILS->name ?> </title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="<?php echo $COMPANY_PROFILE_DETAILS->name ?>" name="author" />
    <!-- App favicon -->
    <link rel="shortcut icon" href="assets/images/favicon.ico">

    <!-- Bootstrap Css -->
    <link href="assets/css/bootstrap.min.css" id="bootstrap-style" rel="stylesheet" type="text/css" />
    <!-- Icons Css -->
    <link href="assets/css/icons.min.css" rel="stylesheet" type="text/css" />
    <!-- App Css-->
    <link href="assets/css/app.min.css" id="app-style" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.0/css/line.css">

    <link href="assets/libs/sweetalert/sweetalert.css" rel="stylesheet" type="text/css" />

    <link href="assets/css/preloader.css" rel="stylesheet" type="text/css" />
    <!-- Responsive datatable examples -->
    <!-- DataTables -->
    <link href="assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css" rel="stylesheet" type="text/css" />
    <link href="assets/libs/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css" rel="stylesheet"
        type="text/css" />
    <link href="assets/libs/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css" rel="stylesheet"
        type="text/css" />



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
                            <a href="#" class="btn btn-warning" id="update" style="display:none">
                                <i class="uil uil-edit me-1"></i> Update
                            </a>
                            <!-- <a href="#" class="btn btn-danger delete-branch">
                                <i class="uil uil-trash-alt me-1"></i> Delete
                            </a> -->

                        </div>

                        <div class="col-md-4 text-md-end text-start mt-3 mt-md-0">
                            <ol class="breadcrumb m-0 justify-content-md-end">
                                <li class="breadcrumb-item"><a href="javascript: void(0);">Dashboard</a></li>
                                <li class="breadcrumb-item active">Item Master</li>
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
                                            <h5 class="font-size-16 mb-1">Item Master</h5>
                                            <p class="text-muted text-truncate mb-0">Fill all information below to add
                                                Item</p>
                                        </div>
                                        <div class="flex-shrink-0">
                                            <i class="mdi mdi-chevron-up accor-down-icon font-size-24"></i>
                                        </div>
                                    </div>

                                </div>

                                <div class="p-4">
                                    <form id="form-data" autocomplete="off">
                                        <div class="row">
                                            <!-- Item Code -->
                                            <div class="col-md-3">
                                                <label class="form-label" for="code">Item Code</label>
                                                <div class="input-group mb-3">
                                                    <input id="code" name="code" type="text" class="form-control"
                                                        value="<?php echo $item_id ?>" readonly>
                                                    <button class="btn btn-info" type="button" data-bs-toggle="modal"
                                                        data-bs-target="#item_master">
                                                        <i class="uil uil-search me-1"></i>
                                                    </button>
                                                </div>
                                            </div>

                                            <!-- Item Name -->
                                            <div class="col-md-3">
                                                <div class="mb-3">
                                                    <label class="form-label" for="name">Item Name <span
                                                            class="text-danger">*</span></label>
                                                    <input id="name" name="name" type="text" class="form-control"
                                                        placeholder="Enter item name">
                                                </div>
                                            </div>

                                            <!-- Brand -->
                                            <div class="col-md-3">
                                                <div class="mb-3">
                                                    <label class="form-label" for="brand">Manufacturer Brand <span
                                                            class="text-danger">*</span></label>
                                                    <select id="brand" name="brand" class="form-select">
                                                         
                                                        <?php
                                                        $BRAND = new Brand(NULL);
                                                        foreach ($BRAND->activeBrands() as $brand) {
                                                            echo "<option value='{$brand['id']}'>{$brand['name']}</option>";
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                            </div>

                                            <!-- Size -->
                                            <div class="col-md-3">
                                                <div class="mb-3">
                                                    <label class="form-label" for="size">Item Size <span
                                                            class="text-danger">*</span></label>
                                                    <input id="size" name="size" type="text" class="form-control"
                                                        placeholder="Enter item size">
                                                </div>
                                            </div>

                                            <!-- Pattern -->
                                            <div class="col-md-3">
                                                <div class="mb-3">
                                                    <label class="form-label" for="pattern">Item Pattern <span
                                                            class="text-danger">*</span></label>
                                                    <input id="pattern" name="pattern" type="text" class="form-control"
                                                        placeholder="Enter item pattern">
                                                </div>
                                            </div>

                                            <!-- Group -->
                                            <div class="col-md-3">
                                                <div class="mb-3">
                                                    <label class="form-label" for="group">Item Group <span
                                                            class="text-danger">*</span></label>
                                                    <select id="group" name="group" class="form-select">
                                                         
                                                        <?php
                                                        $GROUP_MASTER = new GroupMaster(NULL);
                                                        foreach ($GROUP_MASTER->getActiveGroups() as $group) {
                                                            echo "<option value='{$group['id']}'>{$group['name']}</option>";
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                            </div>

                                            <!-- Category -->
                                            <div class="col-md-3">
                                                <div class="mb-3">
                                                    <label class="form-label" for="category">Item Category <span
                                                            class="text-danger">*</span></label>
                                                    <select id="category" name="category" class="form-select">
                                                        
                                                        <?php
                                                        $CATEGORY_MASTER = new CategoryMaster(NULL);
                                                        foreach ($CATEGORY_MASTER->getActiveCategory() as $category) {
                                                            echo "<option value='{$category['id']}'>{$category['name']}</option>";
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                            </div> 
                                        </div>

                                        <hr class="my-4">

                                        <div class="row">
                                            <!-- Cost -->
                                            <div class="col-md-3">
                                                <div class="mb-3">
                                                    <label class="form-label" for="cost">Item Cost <span
                                                            class="text-danger">*</span></label>
                                                    <input id="cost" name="cost" type="text" class="form-control"
                                                        placeholder="Enter item cost">
                                                </div>
                                            </div>

                                            <!-- Reorder Level -->
                                            <div class="col-md-3">
                                                <div class="mb-3">
                                                    <label class="form-label" for="re_order_level">Reorder Level</label>
                                                    <input id="re_order_level" name="re_order_level" type="text"
                                                        class="form-control" placeholder="Enter reorder level">
                                                </div>
                                            </div>

                                            <!-- Reorder Qty -->
                                            <div class="col-md-3">
                                                <div class="mb-3">
                                                    <label class="form-label" for="re_order_qty">Reorder Quantity <span
                                                            class="text-danger">*</span></label>
                                                    <input id="re_order_qty" name="re_order_qty" type="text"
                                                        class="form-control" placeholder="Enter reorder quantity">
                                                </div>
                                            </div>

                                            <!-- Stock Type -->
                                            <div class="col-md-3">
                                                <div class="mb-3">
                                                    <label class="form-label" for="stock_type">Stock Type <span
                                                            class="text-danger">*</span></label>
                                                    <select id="stock_type" name="stock_type" class="form-select">
                                                        
                                                        <?php
                                                        $STOCK_TYPE = new StockType(NULL);
                                                        foreach ($STOCK_TYPE->getActiveStockType() as $stock_type) {
                                                            echo "<option value='{$stock_type['id']}'>{$stock_type['name']}</option>";
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                            </div>

                                            <!-- Wholesale Price -->
                                            <div class="col-md-3">
                                                <div class="mb-3">
                                                    <label class="form-label" for="cash_price">Cash Price
                                                        <span class="text-danger">*</span></label>
                                                    <input id="cash_price" name="cash_price" type="text"
                                                        class="form-control" placeholder="Enter Cash Price">
                                                </div>
                                            </div>

                                            <!-- Retail Price -->
                                            <div class="col-md-3">
                                                <div class="mb-3">
                                                    <label class="form-label" for="credit_price">Credit Price <span
                                                            class="text-danger">*</span></label>
                                                    <input id="credit_price" name="credit_price" type="text"
                                                        class="form-control" placeholder="Enter Credit price">
                                                </div>
                                            </div>

                                            <!-- Cash Discount -->
                                            <div class="col-md-3">
                                                <div class="mb-3">
                                                    <label class="form-label" for="cash_discount">Cash Discount
                                                        (%)</label>
                                                    <input id="cash_discount" name="cash_discount" type="text"
                                                        class="form-control" placeholder="Enter cash discount">
                                                </div>
                                            </div>

                                            <!-- Credit Discount -->
                                            <div class="col-md-2">
                                                <div class="mb-3">
                                                    <label class="form-label" for="credit_discount">Credit Discount
                                                        (%)</label>
                                                    <input id="credit_discount" name="credit_discount" type="text"
                                                        class="form-control" placeholder="Enter credit discount">
                                                </div>
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
                                        </div>

                                        <!-- Notes -->
                                        <div class="mb-3">
                                            <label class="form-label" for="note">Item Notes</label>
                                            <textarea class="form-control" id="note" name="note" rows="4"
                                                placeholder="Enter any additional notes about the item..."></textarea>
                                        </div>
                                        <input type="hidden" name="item_id" id="item_id" />
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

    <!-- Right bar overlay-->
    <div class="rightbar-overlay"></div>
    <!-- JAVASCRIPT -->
    <script src="assets/libs/jquery/jquery.min.js"></script>
    <!-- /////////////////////////// -->
    <script src="ajax/js/item-master.js"></script>
    <script src="ajax/js/common.js"></script>

    <script src="assets/libs/sweetalert/sweetalert-dev.js"></script>
    <script src="assets/js/jquery.preloader.min.js"></script>

    <script src="assets/libs/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Required datatable js -->
    <script src="assets/libs/datatables.net/js/jquery.dataTables.min.js"></script>
    <script src="assets/libs/datatables.net-bs4/js/dataTables.bootstrap4.min.js"></script>
    <!-- Buttons examples -->
    <script src="assets/libs/datatables.net-buttons/js/dataTables.buttons.min.js"></script>
    <script src="assets/libs/datatables.net-buttons-bs4/js/buttons.bootstrap4.min.js"></script>

    <!-- Responsive examples -->
    <script src="assets/libs/datatables.net-responsive/js/dataTables.responsive.min.js"></script>
    <script src="assets/libs/datatables.net-responsive-bs4/js/responsive.bootstrap4.min.js"></script>

    <!-- Datatable init js -->
    <script src="assets/js/pages/datatables.init.js"></script>


</body>

</html>