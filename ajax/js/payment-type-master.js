jQuery(document).ready(function () {

    // Create Payment
    $("#create").click(function (event) {
        event.preventDefault();

        // Validation
        if (!$('#name').val() || $('#name').val().length === 0) {
            swal({
                title: "Error!",
                text: "Please enter Payment Name",
                type: 'error',
                timer: 2000,
                showConfirmButton: false
            });
        } else {
            $('.someBlock').preloader();

            var formData = new FormData($("#form-data")[0]);
            
            
            formData.append('create', true);

            $.ajax({
                url: "ajax/php/payment-type-master.php",
                type: 'POST',
                data: formData,
                async: false,
                cache: false,
                contentType: false,
                processData: false,
                dataType: 'json',
                success: function (result) {
                    $('.someBlock').preloader('remove');

                    if (result.status === 'success') {
                        swal({
                            title: "Success!",
                            text: "Payment Type added successfully!",
                            type: 'success',
                            timer: 2000,
                            showConfirmButton: false
                        });

                        setTimeout(function () {
                            window.location.reload();
                        }, 2000);

                    } else {
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

    // Update Payment
    $("#update").click(function (event) {
        event.preventDefault();

        if (!$('#name').val() || $('#name').val().length === 0) {
            swal({
                title: "Error!",
                text: "Please enter payment type name",
                type: 'error',
                timer: 2000,
                showConfirmButton: false
            });
        } else {
            $('.someBlock').preloader();

            var formData = new FormData($("#form-data")[0]);
            formData.append('update', true);

            $.ajax({
                url: "ajax/php/payment-type-master.php",
                type: 'POST',
                data: formData,
                async: false,
                cache: false,
                contentType: false,
                processData: false,
                dataType: "json",
                success: function (result) {
                    $('.someBlock').preloader('remove');

                    if (result.status === 'success') {
                        swal({
                            title: "Success!",
                            text: "Payment type updated successfully!",
                            type: 'success',
                            timer: 2500,
                            showConfirmButton: false
                        });

                        setTimeout(function () {
                            window.location.reload();
                        }, 2000);
                    } else {
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

    // Clear Form
    $("#new").click(function (e) {
        e.preventDefault();

        $('#form-data')[0].reset();
        $('#id').prop('selectedIndex', 0);
        $("#create").show();
    });

    // Open Modal
    $('#open-branch-modal').click(function (e) {
        e.preventDefault();
        var myModal = new bootstrap.Modal(document.querySelector('.bs-example-modal-xl'));
        myModal.show();
    });

    // Populate Form on Row Click
    $(document).on('click', '.select-branch', function () {
        const id = $(this).data('id');
        const name = $(this).data('name');
        const isActive = $(this).data('active'); // 1 or 0

        $('#id').val(id);
        $('#name').val(name);
        $('#is_active').prop('checked', isActive == 1); // Ensure checkbox reflects actual status

        $("#create").hide();
        $('.modal').modal('hide');
    });


    // Delete Payment Type
    $(document).on('click', '.delete-payment-type', function (e) {
        e.preventDefault();

        var id = $('#id').val();
        var name = $('#name').val();

        if (!id || id === "") {
            swal({
                title: "Error!",
                text: "Please select a payment type first.",
                type: "error",
                timer: 2000,
                showConfirmButton: false
            });
            return;
        }

        swal({
            title: "Are you sure?",
            text: "Do you want to delete '" + name + "' payment type?",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#d33",
            cancelButtonColor: "#6c757d",
            confirmButtonText: "Yes, delete it!",
            cancelButtonText: "Cancel",
            closeOnConfirm: false
        }, function (isConfirm) {
            if (isConfirm) {
                $('.someBlock').preloader();

                $.ajax({
                    url: 'ajax/php/payment-type-master.php',
                    type: 'POST',
                    data: {
                        id: id,
                        delete: true
                    },
                    dataType: 'json',
                    success: function (response) {
                        $('.someBlock').preloader('remove');

                        if (response.status === 'success') {
                            swal({
                                title: "Deleted!",
                                text: "Payment type has been deleted.",
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
                                text: "Something went wrong.",
                                type: "error",
                                timer: 2000,
                                showConfirmButton: false
                            });
                        }
                    }
                });
            }
        });
    });

});
