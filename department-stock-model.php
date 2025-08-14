<div id="department_stock" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="brandModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="department_stockModalLabel">Department Stock</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <div class="row mb-3">
                    <?php 
                    // Check if we're on the stock transfer page
                    $isStockTransferPage = (basename($_SERVER['PHP_SELF']) === 'stock-transfer.php');
                    
                    if (!$isStockTransferPage): 
                    ?>
                    <div class="col-md-3">
                        <label for="filter_department_id" class="form-label">Department</label>
                        <div class="input-group">
                            <select id="filter_department_id" name="filter_department_id" class="form-select">
                                <option value="0">-- All Departments --</option>
                                <?php
                                $DEPARTMENT_MASTER = new DepartmentMaster(NULL);
                                foreach ($DEPARTMENT_MASTER->getActiveDepartment() as $department) {
                                    if ($US->type != 1) {
                                        if ($department['id'] == $US->department_id) {
                                            ?>
                                            <option value="<?php echo $department['id'] ?>" selected>
                                                <?php echo $department['name'] ?>
                                            </option>
                                        <?php }
                                    } else {
                                        ?>
                                        <option value="<?php echo $department['id'] ?>">
                                            <?php echo $department['name'] ?>
                                        </option>
                                        <?php
                                    }
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    
                    <?php else: ?>
                    
                    <?php endif; ?>
                </div>
                <div class="row">
                    <div class="col-12">
                        <table id="datatable" class="table table-bordered dt-responsive nowrap" style="width: 100%;">
                            <thead>
                                <tr>
                                    <th>#ID</th>
                                    <th>Code</th>
                                    <th>Name</th>
                                    <th>Brand</th>
                                    <th>Category</th>
                                    <th>List Price</th>
                                    <th>Invoice Price</th>
                                    <th>Available Qty</th>
                                    <th>Discount</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>