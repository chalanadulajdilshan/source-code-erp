jQuery(document).ready(function () {

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
        } else if (!$('#whole_sale_price').val() || $('#whole_sale_price').val().length === 0) {
            swal({
                title: "Error!",
                text: "Please enter wholesale price",
                type: 'error',
                timer: 2000,
                showConfirmButton: false
            });
        } else if (!$('#retail_price').val() || $('#retail_price').val().length === 0) {
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
        } else if (!$('#whole_sale_price').val() || $('#whole_sale_price').val().length === 0) {
            swal({
                title: "Error!",
                text: "Please enter wholesale price",
                type: 'error',
                timer: 2000,
                showConfirmButton: false
            });
        } else if (!$('#retail_price').val() || $('#retail_price').val().length === 0) {
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
