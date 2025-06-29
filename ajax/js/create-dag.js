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
    $("#vehicleNo, #barcode, #quantity").val("");
    $("#beltDesign").val("").trigger("change");
  }


  function addDagItem() {
    const vehicleNo = $("#vehicleNo").val().trim();
    const beltDesignId = $("#beltDesign").val();
    const beltDesignText = $("#beltDesign option:selected").text();
    const barcode = $("#barcode").val().trim();
    const qty = parseFloat($("#quantity").val()) || 0;

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

    $("#noDagItemRow").hide();

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
    updateSubTotal(); // Can be simplified or removed if no totals
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

    if (!$("#delivery_date").val().trim()) {
      swal({
        title: "Error!",
        text: "Delivery Date cannot be left empty.",
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

    // ✅ Check if at least one DAG item is added
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

    if (!$("#delivery_date").val().trim()) {
      swal({
        title: "Error!",
        text: "Delivery Date cannot be left empty.",
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
    updateSubTotal();

    $("#vehicleNo").focus();
  });


  $(document).on("click", ".select-dag", function () {
    const data = $(this).data();

    $("#form-data")[0].reset();
    $("#id").val(data.id);
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


});
