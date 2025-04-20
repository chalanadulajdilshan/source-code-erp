<div class="modal fade bs-example-modal-xl" tabindex="-1" role="dialog" aria-labelledby="myExtraLargeModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="myExtraLargeModalLabel">Manage Bank Branches</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-12">

                        </p>

                        <table id="datatable" class="table table-bordered dt-responsive nowrap"
                            style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                            <thead>
                                <tr>
                                    <th>#id</th>
                                    <th>Bank</th>
                                    <th>Branch</th>
                                    <th>Address</th>
                                    <th>Phone Number</th>
                                    <th>City</th>
                                    <th>Status</th>

                                </tr>
                            </thead>


                            <tbody>
                                <?php
                                $BRANCH = new Branch(null);
                                foreach ($BRANCH->all() as $key => $branch) {
                                    $key++;
                                    $BANK = new Bank($branch['bank_id']);
                                    ?>
                                    <tr class="select-branch" data-id="<?php echo $branch['id']; ?>"
                                        data-bankid="<?php echo $branch['bank_id']; ?>"
                                        data-code="<?php echo htmlspecialchars($branch['code']); ?>"
                                        data-name="<?php echo htmlspecialchars($branch['name']); ?>"
                                        data-address="<?php echo htmlspecialchars($branch['address']); ?>"
                                        data-phone="<?php echo htmlspecialchars($branch['phone_number']); ?>"
                                        data-city="<?php echo htmlspecialchars($branch['city']); ?>"
                                        data-active="<?php echo $branch['active_status']; ?>">

                                        <td><?php echo $key; ?></td>
                                        <td><?php echo htmlspecialchars($BANK->code . ' - ' . $BANK->name); ?></td>
                                        <td><?php echo htmlspecialchars($branch['code'] . ' - ' . $branch['name']); ?></td>
                                        <td><?php echo htmlspecialchars($branch['address']); ?></td>
                                        <td><?php echo htmlspecialchars($branch['phone_number']); ?></td>
                                        <td><?php echo htmlspecialchars($branch['city']); ?></td>
                                        <td>
                                            <?php if ($branch['active_status'] == 1): ?>
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