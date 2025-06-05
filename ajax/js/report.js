jQuery(document).ready(function () {

    //windows loard price control
    loadPriceControlItems();

    $('#brand_id, #category_id, #group_id').on('change', function () {
        loadPriceControlItems();
    });

    function loadPriceControlItems() {
        let brand_id = $('#brand_id').val();
        let category_id = $('#category_id').val();
        let group_id = $('#group_id').val();

        $.ajax({
            url: 'ajax/php/report.php',
            type: 'POST',
            dataType: 'json',
            data: {
                action: 'load_filtered',
                brand_id: brand_id,
                category_id: category_id,
                group_id: group_id
            },
            success: function (data) {
                let tbody = '';
                if (data.length > 0) {
                    $.each(data, function (index, item) {
                        index++
                        tbody += `<tr>
                            <td>${index}</td>
                            <td>${item.code} - ${item.name}</td>
                            <td>${item.note}</td>
                            <td>${parseFloat(item.cost).toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 })}</td>
                            <td>${parseFloat(item.list_price).toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 })}</td>
                            <td>${parseFloat(item.cash_price).toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 })}</td>
                            <td>${parseFloat(item.credit_price).toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 })}</td>
                            <td>${item.cash_discount}</td>
                            <td>${item.credit_discount}</td>
                        </tr>`;
                    });
                } else {
                    tbody = `<tr><td colspan="9" class="text-center text-muted">No items found</td></tr>`;
                }
                $('#priceControl tbody').html(tbody);
            },
            error: function (xhr, status, error) {
                console.error('Error loading items:', error);
                $('#priceControl tbody').html(`<tr><td colspan="9" class="text-danger text-center">Error loading data</td></tr>`);
            }
        });
    }
});
