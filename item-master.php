<!doctype html>
<?php
include 'class/include.php';
include 'auth.php';


//doc id get by session 
$DOCUMENT_TRACKING = new DocumentTracking($doc_id);

// Get the last inserted quotation
$lastId = $DOCUMENT_TRACKING->item_id;
$item_id = 'ITM/0' . $lastId + 1;

?>
<html lang="en">

<head>

    <meta charset="utf-8" />
    <title>Item Master | <?php echo $COMPANY_PROFILE_DETAILS->name ?> </title>
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
                                <a href="#" class="btn btn-danger delete-item">
                                    <i class="uil uil-trash-alt me-1"></i> Delete
                                </a>
                            <?php endif; ?>
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
                                                    <?php if ($PERMISSIONS['search_page']): ?>
                                                        <button class="btn btn-info" type="button" data-bs-toggle="modal"
                                                            data-bs-target="#item_master">
                                                            <i class="uil uil-search me-1"></i>
                                                        </button>
                                                    <?php endif; ?>
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

        </div>
    </div>

    <div id="item_master" class="modal fade  " tabindex="-1" role="dialog" aria-labelledby="brandModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="brandModalLabel">Manage Item</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <div class="row">
                        <div class="col-12">

                            <table id="datatable" class="table table-bordered dt-responsive nowrap"
                                style="width: 100%;">
                                <thead>
                                    <tr>
                                        <th>#ID</th>
                                        <th>Code</th>
                                        <th>Name</th>
                                        <th>Brand</th>
                                        <th>Cost</th>
                                        <th>Cash Price</th>
                                        <th>Credit Price</th>
                                        <th>Cash %</th>
                                        <th>Credit %</th>
                                        <th>Status</th>
                                    </tr>

                                </thead>
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
    <script src="ajax/js/item-master.js"></script>

    <!-- include main js  -->
    <?php include 'main-js.php' ?>


</body>

</html>