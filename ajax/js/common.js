jQuery(document).ready(function () {


    function formatPriceInput(inputField) {
        let inputValue = inputField.value;

        inputValue = inputValue.replace(/[^\d.]/g, '');

        // If the input contains a decimal point, make sure it has only one
        let [integerPart, decimalPart] = inputValue.split('.');
        integerPart = integerPart.replace(/\B(?=(\d{3})+(?!\d))/g, ','); // Add commas for thousands

        if (decimalPart) {
            decimalPart = decimalPart.substring(0, 2); // Limit to 2 decimal places
        }

        // Reconstruct the formatted price
        let formattedPrice = decimalPart ? `${integerPart}.${decimalPart}` : integerPart;

        inputField.value = formattedPrice;
    }

    document.querySelectorAll('.number-format').forEach(function (inputField) {
        inputField.addEventListener('input', function () {
            formatPriceInput(this);
        });
    });

    //get district name
    $('#province').change(function () {

        $('.someBlock').preloader(); // Show preloader

        var province = $(this).val(); // Get selected province

        $('#district').empty(); // Clear existing district options

        $.ajax({
            url: "ajax/php/district.php",
            type: "POST",
            data: {
                province: province,
                action: 'GET_DISTRICT_BY_PROVINCE'
            },
            dataType: "JSON",
            success: function (jsonStr) {

                $('.someBlock').preloader('remove'); // Remove preloader

                var html = '<option value=""> - Select District - </option>';
                $.each(jsonStr, function (i, data) {
                    html += '<option value="' + data.id + '">' + data.name + '</option>';
                });

                $('#district').html(html); // Set new options
            },
            error: function () {
                $('.someBlock').preloader('remove'); // Remove preloader on error
                swal({
                    title: "Error!",
                    text: "Failed to load districts.",
                    type: 'error',
                    timer: 2000,
                    showConfirmButton: false
                });
            }
        });
    });


});

