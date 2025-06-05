jQuery(document).ready(function () {


    var table = $('#datatable').DataTable({

        processing: true,
        serverSide: true,

        ajax: {
            url: "ajax/php/item-master.php",
            type: "POST",
            data: function (d) {
                d.filter = true;
                d.status = 0;
                d.stock_only = 0;
            },
            dataSrc: function (json) {

                return json.data;
            },
            error: function (xhr) {
                console.error("Server Error Response:", xhr.responseText);
            }
        },
        columns: [

            { data: "key", title: "#ID" },
            { data: "code", title: "Code" },
            { data: "name", title: "Name" },
            { data: "brand", title: "Brand" },
            { data: "cost", title: "Cost" },
            { data: "cash_price", title: "Cash" },
            { data: "credit_price", title: "Credit" },
            { data: "cash_discount", title: "Cash Dis %" },
            { data: "credit_discount", title: "Credit Dis %" },
            { data: "status_label", title: "Status" }
        ],
        order: [[0, 'desc']],
        pageLength: 100
    });


    // On row click, load selected item into input fields
    $('#datatable tbody').on('click', 'tr', function () {
        var data = table.row(this).data();
        if (!data) return;

        const salesType = $('#sales_type').val();
        const paymentType = $('#payment_type').val();

        // Prices and Discounts
        if (salesType == 1) {
            $('#itemPrice').val(data.cash_price.replace(/,/g, ''));
        } else if (salesType == 2) {
            $('#itemPrice').val(data.credit_price.replace(/,/g, ''));
        }

        if (paymentType == 1) {
            $('#itemDiscount').val(data.cash_discount);
        } else if (paymentType == 2) {
            $('#itemDiscount').val(data.credit_discount);
        } else {
            $('#itemDiscount').val(0);
        }

        // Fill all form fields
        $('#item_id').val(data.id);
        $('#code').val(data.code);
        $('#name').val(data.name);
        $('#brand').val(data.brand_id);   
        $('#size').val(data.size);
        $('#pattern').val(data.pattern);    
        $('#category').val(data.category_id);   
        $('#cost').val(data.cost);
        $('#group').val(data.group);
        $('#re_order_level').val(data.re_order_level);
        $('#re_order_qty').val(data.re_order_qty);
        $('#stock_type').val(data.stock_type);  
        $('#cash_price').val(data.cash_price);
        $('#credit_price').val(data.credit_price);
        $('#cash_discount').val(data.cash_discount);
        $('#credit_discount').val(data.credit_discount);
        $('#note').val(data.note);

        // Checkbox
        $('#is_active').prop('checked', data.status == 1); // assuming 1 = active

        // Optional: trigger change for dropdowns if you have dependent selects
        $('#brand, #group, #category, #stock_type').trigger('change');
        $('#create').hide();
        $('#update').show();
        // Close modal
        $('#item_master').modal('hide');
    });

    $('#item_master').on('hidden.bs.modal', function () {
        if (focusAfterModal) {
            $('#itemQty').focus();
            focusAfterModal = false;
        }
    });
    // Create Item
    $("#create").click(function (event) {
        event.preventDefault();

        // Validation
        if (!$('#code').val() || $('#code').val().length === 0) {
            swal({
                title: "Error!",
                text: "Please enter item code",
                type: 'error',
                timer: 2000,
                showConfirmButton: false
            });
        } else if (!$('#name').val() || $('#name').val().length === 0) {
            swal({
                title: "Error!",
                text: "Please enter item name",
                type: 'error',
                timer: 2000,
                showConfirmButton: false
            });
        } else if (!$('#brand').val() || $('#brand').val().length === 0) {
            swal({
                title: "Error!",
                text: "Please select item brand",
                type: 'error',
                timer: 2000,
                showConfirmButton: false
            });
        } else if (!$('#category').val() || $('#category').val().length === 0) {
            swal({
                title: "Error!",
                text: "Please select item category",
                type: 'error',
                timer: 2000,
                showConfirmButton: false
            });
        } else if (!$('#cost').val() || $('#cost').val().length === 0) {
            swal({
                title: "Error!",
                text: "Please enter item cost",
                type: 'error',
                timer: 2000,
                showConfirmButton: false
            });
        } else if (!$('#re_order_level').val() || $('#re_order_level').val().length === 0) {
            swal({
                title: "Error!",
                text: "Please enter re-order level",
                type: 'error',
                timer: 2000,
                showConfirmButton: false
            });
        } else if (!$('#cash_price').val() || $('#cash_price').val().length === 0) {
            swal({
                title: "Error!",
                text: "Please enter wholesale price",
                type: 'error',
                timer: 2000,
                showConfirmButton: false
            });
        } else if (!$('#credit_price').val() || $('#credit_price').val().length === 0) {
            swal({
                title: "Error!",
                text: "Please enter retail price",
                type: 'error',
                timer: 2000,
                showConfirmButton: false
            });
        } else {

            // Preloader start (optional if you use preloader plugin)
            $('.someBlock').preloader();

            // Grab all form data
            var formData = new FormData($("#form-data")[0]);
            formData.append('create', true);

            $.ajax({
                url: "ajax/php/item-master.php", // Adjust the URL based on your needs
                type: 'POST',
                data: formData,
                async: false,
                cache: false,
                contentType: false,
                processData: false,
                success: function (result) {
                    // Remove preloader
                    $('.someBlock').preloader('remove');

                    if (result.status === 'success') {
                        swal({
                            title: "Success!",
                            text: "Item added successfully!",
                            type: 'success',
                            timer: 2000,
                            showConfirmButton: false
                        });

                        window.setTimeout(function () {
                            window.location.reload();
                        }, 2000);

                    } else if (result.status === 'error') {
                        swal({
                            title: "Error!",
                            text: "Something went wrong.",
                            type: 'error',
                            timer: 2000,
                            showConfirmButton: false
                        });
                    }
                }
            });
        }
        return false;
    });

    // Update Item
    $("#update").click(function (event) {
        event.preventDefault();

        // Validation
        if (!$('#code').val() || $('#code').val().length === 0) {
            swal({
                title: "Error!",
                text: "Please enter item code",
                type: 'error',
                timer: 2000,
                showConfirmButton: false
            });
        } else if (!$('#name').val() || $('#name').val().length === 0) {
            swal({
                title: "Error!",
                text: "Please enter item name",
                type: 'error',
                timer: 2000,
                showConfirmButton: false
            });
        } else if (!$('#brand').val() || $('#brand').val().length === 0) {
            swal({
                title: "Error!",
                text: "Please select item brand",
                type: 'error',
                timer: 2000,
                showConfirmButton: false
            });
        } else if (!$('#category').val() || $('#category').val().length === 0) {
            swal({
                title: "Error!",
                text: "Please select item category",
                type: 'error',
                timer: 2000,
                showConfirmButton: false
            });
        } else if (!$('#cost').val() || $('#cost').val().length === 0) {
            swal({
                title: "Error!",
                text: "Please enter item cost",
                type: 'error',
                timer: 2000,
                showConfirmButton: false
            });
        } else if (!$('#re_order_level').val() || $('#re_order_level').val().length === 0) {
            swal({
                title: "Error!",
                text: "Please enter re-order level",
                type: 'error',
                timer: 2000,
                showConfirmButton: false
            });
        } else if (!$('#cash_price').val() || $('#cash_price').val().length === 0) {
            swal({
                title: "Error!",
                text: "Please enter wholesale price",
                type: 'error',
                timer: 2000,
                showConfirmButton: false
            });
        } else if (!$('#credit_price').val() || $('#credit_price').val().length === 0) {
            swal({
                title: "Error!",
                text: "Please enter retail price",
                type: 'error',
                timer: 2000,
                showConfirmButton: false
            });
        } else {

            // Preloader start (optional if you use preloader plugin)
            $('.someBlock').preloader();

            // Grab all form data
            var formData = new FormData($("#form-data")[0]);
            formData.append('update', true);

            $.ajax({
                url: "ajax/php/item-master.php",
                type: 'POST',
                data: formData,
                async: false,
                cache: false,
                contentType: false,
                processData: false,
                dataType: "JSON",
                success: function (result) {
                    // Remove preloader
                    $('.someBlock').preloader('remove');

                    if (result.status == 'success') {
                        swal({
                            title: "Success!",
                            text: "Item updated successfully!",
                            type: 'success',
                            timer: 2500,
                            showConfirmButton: false
                        });

                        window.setTimeout(function () {
                            window.location.reload();
                        }, 2000);

                    } else if (result.status === 'error') {
                        swal({
                            title: "Error!",
                            text: "Something went wrong.",
                            type: 'error',
                            timer: 2000,
                            showConfirmButton: false
                        });
                    }
                }
            });
        }
        return false;
    });

    // Remove input field values
    $("#new").click(function (e) {
        e.preventDefault();

        // Reset all fields in the form
        $('#form-data')[0].reset();

        // Optional: Reset selects to default option (if needed)
        $('#brand').prop('selectedIndex', 0);
        $('#category').prop('selectedIndex', 0);
        $("#create").show();
    });

});
