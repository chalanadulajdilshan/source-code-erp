jQuery(document).ready(function () {

    // Save all payments
    $("#savePayment").click(function (event) {
        event.preventDefault();

        let payments = [];
        let finalTotal = parseFloat($('#modalFinalTotal').val()) || 0;
        let totalAmount = 0;

        // Collect all payment rows
        $('#paymentRows .payment-row').each(function () {
            let methodId = $(this).find('.paymentType').val();
            let amount = parseFloat($(this).find('.paymentAmount').val()) || 0;
            let paymentMethod = $(this).find('.paymentType option:selected').text().toLowerCase();
            
            // Only include cheque details for cheque payments
            let chequeNumber = null;
            let chequeBank = null;
            let chequeDate = '1000-01-01'; // Default valid MySQL date
            
            if (paymentMethod.includes('cheque')) {
                chequeNumber = $(this).find('input[name="chequeNumber[]"]').val() || null;
                chequeBank = $(this).find('input[name="chequeBank[]"]').val() || null;
                let dateInput = $(this).find('input[name="chequeDate[]"]').val();
                chequeDate = dateInput ? dateInput : '1000-01-01'; // Use default date if not provided
            }

            if (!methodId) {
                swal({
                    title: "Error!",
                    text: "Please select a payment method in all rows.",
                    type: "error",
                    timer: 2000,
                    showConfirmButton: false
                });
                return false; // break out of each
            }

            if (amount <= 0) {
                swal({
                    title: "Error!",
                    text: "Please enter a valid amount in all rows.",
                    type: "error",
                    timer: 2000,
                    showConfirmButton: false
                });
                return false; // break out of each
            }

            totalAmount += amount;

            payments.push({
                method_id: methodId,
                amount: amount,
                reference_no: chequeNumber,
                bank_name: chequeBank,
                cheque_date: chequeDate || null
            });
        });

        if (payments.length === 0) {
            swal({
                title: "Error!",
                text: "No payments to save.",
                type: "error",
                timer: 2000,
                showConfirmButton: false
            });
            return false;
        }

        if (totalAmount > finalTotal) {
            swal({
                title: "Error!",
                text: "Total payments exceed final total.",
                type: "error",
                timer: 2000,
                showConfirmButton: false
            });
            return false;
        }

        // Optional: Show preloader
        $('.someBlock').preloader();

        // Send AJAX
        $.ajax({
            url: "ajax/php/invoice-payments.php",
            type: "POST",
            data: {
                invoice_id: $('#modal_invoice_id').val(),
                payments: payments,
                note: $('#note').val(),
                saveMultiple: true
            },
            dataType: "JSON",
            success: function (response) {
                $('.someBlock').preloader('remove');

                if (response.status === "success") {
                    swal({
                        title: "Success!",
                        text: "Payments saved successfully!",
                        type: "success",
                        timer: 2000,
                        showConfirmButton: false
                    });

                    setTimeout(() => {
                        window.location.reload();
                    }, 2000);

                } else {
                    swal({
                        title: "Error!",
                        text: response.message || "Something went wrong.",
                        type: "error",
                        timer: 2000,
                        showConfirmButton: false
                    });
                }
            },
            error: function () {
                $('.someBlock').preloader('remove');
                swal({
                    title: "Error!",
                    text: "AJAX request failed.",
                    type: "error",
                    timer: 2000,
                    showConfirmButton: false
                });
            }
        });

        return false;
    });
});
