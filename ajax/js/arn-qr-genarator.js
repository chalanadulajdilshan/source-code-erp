let allItems = [];

$(document).ready(function () {
    $('#searchArnBtn').click(searchArn);
    $('#arnSearch').keypress(function (e) {
        if (e.which === 13) {
            e.preventDefault();
            searchArn();
        }
    });

    $('#generateAllBarcodesBtn').click(generateAllBarcodes);
    $('#printAllBtn').click(function () {
        // Ensure barcodes are fully rendered before printing
        setTimeout(() => {
            window.print();
        }, 300);
    });
});

function searchArn() {
    const arnNumber = $('#arnSearch').val().trim();
    if (!arnNumber) {
        showMessage('Please enter an ARN number', 'error');
        return;
    }

    const tbody = $('#itemTableBody');
    tbody.html('<tr><td colspan="5" class="text-center"><div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div></td></tr>');

    $('#printButtons').addClass('hidden');
    $('#printAllBtn').addClass('hidden');
    $('#barrcodesContainer').empty();

    $.ajax({
        url: "ajax/php/arn-master.php",
        method: 'POST',
        data: {
            arn_number: arnNumber,
            action: 'get_arn_id'
        },
        dataType: 'json'
    })
        .done(function (response) {
            if (response.status === 'success' && response.arn_id) {
                loadItems(response.arn_id);
            } else {
                tbody.html('<tr><td colspan="5" class="text-center text-warning">ARN number not found</td></tr>');
            }
        })
        .fail(function () {
            const arnId = arnNumber.match(/\/(\d+)$/) || arnNumber.match(/\d+/g);
            if (arnId) {
                const id = parseInt(arnId[arnId.length - 1]);
                loadItems(id);
            } else {
                tbody.html('<tr><td colspan="5" class="text-center text-danger">Invalid ARN format</td></tr>');
            }
        });
}

function loadItems(arnId) {
    $.ajax({
        url: "ajax/php/arn-master.php",
        method: 'POST',
        data: {
            arn_id: arnId
        },
        dataType: 'json'
    })
        .done(function (response) {
            const tbody = $('#itemTableBody');
            tbody.empty();

            let items = Array.isArray(response) ? response :
                (response.data ? (Array.isArray(response.data) ? response.data : [response.data]) : []);

            if (items.length > 0) {
                allItems = items;

                items.forEach(item => {
                    const itemCode = item.item_code || item.id || 'N/A';
                    tbody.append(`
                            <tr>
                                <td>${itemCode}</td>
                                <td><input type="number" class="form-control form-control-sm" value="${item.commercial_cost || item.cost || '0.00'}" readonly></td>
                                <td><input type="number" class="form-control form-control-sm" value="${item.received_qty || item.qty || '0'}" readonly></td>
                                <td><input type="number" class="form-control form-control-sm" value="${item.unit_total || item.total || '0.00'}" readonly></td>
                                <td>
                                    <button type="button" class="btn btn-sm btn-info" onclick="printSingle(${item.id})">
                                        <i class="fas fa-print"></i> Print
                                    </button>
                                </td>
                            </tr>
                        `);
                });

                $('#printButtons').removeClass('hidden');
            } else {
                tbody.append('<tr><td colspan="5" class="text-center">No items found for this ARN</td></tr>');
            }
        })
        .fail(function (xhr) {
            let errorMsg = 'Error loading ARN items. Please try again.';
            try {
                const errorResponse = JSON.parse(xhr.responseText);
                if (errorResponse.message) errorMsg = errorResponse.message;
            } catch (e) {
                if (xhr.responseText && xhr.responseText.length < 200) {
                    errorMsg += ' (Server response: ' + xhr.responseText + ')';
                }
            }
            $('#itemTableBody').html(`<tr><td colspan="5" class="text-center text-danger">${errorMsg}</td></tr>`);
        });
}

function generateAllBarcodes() {
    if (allItems.length === 0) {
        showMessage('No items available to generate barcodes.', 'error');
        return;
    }

    const container = document.getElementById('barcodesContainer');
    container.innerHTML = '';

    let totalBarcodes = 0;
    const barcodesToSave = [];

    allItems.forEach(item => {
        const dbId = item.id.toString().padStart(4, '0');
        const qty = parseInt(item.received_qty || item.qty || 1);
        const randomPart = Math.floor(10000000 + Math.random() * 90000000).toString(); // 8 digits

        // Generate barcodes based on quantity
        for (let i = 1; i <= qty; i++) {
            const seq = i.toString().padStart(4, '0'); // 0001, 0002...
            const barcodeId = dbId + randomPart + seq;
            createBarcode(item, container, barcodeId);
            totalBarcodes++;

            // Add barcode data to save
            barcodesToSave.push({
                arn_id: item.arn_id || 0,
                item_id: item.id,
                item_code: item.item_code || '',
                barcode_id: barcodeId,
                commercial_cost: parseFloat(item.commercial_cost || item.cost || 0)
            });
        }
    });

    // Save all generated barcodes to the database
    saveBarcodesToDatabase(barcodesToSave, totalBarcodes);
    $('#printAllBtn').removeClass('hidden');
}

function saveBarcodesToDatabase(barcodes, totalCount) {
    if (!barcodes || barcodes.length === 0) {
        showMessage('No barcodes to save.', 'error');
        return;
    }

    // Show loading message
    const loadingMessage = showMessage('Saving barcodes, please wait...', 'info', 0);

    $.ajax({
        url: 'ajax/php/arn-qr-genaretor.php',
        method: 'POST',
        data: {
            action: 'save_barcodes',
            barcodes: barcodes
        },
        dataType: 'json'
    })
        .done(function (response) {
            if (response.status === 'success') {
                // Remove the loading message
                if (loadingMessage) {
                    loadingMessage.remove();
                }

                // Show success message with count
                const successMsg = `Successfully generated and saved ${response.saved_count || totalCount} barcodes!`;
                if (response.warning) {
                    showMessage(`${successMsg} ${response.warning}`, 'warning');
                } else {
                    showMessage(successMsg, 'success');
                }
            } else {
                throw new Error(response.message || 'Failed to save barcodes');
            }
        })
        .fail(function (xhr, status, error) {
            console.error('Error saving barcodes:', error, xhr.responseText);
            showMessage(`Error saving barcodes: ${error}`, 'error');
        });
}

function printSingle(itemId) {
    const item = allItems.find(i => i.id == itemId);
    if (!item) {
        showMessage('Item not found', 'error');
        return;
    }

    const tempContainer = document.createElement('div');
    tempContainer.className = 'barcode-container';
    tempContainer.style.display = 'none';
    document.body.appendChild(tempContainer);

    const dbId = item.id.toString().padStart(4, '0');
    const qty = parseInt(item.received_qty || item.qty || 1);
    const randomPart = Math.floor(10000000 + Math.random() * 90000000).toString(); // 8 digits

    // Generate barcodes based on quantity for single item
    for (let i = 1; i <= qty; i++) {
        const seq = i.toString().padStart(4, '0');
        const barcodeId = dbId + randomPart + seq;
        createBarcode(item, tempContainer, barcodeId);
    }

    const printWindow = window.open('', '_blank');
    printWindow.document.write(`
    <!DOCTYPE html>
    <html>
    <head>
        <style>
            @media print {
                body {
                    margin: 0;
                    padding: 0;
                    font-family: Arial, sans-serif;
                }
                .barcode-container {
                    background: none;
                    padding: 0;
                    margin: 0;
                    display: block;
                }
                .barcode-item {
                    width: 58mm;
                    height: auto;
                    margin: 0;
                    padding: 2mm;
                    border: none;
                    display: block;
                    page-break-inside: avoid;
                    page-break-after: always;
                    box-shadow: none;
                    border-radius: 0;
                    background: white;
                    text-align: center;
                }
                .barcode-item:last-child {
                    page-break-after: auto;
                }
                .barcode-item svg {
                    width: 50mm;
                    height: 12mm;
                    margin: 1mm 0;
                }
                .barcode-item .barcode-number {
                    font-size: 8px;
                    font-weight: bold;
                    color: #000;
                    font-family: 'Courier New', monospace;
                    letter-spacing: 0.5px;
                    margin: 1mm 0;
                }
            }
            @page {
                size: 58mm auto;
                margin: 2mm;
            }
        </style>
    </head>
    <body>
        ${tempContainer.innerHTML}
    </body>
    </html>
    `);

    printWindow.document.close();
    printWindow.focus();

    // Wait for barcode to render before printing
    setTimeout(() => {
        printWindow.print();
        printWindow.close();
        document.body.removeChild(tempContainer);
    }, 500);
}

function createBarcode(item, container, barcodeId) {
    const barcodeDiv = document.createElement('div');
    barcodeDiv.className = 'barcode-item';

    const safeId = `barcode_${Date.now()}_${Math.random().toString(36).substr(2, 9)}`;

    barcodeDiv.innerHTML = `
        <svg id="${safeId}"></svg>
        <div class="barcode-number">${barcodeId}</div>
    `;

    container.appendChild(barcodeDiv);

    try {
        JsBarcode(`#${safeId}`, barcodeId, {
            format: "CODE128",
            width: 1.5,
            height: 30,
            displayValue: false,
            margin: 0,
            background: "#ffffff",
            lineColor: "#000000"
        });
    } catch (error) {
        console.error('Error generating barcode:', error);
        document.getElementById(safeId).outerHTML = '<div style="font-size:10px;color:red;">Error generating barcode</div>';
    }
}



// Update the showMessage function to return the created element for later removal
function showMessage(message, type = 'info', autoHide = 3000) {
    const alertHtml = `
        <div class="alert alert-${type === 'success' ? 'success' : type === 'error' ? 'danger' : 'info'} 
                    alert-dismissible fade show" role="alert">
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    `;

    const $alert = $(alertHtml).prependTo('.container-fluid');

    // Auto-hide the message after delay if autoHide is greater than 0
    if (autoHide > 0) {
        setTimeout(() => {
            $alert.fadeOut(400, function () {
                $(this).remove();
            });
        }, autoHide);
    }

    return $alert;
}