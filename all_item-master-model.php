<style>
    #itemMaster_wrapper .table-responsive {
        max-height: 100px;
        /* Set desired height */
        overflow-y: auto;
    }
</style>
<div id="all_item_master" class="modal fade  " tabindex="-1" role="dialog" aria-labelledby="brandModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-xl ">
        <div class="modal-content  ">
            <div class="modal-header">
                <h5 class="modal-title" id="brandModalLabel">All Items Show the Table</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <div class="row">
                    <div class="col-12">
                        <div class="row">

                            <div class="col-md-2">
                                <label for="username" class="form-label">Search By Code or Name</label>
                                <div class="input-group mb-3">
                                    <input id="item_item_code" name="item_item_code" type="text" class="form-control"
                                        placeholder="Search by item code or Name">

                                </div>
                            </div>

                            <div class="col-md-2">
                                <label for="Department" class="form-label">Select Brand</label>
                                <div class="input-group mb-3">
                                    <select id="item_brand_id" name="item_brand_id" class="form-select">
                                        <option value="0">-- All Brands -- </option>
                                        <?php
                                        $BRAND = new Brand(NULL);
                                        foreach ($BRAND->activeBrands() as $brand) {
                                            echo "<option value='{$brand['id']}'>{$brand['name']}</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <label for="Department" class="form-label">Select Category</label>
                                <div class="input-group mb-3">
                                    <select id="item_category_id" name="item_category_id" class="form-select">
                                        <option value="0">-- All Categories -- </option>

                                        <?php
                                        $CATEGORY_MASTER = new CategoryMaster(NULL);
                                        foreach ($CATEGORY_MASTER->getActiveCategory() as $category) {
                                            echo "<option value='{$category['id']}'>{$category['name']}</option>";
                                        }
                                        ?>

                                    </select>
                                </div>
                            </div>

                            <div class="col-md-2">
                                <label for="Department" class="form-label">Select Group</label>
                                <div class="input-group mb-3">
                                    <select id="item_group_id" name="item_group_id" class="form-select">

                                        <option value="0">-- All Groups -- </option>
                                        <?php
                                        $GROUP_MASTER = new GroupMaster(NULL);
                                        foreach ($GROUP_MASTER->getActiveGroups() as $group) {
                                            echo "<option value='{$group['id']}'>{$group['name']}</option>";
                                        }
                                        ?>

                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2 d-flex flex-column justify-content-end">
                                <label for="department_id" class="form-label">Department</label>
                                <div class="input-group mb-3">
                                    <select id="item_department_id" name="item_department_id" class="form-select">

                                        <?php

                                        $DEPARTMENT_MASTER = new DepartmentMaster(NUll);
                                        foreach ($DEPARTMENT_MASTER->getActiveDepartment() as $departments) {
                                            if ($US->type != 1) {
                                                if ($departments['id'] = $US->department_id) {
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
                            <div class="col-md-1 d-flex align-items-center ">
                                <button id="view_price_report" class="btn btn-primary delete-item">
                                    View
                                </button>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table id="all_itemMaster" class="table table-bordered table-striped w-100">
                                <thead class="table-dark">
                                    <tr>
                                        <th>#id</th>
                                        <th>Item</th>
                                        <th>Note</th>
                                        <th>All Qty</th>
                                        <th>Group</th>
                                        <th>Brand</th>
                                        <th>Category</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                            <nav class="d-flex justify-content-end">
                                <ul class="pagination mb-0" id="allitemPagination"></ul>
                            </nav>
                        </div>


                    </div> <!-- end col -->
                </div> <!-- end row -->
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>