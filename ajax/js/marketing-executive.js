jQuery(document).ready(function () {


    $("#create").click(function (event) {
        event.preventDefault();

        // Validation
        if (!$('#code').val() || $('#code').val().trim().length === 0) {
            swal({
                title: "Error!",
                text: "Please enter executive code",
                type: 'error',
                timer: 2000,
                showConfirmButton: false
            });
        } else if (!$('#full_name').val() || $('#full_name').val().trim().length === 0) {
            swal({
                title: "Error!",
                text: "Please enter full name",
                type: 'error',
                timer: 2000,
                showConfirmButton: false
            });
        } else if (!$('#nic').val() || $('#nic').val().trim().length === 0) {
            swal({
                title: "Error!",
                text: "Please enter NIC",
                type: 'error',
                timer: 2000,
                showConfirmButton: false
            });
        } else if (!$('#mobile_number').val() || $('#mobile_number').val().trim().length === 0) {
            swal({
                title: "Error!",
                text: "Please enter mobile number",
                type: 'error',
                timer: 2000,
                showConfirmButton: false
            });
        } else if (!$('#whatsapp_number').val() || $('#whatsapp_number').val().trim().length === 0) {
            swal({
                title: "Error!",
                text: "Please enter WhatsApp number",
                type: 'error',
                timer: 2000,
                showConfirmButton: false
            });
        } else if (!$('#target_month').val()) {
            swal({
                title: "Error!",
                text: "Please select target month",
                type: 'error',
                timer: 2000,
                showConfirmButton: false
            });
        } else if (!$('#target').val() || isNaN($('#target').val())) {
            swal({
                title: "Error!",
                text: "Please enter a valid target",
                type: 'error',
                timer: 2000,
                showConfirmButton: false
            });

        } else {
            // Preloader start (optional)
            $('.someBlock').preloader();

            var formData = new FormData($("#form-data")[0]);
            formData.append('create', true);

            $.ajax({
                url: "ajax/php/marketing-executive.php",
                type: 'POST',
                data: formData,
                async: false,
                cache: false,
                contentType: false,
                processData: false,
                dataType: "JSON",
                success: function (result) {
                    $('.someBlock').preloader('remove');

                    if (result.status === 'success') {
                        swal({
                            title: "Success!",
                            text: "Marketing Executive updated successfully!",
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

    $("#update").click(function (event) {
        event.preventDefault();

        // Validation
        if (!$('#code').val() || $('#code').val().trim().length === 0) {
            swal({
                title: "Error!",
                text: "Please enter executive code",
                type: 'error',
                timer: 2000,
                showConfirmButton: false
            });
        } else if (!$('#full_name').val() || $('#full_name').val().trim().length === 0) {
            swal({
                title: "Error!",
                text: "Please enter full name",
                type: 'error',
                timer: 2000,
                showConfirmButton: false
            });
        } else if (!$('#nic').val() || $('#nic').val().trim().length === 0) {
            swal({
                title: "Error!",
                text: "Please enter NIC",
                type: 'error',
                timer: 2000,
                showConfirmButton: false
            });
        } else if (!$('#mobile_number').val() || $('#mobile_number').val().trim().length === 0) {
            swal({
                title: "Error!",
                text: "Please enter mobile number",
                type: 'error',
                timer: 2000,
                showConfirmButton: false
            });
        } else if (!$('#whatsapp_number').val() || $('#whatsapp_number').val().trim().length === 0) {
            swal({
                title: "Error!",
                text: "Please enter WhatsApp number",
                type: 'error',
                timer: 2000,
                showConfirmButton: false
            });
        } else if (!$('#target_month').val()) {
            swal({
                title: "Error!",
                text: "Please select target month",
                type: 'error',
                timer: 2000,
                showConfirmButton: false
            });
        } else if (!$('#target').val() || isNaN($('#target').val())) {
            swal({
                title: "Error!",
                text: "Please enter a valid target",
                type: 'error',
                timer: 2000,
                showConfirmButton: false
            });

        } else {
            // Preloader start (optional)
            $('.someBlock').preloader();

            var formData = new FormData($("#form-data")[0]);
            formData.append('update', true);

            $.ajax({
                url: "ajax/php/marketing-executive.php",
                type: 'POST',
                data: formData,
                async: false,
                cache: false,
                contentType: false,
                processData: false,
                dataType: "JSON",
                success: function (result) {
                    $('.someBlock').preloader('remove');

                    if (result.status === 'success') {
                        swal({
                            title: "Success!",
                            text: "Marketing Executive updated successfully!",
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

    // Reset input fields
    $("#new").click(function (e) {
        e.preventDefault();
        $('#form-data')[0].reset();
        $("#create").show();
    });


    // Select Marketing Executive from modal
    $(document).on('click', '.select-executive', function () {
        $('#executive_id').val($(this).data('id'));
        $('#code').val($(this).data('code'));
        $('#full_name').val($(this).data('fullname'));
        $('#nic').val($(this).data('nic'));
        $('#mobile_number').val($(this).data('mobile'));
        $('#whatsapp_number').val($(this).data('whatsapp_number'));
        $('#target_month').val($(this).data('target-month'));
        $('#target').val($(this).data('target'));

        if ($(this).data('active') == 1) {
            $('#is_active').prop('checked', true);
        } else {
            $('#is_active').prop('checked', false);
        }

        $("#create").hide();
        $('.bs-example-modal-xl').modal('hide');
    });

    // Delete Marketing Executive
    $(document).on('click', '.delete-executive', function (e) {
        e.preventDefault();

        var executiveId = $('#executive_id').val();
        var executiveName = $('#full_name').val();

        if (!executiveId || executiveId === "") {
            swal({
                title: "Error!",
                text: "Please select a marketing executive first.",
                type: "error",
                timer: 2000,
                showConfirmButton: false
            });
            return;
        }

        swal({
            title: "Are you sure?",
            text: "Do you want to delete marketing executive '" + executiveName + "'?",
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
                    url: 'ajax/php/marketing-executive.php',
                    type: 'POST',
                    data: {
                        id: executiveId,
                        delete: true
                    },
                    dataType: 'JSON',
                    success: function (response) {
                        $('.someBlock').preloader('remove');

                        if (response.status === 'success') {
                            swal({
                                title: "Deleted!",
                                text: "Marketing executive has been deleted.",
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
