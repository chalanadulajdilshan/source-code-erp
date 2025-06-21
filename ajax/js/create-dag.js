jQuery(document).ready(function () {
  function calculateDagItemTotal() {
    const cost = parseFloat($("#casingCost").val()) || 0;
    const qty = parseFloat($("#quantity").val()) || 0;
    const total = cost * qty;
    $("#totalAmount").val(total.toFixed(2));
  }

  function updateSubTotal() {
    let total = 0;
    $("#dagItemsBody").find("input[name='total[]']").each(function () {
      total += parseFloat($(this).val()) || 0;
    });
    $("#finalTotal").val(total.toFixed(2));
    calculateGrandTotal();
  }

  function calculateGrandTotal() {
    const subTotal = parseFloat($("#finalTotal").val()) || 0;
    const discount = parseFloat($("#discount").val()) || 0;
    const discountAmount = subTotal * (discount / 100);
    const grandTotal = subTotal - discountAmount;
    $("#grandTotal").val(grandTotal.toFixed(2));
  }

  function resetDagInputs() {
    $("#vehicleNo, #casingCost, #barcode, #quantity, #totalAmount").val("");
    $("#beltDesign").val("").trigger("change");
  }

  function addDagItem() {
    const vehicleNo = $("#vehicleNo").val().trim();
    const beltDesignId = $("#beltDesign").val();
    const beltDesignText = $("#beltDesign option:selected").text();
    const casingCost = parseFloat($("#casingCost").val()) || 0;
    const barcode = $("#barcode").val().trim();
    const qty = parseFloat($("#quantity").val()) || 0;
    const total = casingCost * qty;

    if (!vehicleNo || !beltDesignId || qty <= 0 || casingCost <= 0) {
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
        <td>${casingCost.toFixed(2)}<input type="hidden" name="casing_cost[]" class="casing_cost" value="${casingCost}"></td>
        <td>${barcode}<input type="hidden" name="barcode[]" class="barcode" value="${barcode}"></td>
        <td>${qty}<input type="hidden" name="qty[]" class="qty" value="${qty}"></td>
        <td>${total.toFixed(2)}<input type="hidden" name="total[]" class="total_amount" value="${total}"></td>
        <td><button type="button" class="btn btn-danger btn-sm remove-item">Remove</button></td>
      </tr>
    `);

    $("#dagItemsBody").append(newRow);
    resetDagInputs();
    updateSubTotal();
    $("#vehicleNo").focus();
  }

  $("#addDagItemBtn").click(function (e) {
    e.preventDefault();
    addDagItem();
  });

  $("#discount").on("input", calculateGrandTotal);
  $("#casingCost, #quantity").on("input", calculateDagItemTotal);

  $("#vehicleNo, #beltDesign, #casingCost, #barcode, #quantity").on("keydown", function (e) {
    if (e.key === "Enter") {
      e.preventDefault();
      addDagItem();
    }
  });

  $(document).on("click", ".remove-item", function () {
    $(this).closest("tr").remove();
    updateSubTotal();
  });

  $("#update").click(function (event) {
    event.preventDefault();
    if (!$("#ref_no").val() || !$("#received_date").val() || !$("#delivery_date").val() || !$("#customer_request_date").val()) {
      swal("Error!", "Please fill all required fields.", "error");
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
        casing_cost: $(this).find(".casing_cost").val(),
        qty: $(this).find(".qty").val(),
        total_amount: $(this).find(".total_amount").val()
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

  $(document).on("click", ".select-dag", function () {
    const data = $(this).data();
    $("#form-data")[0].reset();
    $("#id").val(data.id);
    $("#ref_no").val(data.ref_no);
    $("#department_id").val(data.department_id).trigger("change");
    $("#customer_id").val(data.customer_id).trigger("change");
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
    $("#dagItemsBody").empty();

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
                <td>${parseFloat(item.casing_cost).toFixed(2)}<input type="hidden" name="casing_cost[]" class="casing_cost" value="${item.casing_cost}"></td>
                <td>${item.barcode}<input type="hidden" name="barcode[]" class="barcode" value="${item.barcode}"></td>
                <td>${item.qty}<input type="hidden" name="qty[]" class="qty" value="${item.qty}"></td>
                <td>${parseFloat(item.total_amount).toFixed(2)}<input type="hidden" name="total[]" class="total_amount" value="${item.total_amount}"></td>
                <td><button type="button" class="btn btn-sm btn-danger remove-item">Remove</button></td>
              </tr>`;
            $("#dagItemsBody").append(row);
          });
          updateSubTotal();
        } else {
          swal("Warning!", "No items returned for this DAG.", "warning");
        }
      },
      error: function () {
        swal("Error!", "Failed to load DAG items.", "error");
      },
    });
  });


});
