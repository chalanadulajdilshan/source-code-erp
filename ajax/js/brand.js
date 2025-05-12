jQuery(document).ready(function () {

    // Create Brand
    $("#create").click(function (event) {
        event.preventDefault();

        // Validation
        if (!$('#category_id').val() || $('#category_id').val().length === 0) {
            swal({
                title: "Error!",
                text: "Please select a category",
                type: 'error',
                timer: 2000,
                showConfirmButton: false
            });
        } else if (!$('#name').val() || $('#name').val().length === 0) {
            swal({
                title: "Error!",
                text: "Please enter brand name",
                type: 'error',
                timer: 2000,
                showConfirmButton: false
            });
        } else {

            $('.someBlock').preloader();


            var formData = new FormData($("#form-data")[0]);
            formData.append('create', true);

            $.ajax({
                url: "ajax/php/brand.php",
                type: 'POST',
                data: formData,
                async: false,
                cache: false,
                contentType: false,
                processData: false,
                success: function (result) {
                    $('.someBlock').preloader('remove');

                    if (result.status === 'success') {
                        swal({
                            title: "Success!",
                            text: "Brand added successfully!",
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

    // Update Brand
    $("#update").click(function (event) {
        event.preventDefault();

        if (!$('#category_id').val() || $('#category_id').val().length === 0) {
            swal({
                title: "Error!",
                text: "Please select a category",
                type: 'error',
                timer: 2000,
                showConfirmButton: false
            });
        } else if (!$('#name').val() || $('#name').val().length === 0) {
            swal({
                title: "Error!",
                text: "Please enter brand name",
                type: 'error',
                timer: 2000,
                showConfirmButton: false
            });
        } else {

            $('.someBlock').preloader();

            var formData = new FormData($("#form-data")[0]);
            formData.append('update', true);

            $.ajax({
                url: "ajax/php/brand.php",
                type: 'POST',
                data: formData,
                async: false,
                cache: false,
                contentType: false,
                processData: false,
                dataType: "JSON",
                success: function (result) {
                    $('.someBlock').preloader('remove');

                    if (result.status == 'success') {
                        swal({
                            title: "Success!",
                            text: "Brand updated successfully!",
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

    // Reset input fields
    $("#new").click(function (e) {
        e.preventDefault();
        $('#form-data')[0].reset();
        $('#category_id').prop('selectedIndex', 0);
        $("#create").show();
    });
 

    // Populate form from modal click
    $(document).on('click', '.select-brand', function () {
        $('#brand_id').val($(this).data('id'));
        $('#category_id').val($(this).data('category'));
        $('#name').val($(this).data('name'));
        $('#discount').val($(this).data('discount'));
        $('#remark').val($(this).data('remark'));

        if ($(this).data('active') == 1) {
            $('#activeStatus').prop('checked', true);
        } else {
            $('#activeStatus').prop('checked', false);
        }

        $("#create").hide();
        $('#brand_master').modal('hide');
    });

    // Delete Brand
    $(document).on('click', '.delete-brand', function (e) {
        e.preventDefault();

        var brandId = $('#brand_id').val();
        var brandName = $('#name').val();

        if (!brandId || brandId === "") {
            swal({
                title: "Error!",
                text: "Please select a brand first.",
                type: "error",
                timer: 2000,
                showConfirmButton: false
            });
            return;
        }

        swal({
            title: "Are you sure?",
            text: "Do you want to delete brand '" + brandName + "'?",
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
                    url: 'ajax/php/brand.php',
                    type: 'POST',
                    data: {
                        id: brandId,
                        delete: true
                    },
                    dataType: 'JSON',
                    success: function (response) {
                        $('.someBlock').preloader('remove');

                        if (response.status === 'success') {
                            swal({
                                title: "Deleted!",
                                text: "Brand has been deleted.",
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
