<?php
$CUSTOMER_MASTER = new CustomerMaster(NULL);

// Get the last inserted package id
$lastId = $CUSTOMER_MASTER->getLastID();
$customer_id = 'CM/' . $_SESSION['id'] . '/0' . ($lastId + 1);
?>

<!-- Customer Details Modal -->
<div class="modal fade" id="customerAddModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content shadow-lg rounded-3">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title">
                    <i class="bi bi-person-lines-fill me-2"></i> Add Customer Details
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body bg-light">
                <form id="form-data-invoice">
                    <div class="row g-3">
                        <!-- Customer Code -->
                        <div class="col-md-6">
                            <label class="fw-bold">Customer Code</label>
                            <input type="disabled" id="code" name="code" class="form-control" value="<?php echo $customer_id ?>" readonly>
                        </div>

                        <!-- Customer Name -->
                        <div class="col-md-6">
                            <label class="fw-bold">Customer Name</label>
                            <input type="text" id="name" name="name" class="form-control" placeholder="Enter customer name" required>
                        </div>

                        <!-- Mobile Number -->
                        <div class="col-md-6">
                            <label class="fw-bold">Mobile Number</label>
                            <input type="tel" id="mobile_number" name="mobile_number" class="form-control" placeholder="Enter mobile number" pattern="[0-9]{10}">
                        </div>

                        <!-- Address -->
                        <div class="col-md-12">
                            <label class="fw-bold">Address</label>
                            <textarea id="address" name="address" class="form-control" rows="2" placeholder="Enter address"></textarea>
                        </div>
                    </div>

                    <div class="modal-footer bg-light">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            <i class="bi bi-x-circle me-1"></i> Cancel
                        </button>
                        <button type="button" class="btn btn-success" id="create-invoice-customer">
                            <i class="bi bi-check2-circle me-1"></i> Save Customer
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>