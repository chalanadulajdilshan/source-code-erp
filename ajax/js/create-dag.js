jQuery(document).ready(function () {

  function loadDagItemsToTable(items) {
    $("#dagItemsBodyInvoice").empty();

    if (!items.length) {
      $("#dagItemsBodyInvoice").append(`
      <tr id="noDagItemRow">
        <td colspan="6" class="text-center text-muted">No items found</td>
      </tr>`);
      return;
    }

    items.forEach((item) => {
      const price = parseFloat(item.price) || 0;
      const qty = parseFloat(item.qty) || 0;
      const total = price * qty;

      const row = $(`
    <tr class="dag-item-row clickable-row">
      <td>
        ${item.vehicle_no}
        <input type="hidden" class="vehicle_no" value="${item.vehicle_no}">
      </td>
      <td>
        ${item.belt_title}
        <input type="hidden" class="belt_id" value="${item.belt_id}">
      </td>
      <td>
        ${item.barcode}
        <input type="hidden" class="barcode" value="${item.barcode}">
      </td>
      <td>
        ${qty}
        <input type="hidden" class="qty" value="${qty}">
      </td>
      <td>
        <input type="number" class="form-control form-control-sm price" value="${price}" readonly>
      </td>
      <td>
        <input type="text" class="form-control form-control-sm total_amount" value="${total.toFixed(2)}" readonly>
      </td>
    </tr>
    `);

      // On row click → populate input fields
      row.on("click", function () {
        $("#vehicleNo").val(item.vehicle_no);
        $("#beltDesign").val(item.belt_id).trigger("change");
        $("#barcode").val(item.barcode);
        $("#quantity").val(qty);
        $("#casingCost").val(price);
        $("#vehicleNo").focus();
      });

      $("#dagItemsBodyInvoice").append(row);
    });
  }


  function resetDagInputs() {
    $("#vehicleNo, #barcode, #quantity").val("");
    $("#beltDesign").val("").trigger("change");
  }


  function addDagItem() {
    const vehicleNo = $("#vehicleNo").val().trim();
    const beltDesignId = $("#beltDesign").val();
    const beltDesignText = $("#beltDesign option:selected").text();
    const barcode = $("#barcode").val().trim();
    const qty = parseFloat($("#quantity").val()) || 0;
    const price = parseFloat($("#casingCost").val()) || 0;

    if (!vehicleNo || !beltDesignId || !barcode || qty <= 0) {
      swal("Error!", "Please fill all required fields correctly.", "error");
      return;
    }

    let isDuplicate = false;
    $(".dag-item-row").each(function () {
      if ($(this).find(".vehicle_no").val() === vehicleNo) {
        isDuplicate = true;
        return false;
      }
    });

    if (isDuplicate) {
      swal("Duplicate!", "This vehicle number is already added.", "warning");
      return;
    }



    const newRow = $(`
      <tr class="dag-item-row">
        <td>${vehicleNo}<input type="hidden" name="vehicle_no[]" class="vehicle_no" value="${vehicleNo}"></td>
        <td>${beltDesignText}<input type="hidden" name="belt_design_id[]" class="belt_id" value="${beltDesignId}"></td>
        <td>${barcode}<input type="hidden" name="barcode[]" class="barcode" value="${barcode}"></td>
        <td>${qty}<input type="hidden" name="qty[]" class="qty" value="${qty}"></td>
         
        <td>
          <button type="button" class="btn btn-warning btn-sm edit-item">Edit</button>
          <button type="button" class="btn btn-danger btn-sm remove-item">Remove</button>
        </td>
      </tr>
    `);

    $("#dagItemsBody").append(newRow);
    resetDagInputs();
    $("#noDagItemRow").hide();

    const dagItems = [];
    $(".dag-item-row").each(function () {
      dagItems.push({
        vehicle_no: $(this).find(".vehicle_no").val(),
        belt_title: $(this).find(".belt_id option:selected").text() || $(this).find(".belt_id").val(), // if text not present
        belt_id: $(this).find(".belt_id").val(),
        barcode: $(this).find(".barcode").val(),
        qty: parseFloat($(this).find(".qty").val()) || 0,
        price: parseFloat($(this).find(".casing_cost").val()) || 0,
      });
    });

    loadDagItemsToTable(dagItems);
    $("#vehicleNo").focus();
  }



  $("#addDagItemBtn").click(function (e) {
    e.preventDefault();
    addDagItem();
  });


  $("#vehicleNo, #beltDesign, #casingCost, #barcode, #quantity").on("keydown", function (e) {
    if (e.key === "Enter") {
      e.preventDefault();
      addDagItem();
    }
  });

  $(document).on("click", ".remove-item", function () {
    $(this).closest("tr").remove();

  });

  $("#create").click(function (event) {
    event.preventDefault();

    if (!$("#ref_no").val().trim()) {
      swal({
        title: "Error!",
        text: "Reference Number is required to proceed.",
        type: "error",
        timer: 2000,
        showConfirmButton: false,
      });
      return;
    }

    if (!$("#received_date").val().trim()) {
      swal({
        title: "Error!",
        text: "Please enter the Received Date to continue.",
        type: "error",
        timer: 2000,
        showConfirmButton: false,
      });
      return;
    }

    if (!$("#customer_request_date").val().trim()) {
      swal({
        title: "Error!",
        text: "Customer Request Date is needed for scheduling.",
        type: "error",
        timer: 2000,
        showConfirmButton: false,
      });
      return;
    }

    let dagItems = [];
    $(".dag-item-row").each(function () {
      dagItems.push({
        vehicle_no: $(this).find(".vehicle_no").val(),
        belt_id: $(this).find(".belt_id").val(),
        barcode: $(this).find(".barcode").val(),
        casing_cost: $(this).find(".casing_cost").val(),
        qty: $(this).find(".qty").val(),
        total_amount: $(this).find(".total_amount").val()
      });
    });

    if (dagItems.length === 0) {
      swal({
        title: "Error!",
        text: "Please add at least one DAG item before saving.",
        type: "error",
        timer: 2000,
        showConfirmButton: false,
      });
      return;
    }

    $(".someBlock").preloader();
    const formData = new FormData($("#form-data")[0]);
    formData.append("create", true); // Create flag
    formData.append("dag_items", JSON.stringify(dagItems));

    $.ajax({
      url: "ajax/php/create-dag.php",
      type: "POST",
      data: formData,
      async: false,
      cache: false,
      contentType: false,
      processData: false,
      dataType: "JSON",
      success: function (result) {
        $(".someBlock").preloader("remove");
        if (result.status === "success") {
          swal("Success!", "DAG created successfully!", "success");
          setTimeout(() => {
            window.location.href = `dag-receipt-print.php?id=${result.id}`;
          }, 1500);
        } else {
          swal("Error!", result.message || "Something went wrong while creating.", "error");
        }
      },
    });
  });



  $("#update").click(function (event) {
    event.preventDefault();
    if (!$("#ref_no").val().trim()) {
      swal({
        title: "Error!",
        text: "Reference Number is required to proceed.",
        type: "error",
        timer: 2000,
        showConfirmButton: false,
      });
      return;
    }

    if (!$("#received_date").val().trim()) {
      swal({
        title: "Error!",
        text: "Please enter the Received Date to continue.",
        type: "error",
        timer: 2000,
        showConfirmButton: false,
      });
      return;
    }



    if (!$("#customer_request_date").val().trim()) {
      swal({
        title: "Error!",
        text: "Customer Request Date is needed for scheduling.",
        type: "error",
        timer: 2000,
        showConfirmButton: false,
      });
      return;
    }

    if (!$("#remark").val().trim()) {
      swal({
        title: "Error!",
        text: "Dag Remark added.!",
        type: "error",
        timer: 2000,
        showConfirmButton: false,
      });
      return;
    }


    $(".someBlock").preloader();
    const formData = new FormData($("#form-data")[0]);
    formData.append("update", true);
    formData.append("dag_id", $("#id").val());

    let dagItems = [];
    $(".dag-item-row").each(function () {
      dagItems.push({
        vehicle_no: $(this).find(".vehicle_no").val(),
        belt_id: $(this).find(".belt_id").val(),
        barcode: $(this).find(".barcode").val(),
        qty: $(this).find(".qty").val()
      });

    });
    formData.append("dag_items", JSON.stringify(dagItems));

    $.ajax({
      url: "ajax/php/create-dag.php",
      type: "POST",
      data: formData,
      async: false,
      cache: false,
      contentType: false,
      processData: false,
      dataType: "JSON",
      success: function (result) {
        $(".someBlock").preloader("remove");
        if (result.status === "success") {
          swal("Success!", "DAG updated successfully!", "success");
          setTimeout(() => location.reload(), 2000);
        } else {
          swal("Error!", "Something went wrong while updating.", "error");
        }
      },
    });
  });


  $(document).on("click", ".edit-item", function () {
    const row = $(this).closest("tr");

    $("#vehicleNo").val(row.find(".vehicle_no").val());
    $("#beltDesign").val(row.find(".belt_id").val()).trigger("change");
    $("#barcode").val(row.find(".barcode").val());
    $("#quantity").val(row.find(".qty").val());

    row.remove();

    $("#vehicleNo").focus();
  });


  $(document).on("click", ".select-dag", function () {
    const data = $(this).data();

    $("#id").val(data.id);
    $("#dag_id").val(data.id);
    $("#ref_no").val(data.ref_no);
    $("#department_id").val(data.department_id).trigger("change");
    $("#customer_id").val(data.customer_id).trigger("change");


    $("#customer_code").val(data.customer_code);
    $("#customer_name").val(data.customer_name);

    $("#received_date").val(data.received_date);
    $("#delivery_date").val(data.delivery_date);
    $("#customer_request_date").val(data.customer_request_date);
    $("#dag_company_id").val(data.dag_company_id).trigger("change");
    $("#company_issued_date").val(data.company_issued_date);
    $("#company_delivery_date").val(data.company_delivery_date);
    $("#receipt_no").val(data.receipt_no);
    $("#remark").val(data.remark);
    $("#status").val(data.status);

    $("#create").hide();
    $("#dagModel").modal("hide");
    $("#mainDagModel").modal("hide");

    $("#noDagItemRow").hide();
    $("#invoiceTable").hide();
    $("#dagTableHide").show();
    $("#addItemTable").hide();
    $("#quotationTableHide").hide();



    $("#dagItemsBody").empty();
    $("#print").data("dag-id", data.id);
    $("#print").show();
    $("#update").show();
    $.ajax({
      url: "ajax/php/create-dag.php",
      type: "POST",
      data: { dag_id: data.id },
      dataType: "json",
      success: function (res) {
        if (res.status === "success") {
          const items = res.data;
          items.forEach((item) => {
            const row = `
  <tr class="dag-item-row">
    <td>${item.vehicle_no}<input type="hidden" name="vehicle_no[]" class="vehicle_no" value="${item.vehicle_no}"></td>
    <td>${item.belt_title}<input type="hidden" name="belt_design_id[]" class="belt_id" value="${item.belt_id}"></td>
    <td>${item.barcode}<input type="hidden" name="barcode[]" class="barcode" value="${item.barcode}"></td>
    <td>${item.qty}<input type="hidden" name="qty[]" class="qty" value="${item.qty}"></td>
    <td>
      <button type="button" class="btn btn-warning btn-sm edit-item">Edit</button>
      <button type="button" class="btn btn-sm btn-danger remove-item">Remove</button>
    </td>
  </tr>`;

            $("#dagItemsBody").append(row);

            const price = parseFloat(item.price) || 0;
            const qty = parseFloat(item.qty) || 0;
            const total = price * qty;

            const invoiceRow = `
              <tr class="dag-item-row clickable-row">
                <td>${item.vehicle_no}</td>
                <td>${item.belt_title}</td>
                <td>${item.barcode}</td>
                <td>${qty}</td>
                <td><input type="number" class="form-control form-control-sm price"   value="${price}"  ></td>
                <td><input type="text" class="form-control form-control-sm totalPrice"  value="${total.toFixed(2)}" readonly>
                <input type="hidden" id="dag_item_id" value="${item.id}" />
                </td>
              </tr>`;
            $("#dagItemsBodyInvoice").append(invoiceRow);
            calculateTotals();

          });

        } else {
          swal("Warning!", "No items returned for this DAG.", "warning");
        }
      },
      error: function () {
        swal("Error!", "Failed to load DAG items.", "error");
      },
    });
  });

  $(document).on("click", "#print", function (e) {
    e.preventDefault();

    const dagId = $(this).data("dag-id");
    if (!dagId) {
      swal("Error!", "No DAG selected to print.", "error");
      return;
    }

    // Redirect to print page
    window.open(`dag-receipt-print.php?id=${dagId}`, "_blank");
  });


  function calculateTotals() {
    let subTotal = 0;

    $("#dagItemsBodyInvoice tr").each(function () {
      const price = parseFloat($(this).find('.price').val()) || 0;
      const qty = parseFloat($(this).find("td:eq(3)").text()) || 0;
      const rowTotal = price * qty;


      // Update totalPrice input (using class, not id)
      $(this).find('input.totalPrice').val(rowTotal.toFixed(2));

      subTotal += rowTotal;
    });

    const discountStr = $("#disTotal").val().replace(/,/g, '').trim();
    const discountPercent = parseFloat(discountStr) || 0;
    const discountAmount = (subTotal * discountPercent) / 100;

    const finalTotal = subTotal - discountAmount;

    $("#subTotal").val(subTotal.toFixed(2));
    $("#finalTotal").val(finalTotal.toFixed(2));

    if (finalTotal < subTotal) {
      $("#finalTotal").css("color", "red");
    } else {
      $("#finalTotal").css("color", "");
    }
  }

  // Handle price input changes dynamically
  $(document).on('input', '.price', function () {
    const row = $(this).closest('tr');
    const price = parseFloat($(this).val()) || 0;
    const qty = parseFloat(row.find("td:eq(3)").text()) || 0;

    const total = price * qty;
    row.find('.totalPrice').val(total.toFixed(2));

    // Enable discount input if needed
    $("#disTotal").prop("disabled", false);

    calculateTotals();
  });

  // Discount input triggers recalculation
  $(document).on("input", "#disTotal", function () {
    setTimeout(() => {
      calculateTotals();
    }, 10);
  });



});
