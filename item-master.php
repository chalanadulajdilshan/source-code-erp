<!doctype html>
<?php
include 'class/include.php';
include 'auth.php';


//doc id get by session 
$DOCUMENT_TRACKING = new DocumentTracking($doc_id);

// Get the last inserted quotation
$lastId = $DOCUMENT_TRACKING->item_id;
$item_id = 'TI/0' . ($lastId + 1);

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
                                <a href="#" class="btn btn-warning" id="update" hidden>
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
                                                            data-bs-target="#main_item_master">
                                                            <i class="uil uil-search me-1"></i>
                                                        </button>
                                                    <?php endif; ?>
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
                                            <div class="col-md-2">
                                                <div class="mb-3">
                                                    <label class="form-label" for="pattern">Item Pattern <span
                                                            class="text-danger">*</span></label>
                                                    <input id="pattern" name="pattern" type="text" class="form-control"
                                                        placeholder="Enter item pattern">
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

                                            <!-- Group -->
                                            <div class="col-md-2">
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
                                            <!-- List Price -->
                                            <div class="col-md-2">
                                                <div class="mb-3">
                                                    <label class="form-label" for="list_price"> List Price <span
                                                            class="text-danger">*</span></label>
                                                    <input id="list_price" name="list_price" type="text"
                                                        class="form-control list-price" placeholder="Enter item List Price">
                                                </div>
                                            </div>
                                            <!-- DIS Column -->
                                            <div class="col-md-1">
                                                <div class="mb-3">
                                                    <label class="form-label" for="discount">DIS % <span
                                                            class="text-danger">*</span></label>
                                                    <input id="discount" name="discount" type="text"
                                                        class="form-control discount" placeholder="%" value="0">
                                                </div>
                                            </div>
                                            <!-- Invoice Price -->
                                            <div class="col-md-2">
                                                <div class="mb-3">
                                                    <label class="form-label">Selling Price <span
                                                            class="text-danger">*</span></label>
                                                    <input id="invoice_price" name="invoice_price" type="text"
                                                        class="form-control invoice-price" placeholder="Auto-calculated">
                                                </div>
                                            </div>
                                        
                                        </div>

                                        <hr class="my-4">

                                        <div class="row">


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
    
    <script>
        $(document).ready(function() {
            // Function to update item name
            function updateItemName() {
                const brand = $('#brand option:selected').text().trim();
                const size = $('#size').val().trim();
                const pattern = $('#pattern').val().trim();
                
                // Combine the values with spaces, but only if they're not empty
                const itemName = [brand, size, pattern].filter(Boolean).join(' ');
                
                // Update the item name field
                $('#name').val(itemName);
            }
            
            // Function to calculate invoice price based on list price and discount
            function calculateInvoicePrice() {
                const listPrice = parseFloat($('#list_price').val()) || 0;
                const discount = parseFloat($('#discount').val()) || 0;
                
                // Calculate discount amount
                const discountAmount = listPrice * (discount / 100);
                const invoicePrice = listPrice - discountAmount;
                
                // Update invoice price field with 2 decimal places
                $('#invoice_price').val(invoicePrice.toFixed(2));
            }
            
            // Function to calculate discount based on list price and invoice price
            function calculateDiscount() {
                const listPrice = parseFloat($('#list_price').val()) || 0;
                const invoicePrice = parseFloat($('#invoice_price').val()) || 0;
                
                if (listPrice > 0 && invoicePrice > 0) {
                    // Calculate discount percentage
                    const discount = ((listPrice - invoicePrice) / listPrice) * 100;
                    
                    // Update discount field with 2 decimal places
                    $('#discount').val(discount.toFixed(2));
                }
            }
            
            // Add event listeners to the relevant fields
            $('#brand, #size, #pattern').on('change keyup', updateItemName);
            
            // When list price or discount changes, update invoice price
            $('#list_price, #discount').on('change keyup', function() {
                calculateInvoicePrice();
            });
            
            // When invoice price changes, update discount
            $('#invoice_price').on('change keyup', function() {
                calculateDiscount();
            });
            
            // Initialize calculations on page load
            calculateInvoicePrice();
        });
    </script>

    <!-- include main js  -->
    <?php include 'main-js.php' ?>

</body>

</html>