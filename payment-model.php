<div class="modal fade" id="paymentModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <form id="paymentForm">
                <div class="modal-header">
                    <h5 class="modal-title">Finalize Payment</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label>Final Total</label>
                        <input type="text" id="modalFinalTotal" class="form-control" readonly>
                    </div>
                    <div class="mb-3">
                        <label>Payment Type</label>
                        <select id="modalPaymentType" class="form-select">
                            <?php
                            $PAYMENT_TYPE = new PaymentType(NULL);
                            foreach ($PAYMENT_TYPE->all() as $type) {
                                echo "<option value='{$type['id']}'>{$type['name']}</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label>Amount Paid</label>
                        <input type="number" id="amountPaid" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Balance</label>
                        <input type="text" id="balanceAmount" class="form-control" readonly>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">Save Payment</button>
                </div>
            </form>
        </div>
    </div>
</div>
