<div class="modal fade bs-example-modal-xl" tabindex="-1" role="dialog" aria-labelledby="brandModalLabel"
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

                        <table id="datatable" class="table table-bordered dt-responsive nowrap"
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
                                    <tr class="select-brand" 
                                    data-id="<?php echo $brand['id']; ?>"
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