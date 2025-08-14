<div class="modal fade bs-example-modal-xl" id="companyProfileModel" tabindex="-1" role="dialog" aria-labelledby="sModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="ModalLabel">Manage Company</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <div class="row">
                    <div class="col-12">

                        <table  class="datatable table table-bordered dt-responsive nowrap"
                                style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                            <thead>
                                <tr>
                                    <th>Company Code</th>
                                    <th>Name</th>
                                    <th>Address</th>
                                    <th>Mobile</th>
                                    <th>Email</th>
                                    <th>Is Active</th>
                                    <th>is vat</th>
                                </tr>
                            </thead>

                            <tbody>
                                <?php
                                $COMPANY_PROFILE = new CompanyProfile(NULL);
                                foreach ($COMPANY_PROFILE->all() as $key => $company) {
                                    $key++;
                                    ?>
                                    <tr class="select-company" data-id="<?php echo $company['id']; ?>"
                                        data-name="<?php echo htmlspecialchars($company['name']); ?>"
                                        data-address="<?php echo htmlspecialchars($company['address']); ?>"
                                        data-mobile1="<?php echo htmlspecialchars($company['mobile_number_1']); ?>"
                                        data-mobile2="<?php echo htmlspecialchars($company['mobile_number_2']); ?>"
                                        data-mobile3="<?php echo htmlspecialchars($company['mobile_number_3']); ?>"
                                        data-email="<?php echo htmlspecialchars($company['email']); ?>"
                                        data-vatnumber="<?php echo htmlspecialchars($company['vat_number']); ?>"
                                        data-companycode="<?php echo htmlspecialchars($company['company_code']); ?>"
                                        data-image="<?php echo htmlspecialchars($company['image_name']); ?>"
                                        data-active="<?php echo $company['is_active']; ?>"
                                        data-isvat="<?php echo $company['is_vat']; ?>">

                                        <td><?php echo htmlspecialchars($company['company_code']); ?></td>
                                        <td><?php echo htmlspecialchars($company['name']); ?></td> 
                                        <td><?php echo htmlspecialchars($company['address']); ?></td>
                                        <td><?php echo htmlspecialchars($company['mobile_number_1']); ?></td>
                                        <td><?php echo htmlspecialchars($company['email']); ?></td>
                                        <td>
                                            <?php if ($company['is_active'] == 1): ?>
                                                <span class="badge bg-soft-success font-size-12">Active</span>
                                            <?php else: ?>
                                                <span class="badge bg-soft-danger font-size-12">Inactive</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php if ($company['is_vat'] == 1): ?>
                                                <span class="badge bg-soft-primary font-size-12">VAT Registered</span>
                                            <?php else: ?>
                                                <span class="badge bg-soft-warning font-size-12">Non VAT</span>
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