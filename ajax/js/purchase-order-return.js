function newPurchaseReturn() {
    $('#itemTableContainer').html('');
    $('#itemTableHeader').text('');
    $('#po_number_modal').modal('show');
}

function selectArnOrder(arnId, arnNumber) {
    $('#itemTableHeader').text('ARN Return Items - ' + 'ARN Number: ' + arnNumber);
    $.ajax({
        url: 'purchase-return-arn-items.php',
        method: 'GET',
        data: {
            arn_id: arnId
        },
        success: function (response) {
            $('#itemTableContainer').html(response);
            $('#po_number_modal').modal('hide'); // optional if modal exists
        },
        error: function () {
            alert('Something went wrong while fetching ARN items');
        }
    });
}

    document.getElementById('makeReturnBtn').addEventListener('click', function() {
        $('#returnModal').show();
    });

   $(document).on('click', '#submitPurchaseReturn', function () {

        const refNo = document.getElementById('refNo').value;
        const reason = document.getElementById('returnReason').value;
        const arnId = document.getElementById('arn_id_hidden').value;

        if (!refNo || !reason) {
            swal({
                title: "Error!",
                text: "Please fill all required fields.",
                type: 'error',
                timer: 2000,
                showConfirmButton: false
            });
            return;
        }

        let hasValidQty = false;
        let hasError = false;
        let returnItems = [];

        document.querySelectorAll('.return-qty').forEach(input => {
            const qty = parseInt(input.value || 0);
            const max = parseInt(input.getAttribute('data-received'));
            const itemId = input.name.match(/\[(.*?)\]/)[1];

            if (qty > 0) {
                hasValidQty = true;
                if (qty > max) {
                    swal({
                        title: "Error!",
                        text: `Return quantity for item ${itemId} cannot exceed received quantity (${max}).`,
                        icon: 'error',
                        timer: 5000,
                        buttons: true
                    });
                    hasError = true;
                } else {
                    returnItems.push({
                        item_id: itemId,
                        quantity: qty
                    });
                }
            }
        });


        if (hasError) {
            return;
        }

        if (!hasValidQty) {
            swal({
                title: "Warning!",
                text: "Please enter return quantity for at least one item.",
                icon: 'warning',
                timer: 2000,
                buttons: false
            });
            return;
        }


        if (!hasValidQty) {
            swal({
                title: "Error!",
                text: "Please enter at least one item to return.",
                type: 'error',
                timer: 2000,
                showConfirmButton: false
            });
            return;
        }

        $.ajax({
            url: 'save-purchase-return.php',
            method: 'POST',
            data: {
                arn_id: arnId,
                ref_no: refNo,
                return_reason: reason,
                return_items: JSON.stringify(returnItems)
            },
            success: function(response) {
                $('#returnModal').hide();
                swal({
                    title: "Success!",
                    text: "Return saved successfully.",
                    type: 'success',
                    confirmButtonText: "OK"
                });
                setTimeout(function() {
                    location.reload();
                }, 3000);

            },
            error: function() {
                swal({
                    title: "Error!",
                    text: "An error occurred while saving the return.",
                    type: 'error',
                    timer: 2000,
                    showConfirmButton: false
                });
            }
        });
    });


$(document).on('click', '.view-return-items', function () {
    const returnId = $(this).data('id');
    $.ajax({
        url: 'purchase-returned-item-list.php',
        method: 'GET',
        data: { return_id: returnId },
        success: function (response) {
            $('#returnItemsContainer').html(response);
            $('#returnItemsModal').modal('show');
        },
        error: function () {
            alert('Failed to load return items.');
        }
    });
});
