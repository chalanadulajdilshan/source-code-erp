jQuery(document).ready(function () {

    //------------------ MAIN ITEM MASTER ITEM LOARD---------------//
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
            { data: "category", title: "Category" },
            { data: "list_price", title: "List Price" },
            { data: "invoice_price", title: "Invoice Price" },
            { data: "qty", title: "Quantity" },
            { data: "discount", title: "Discount %" },
            { data: "status_label", title: "Status" }
        ],
        order: [[0, 'desc']],
        pageLength: 100
    });



    // On row click, load selected item into input fields
    $('#datatable tbody').on('click', 'tr', function () {
        var data = table.row(this).data();
        if (!data) return;

        const paymentType = $('#payment_type').val();


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
        $('#list_price').val(data.list_price);
        $('#group').val(data.group);
        $('#re_order_level').val(data.re_order_level);
        $('#re_order_qty').val(data.re_order_qty);
        $('#stock_type').val(data.stock_type);
        $('#invoice_price').val(data.invoice_price);

        $('#note').val(data.note);

        // Checkbox
        $('#is_active').prop('checked', data.status == 1); // assuming 1 = active

        // Optional: trigger change for dropdowns if you have dependent selects
        $('#brand, #group, #category, #stock_type').trigger('change');
        $('#create').hide();
        $('#update').removeAttr('hidden');

        // Close modal
        $('#main_item_master').modal('hide');
    });

    $('#main_item_master').on('hidden.bs.modal', function () {
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
        } else if (!$('#list_price').val() || $('#list_price').val().length === 0) {
            swal({
                title: "Error!",
                text: "Please select item invoice price",
                type: 'error',
                timer: 2000,
                showConfirmButton: false
            });
        } else if (!$('#invoice_price').val() || $('#invoice_price').val().length === 0) {
            swal({
                title: "Error!",
                text: "Please select item invoice price",
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
        } else if (!$('#list_price').val() || $('#list_price').val().length === 0) {
            swal({
                title: "Error!",
                text: "Please select item invoice price",
                type: 'error',
                timer: 2000,
                showConfirmButton: false
            });
        } else if (!$('#invoice_price').val() || $('#invoice_price').val().length === 0) {
            swal({
                title: "Error!",
                text: "Please select item invoice price",
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

        // Refresh the page
        location.reload();
    });


    $(document).on("click", ".delete-item", function (e) {
        e.preventDefault();

        var id = $("#item_id").val();
        var name = $("#name").val();

        if (!name || name === "") {
            swal({
                title: "Error!",
                text: "Please select a Item Master first.",
                type: "error",
                timer: 2000,
                showConfirmButton: false,
            });
            return;
        }

        swal(
            {
                title: "Are you sure?",
                text: "Do you want to delete '" + name + "' Item Master?",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#d33",
                cancelButtonColor: "#6c757d",
                confirmButtonText: "Yes, delete it!",
                cancelButtonText: "Cancel",
                closeOnConfirm: false,
            },
            function (isConfirm) {
                if (isConfirm) {
                    $(".someBlock").preloader();

                    $.ajax({
                        url: "ajax/php/item-master.php",
                        type: "POST",
                        data: {
                            id: id,
                            delete: true,
                        },
                        dataType: "json",
                        success: function (response) {
                            $(".someBlock").preloader("remove");

                            if (response.status === "success") {
                                swal({
                                    title: "Deleted!",
                                    text: "Item Master has been deleted.",
                                    type: "success",
                                    timer: 2000,
                                    showConfirmButton: false,
                                });

                                setTimeout(() => {
                                    window.location.reload();
                                }, 2000);
                            } else {
                                swal({
                                    title: "Error!",
                                    text: "Something went wrong.",
                                    type: "error",
                                    timer: 2000,
                                    showConfirmButton: false,
                                });
                            }
                        },
                    });
                }
            }
        );
    });


});
