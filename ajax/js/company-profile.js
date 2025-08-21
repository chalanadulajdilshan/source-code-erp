jQuery(document).ready(function () {

    // Create Company Profile
    $("#create").click(function (event) {
        event.preventDefault();

        // Validation
        if (!$('#name').val() || $('#name').val().length === 0) {
            swal({
                title: "Error!",
                text: "Please enter company name",
                type: 'error',
                timer: 2000,
                showConfirmButton: false
            });
        } else if (!$('#email').val() || $('#email').val().length === 0) {
            swal({
                title: "Error!",
                text: "Please enter company email",
                type: 'error',
                timer: 2000,
                showConfirmButton: false
            });
        } else if (!$('#email').val() || $('#email').val().length === 0) {
            swal({
                title: "Error!",
                text: "Please enter company email",
                type: 'error',
                timer: 2000,
                showConfirmButton: false
            }); F
        } else {

            $('.someBlock').preloader();

            var formData = new FormData($("#form-data")[0]);
            formData.append('create', true);

            $.ajax({
                url: "ajax/php/company-profile.php",
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
                            text: "Company Profile added successfully!",
                            type: 'success',
                            timer: 2000,
                            showConfirmButton: false
                        });

                        window.setTimeout(function () {
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

    // Update Company Profile
    $("#update").click(function (event) {
        event.preventDefault();

        // Validation
        if (!$('#name').val() || $('#name').val().length === 0) {
            swal({
                title: "Error!",
                text: "Please enter company name",
                type: 'error',
                timer: 2000,
                showConfirmButton: false
            });
        } else if (!$('#email').val() || $('#email').val().length === 0) {
            swal({
                title: "Error!",
                text: "Please enter company email",
                type: 'error',
                timer: 2000,
                showConfirmButton: false
            });
        } else {

            $('.someBlock').preloader();

            var formData = new FormData($("#form-data")[0]);
            formData.append('update', true);

            $.ajax({
                url: "ajax/php/company-profile.php",
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
                            text: "Company Profile updated successfully!",
                            type: 'success',
                            timer: 2500,
                            showConfirmButton: false
                        });

                        window.setTimeout(function () {
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

    // Reset form
    $("#new").click(function (e) {
        e.preventDefault();
        $('#form-data')[0].reset();
        $("#create").show();
    });

    // Open modal
    $('#open-company-profile-modal').click(function (e) {
        e.preventDefault();
        var myModal = new bootstrap.Modal(document.querySelector('.bs-example-modal-xl'));
        myModal.show();
    });

    // Populate form from modal click
    $(document).on('click', '.select-company', function () {
        $('#company_id').val($(this).data('id'));
        $('#name').val($(this).data('name'));
        $('#address').val($(this).data('address'));
        $('#mobile_number_1').val($(this).data('mobile1'));
        $('#mobile_number_2').val($(this).data('mobile2'));
        $('#mobile_number_3').val($(this).data('mobile3'));
        $('#email').val($(this).data('email'));
        $('#vat_number').val($(this).data('vatnumber'));
        $('#company_code').val($(this).data('companycode')).prop('readonly', false);
        $('#image_name').val($(this).data('image'));

        var imageUrl = 'uploads/company-logos/' + $(this).data('image');
        $('#logo-preview-show').attr('src', imageUrl);

        $('#is_active').prop('checked', $(this).data('active') == 1);
        $('#is_vat').prop('checked', $(this).data('isvat') == 1);

        if ($('#is_vat').is(':checked')) {
            $('#vat-number-group').show();
        }
        $("#create").hide();
        $('.bs-example-modal-xl').modal('hide');
    });

    // Delete Company Profile
    $(document).on('click', '.delete-company-profile', function (e) {
        e.preventDefault();

        var companyId = $('#company_id').val();
        var companyName = $('#name').val();

        if (!companyId || companyId === "") {
            swal({
                title: "Error!",
                text: "Please select a company profile first.",
                type: "error",
                timer: 2000,
                showConfirmButton: false
            });
            return;
        }

        swal({
            title: "Are you sure?",
            text: "Do you want to delete the company profile '" + companyName + "'?",
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
                    url: 'ajax/php/company-profile.php',
                    type: 'POST',
                    data: {
                        id: companyId,
                        delete: true
                    },
                    dataType: 'JSON',
                    success: function (response) {
                        $('.someBlock').preloader('remove');

                        if (response.status === 'success') {
                            swal({
                                title: "Deleted!",
                                text: "Company Profile has been deleted.",
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

    // Toggle VAT Number field
    $("#is_vat").change(function () {
        if ($(this).is(':checked')) {
            $("#vat-number-group").show();
            $("#vat_number").prop("required", true);
        } else {
            $("#vat-number-group").hide();
            $("#vat_number").val('').prop("required", false);
        }
    });

    // Optional: Trigger on load in case of edit mode
    if ($('#is_vat').is(':checked')) {
        $("#vat-number-group").show();
        $("#vat_number").prop("required", true);
    }


    const previewContainer = document.getElementById("logo-preview");

    $('#logo').on('change', function (e) {
        const file = e.target.files[0];
        if (!file) return;

        $('#logo-preview-show').hide();
        const reader = new FileReader();
        reader.onload = function (event) {
            previewContainer.innerHTML = `<img id="crop-image" src="${event.target.result}" style="max-width: 100%;">`;

            const image = document.getElementById('crop-image');
            image.onload = () => {
                if (cropper) cropper.destroy();
                cropper = new Cropper(image, {
                    aspectRatio: 3,
                    viewMode: 1,
                    autoCropArea: 1,
                    minCropBoxWidth: 600,
                    minCropBoxHeight: 200
                });
            };
        };
        reader.readAsDataURL(file);


    });

    // Crop and Upload (if used separately)
    $('#form-data').submit(function (e) {
        e.preventDefault();

        if (cropper) {
            const canvas = cropper.getCroppedCanvas({ width: 600, height: 200 });

            canvas.toBlob(blob => {
                removeBackground(blob, function (bgRemovedBlob) {
                    const imageUrl = URL.createObjectURL(bgRemovedBlob);
                    const image = new Image();
                    image.src = imageUrl;

                    image.onload = () => {
                        const upscaler = new Upscaler();
                        upscaler.upscale(image).then(enhancedCanvas => {
                            enhancedCanvas.toBlob(enhancedBlob => {
                                const formData = new FormData($('#form-data')[0]);
                                formData.append('logo', enhancedBlob, 'logo.png');
                                formData.append('create', true);

                                $('.someBlock').preloader();

                                $.ajax({
                                    url: 'ajax/php/company-profile.php',
                                    type: 'POST',
                                    data: formData,
                                    cache: false,
                                    contentType: false,
                                    processData: false,
                                    success: function (response) {
                                        $('.someBlock').preloader('remove');
                                        console.log(response);
                                    }
                                });

                            }, 'image/png');
                        });
                    };
                });
            });
        } else {
            swal("Error", "Please upload and crop the logo before saving.", "error");
        }
    });

    // Background remover
    function removeBackground(fileBlob, callback) {
        const formData = new FormData();
        formData.append("image_file", fileBlob);
        formData.append("size", "auto");

        fetch("https://api.remove.bg/v1.0/removebg", {
            method: "POST",
            headers: {
                "X-Api-Key": "XKydp6uaquCcMK8WK7Agga4D" // 🔑 Replace with your key
            },
            body: formData
        })
            .then(res => res.blob())
            .then(callback)
            .catch(err => {
                console.error("Background removal failed", err);
                swal("Error", "Background removal failed.", "error");
            });
    }

    // Alert helpers
    function showError(msg) {
        swal({ title: "Error!", text: msg, type: 'error', timer: 2000, showConfirmButton: false });
    }

    function showSuccess(msg) {
        swal({ title: "Success!", text: msg, type: 'success', timer: 2000, showConfirmButton: false });
    }

});
