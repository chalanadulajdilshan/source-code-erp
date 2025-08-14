jQuery(document).ready(function () {
    // Initialize DataTable with server-side processing
    var table = $('#stockTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: "ajax/php/item-master.php",
            type: "POST",
            data: function (d) {
                d.filter = true;
                d.status = 1; // Only show active items
                d.stock_only = true; // Only show items with stock tracking enabled
                d.department_id = $('#filter_department_id').val(); // Get selected department
            },
            dataSrc: function (json) {
                return json.data;
            },
            error: function (xhr) {
                console.error("Server Error Response:", xhr.responseText);
            }
        },
        columns: [
            { data: "code", title: "Item Code" },
            { data: "name", title: "Item Description" },
            { data: "brand", title: "Brand" },
            { data: "pattern", title: "Pattern" },
            { data: "category", title: "Category" },
            {
                data: "invoice_price",
                title: "Cost",
                render: function (data, type, row) {
                    return parseFloat(data).toFixed(2);
                }
            },
            {
                data: "list_price",
                title: "Selling",
                render: function (data, type, row) {
                    return parseFloat(data).toFixed(2);
                }
            },
            {
                data: "discount",
                title: "Dealer Price",
                render: function (data, type, row) {
                    if (row.list_price && data) {
                        const dealerPrice = parseFloat(row.list_price) * (1 - parseFloat(data) / 100);
                        return dealerPrice.toFixed(2);
                    }
                    return '0.00';
                }
            },
            {
                data: "department_stock",
                title: "Quantity",
                render: function (data, type, row) {
                    const departmentId = $('#filter_department_id').val();
                    if (data && data.length > 0) {
                        const stock = data.find(s => s.department_id == departmentId);
                        return stock ? parseFloat(stock.quantity).toFixed(2) : '0.00';
                    }
                    return '0.00';
                }
            },
            {
                data: "status",
                title: "Stock Status",
                render: function (data, type, row) {
                    const departmentId = $('#filter_department_id').val();
                    let quantity = 0;

                    if (row.department_stock && row.department_stock.length > 0) {
                        const stock = row.department_stock.find(s => s.department_id == departmentId);
                        quantity = stock ? parseFloat(stock.quantity) : 0;
                    }

                    const reorderLevel = parseFloat(row.re_order_level) || 0;
                    const isLowStock = quantity <= reorderLevel && quantity > 0;
                    const isOutOfStock = quantity <= 0;

                    let statusText = '';
                    let statusClass = '';

                    if (isOutOfStock) {
                        statusText = 'Out of Stock';
                        statusClass = 'danger';
                    } else if (isLowStock) {
                        statusText = 'Low Stock';
                        statusClass = 'warning';
                    } else {
                        statusText = 'In Stock';
                        statusClass = 'success';
                    }

                    return `<span class="badge bg-soft-${statusClass} font-size-12">${statusText}</span>`;
                },
                orderable: false
            }
        ],
        order: [[1, 'asc']], // Default sort by item name
        lengthMenu: [10, 25, 50, 100],
        pageLength: 25,
        responsive: true,
        language: {
            paginate: {
                previous: "<i class='mdi mdi-chevron-left'>",
                next: "<i class='mdi mdi-chevron-right'>"
            }
        },
        drawCallback: function () {
            $('.dataTables_paginate > .pagination').addClass('pagination-rounded');
        }
    });

    // Department filter change handler
    $('#filter_department_id').on('change', function () {
        table.ajax.reload();
    });

    // Initialize department select2 if it exists
    if ($.fn.select2) {
        $('#filter_department_id').select2({
            placeholder: 'Select Department',
            allowClear: true
        });
    }
});
