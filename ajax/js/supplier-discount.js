jQuery(document).ready(function () {
    // Create Supplier Discount
    $("#create").click(function (event) {
      event.preventDefault();
  
      // Validation
      if (!$("#code").val() || $("#code").val().length === 0) {
        swal({
          title: "Error!",
          text: "Please enter a Ref No",
          type: "error",
          timer: 2000,
          showConfirmButton: false,
        });
      } else if (!$("#date").val() || $("#date").val().length === 0) {
        swal({
          title: "Error!",
          text: "Please select a date",
          type: "error",
          timer: 2000,
          showConfirmButton: false,
        });
      
      } else if (!$("#suplier_id").val() || $("#suplier_id").val().length === 0) {
        swal({
          title: "Error!",
          text: "Please select a supplier",
          type: "error",
          timer: 2000,
          showConfirmButton: false,
        });
      } else if (!$("#brand_id").val() || $("#brand_id").val().length === 0) {
        swal({
          title: "Error!",
          text: "Please select a brand",
          type: "error",
          timer: 2000,
          showConfirmButton: false,
        });
      } else if (!$("#discount").val() || $("#discount").val().length === 0) {
        swal({
          title: "Error!",
          text: "Please enter a Discount",
          type: "error",
          timer: 2000,
          showConfirmButton: false,
        });
      } else {
        // Preloader start (optional if you use preloader plugin)
        $(".someBlock").preloader();
  
        // Grab all form data
        var formData = new FormData($("#form-data")[0]);
        formData.append("create", true);
  
        $.ajax({
          url: "ajax/php/supplier-discount.php", // Adjust the URL based on your needs
          type: "POST",
          data: formData,
          async: false,
          cache: false,
          contentType: false,
          processData: false,
          success: function (result) {
            // Remove preloader
            $(".someBlock").preloader("remove");
  
            if (result.status === "success") {
              swal({
                title: "Success!",
                text: "Supplier Discount added Successfully!",
                type: "success",
                timer: 2000,
                showConfirmButton: false,
              });
  
              window.setTimeout(function () {
                window.location.reload();
              }, 2000);
            } else if (result.status === "error") {
              swal({
                title: "Error!",
                text: "Something went wrong.",
                type: "error",
                timer: 2000,
                showConfirmButton: false,
              });
            }
          },
        });
      }
      return false;
    });
  
    // Update Page
    $("#update").click(function (event) {
      event.preventDefault();
  
      // Validation
      if (!$("#code").val() || $("#code").val().length === 0) {
        swal({
          title: "Error!",
          text: "Please enter a Ref No",
          type: "error",
          timer: 2000,
          showConfirmButton: false,
        });
      } else if (!$("#date").val() || $("#date").val().length === 0) {
        swal({
          title: "Error!",
          text: "Please select a date",
          type: "error",
          timer: 2000,
          showConfirmButton: false,
        });
      
      } else if (!$("#suplier_id").val() || $("#suplier_id").val().length === 0) {
        swal({
          title: "Error!",
          text: "Please select a supplier",
          type: "error",
          timer: 2000,
          showConfirmButton: false,
        });
      } else if (!$("#brand_id").val() || $("#brand_id").val().length === 0) {
        swal({
          title: "Error!",
          text: "Please select a brand",
          type: "error",
          timer: 2000,
          showConfirmButton: false,
        });
      } else if (!$("#discount").val() || $("#discount").val().length === 0) {
        swal({
          title: "Error!",
          text: "Please enter a Discount",
          type: "error",
          timer: 2000,
          showConfirmButton: false,
        });
      } else {
        // Preloader start (optional if you use preloader plugin)
        $(".someBlock").preloader();
  
        // Grab all form data
        var formData = new FormData($("#form-data")[0]);
        formData.append("update", true);
  
        $.ajax({
          url: "ajax/php/supplier-discount.php",
          type: "POST",
          data: formData,
          async: false,
          cache: false,
          contentType: false,
          processData: false,
          dataType: "JSON",
          success: function (result) {
            // Remove preloader
            $(".someBlock").preloader("remove");
  
            if (result.status == "success") {
              swal({
                title: "Success!",
                text: "Supplier Discount updated Successfully!",
                type: "success",
                timer: 2500,
                showConfirmButton: false,
              });
  
              window.setTimeout(function () {
                window.location.reload();
              }, 2000);
            } else if (result.status === "error") {
              swal({
                title: "Error!",
                text: "Something went wrong.",
                type: "error",
                timer: 2000,
                showConfirmButton: false,
              });
            }
          },
        });
      }
      return false;
    });
  
    // Delete Supplier Discount
    $(document).on("click", ".delete-discount-model", function (e) {
      e.preventDefault();
  
      var id = $("#id").val();
      var name = $("#name").val();
  
      if (!name || name === "") {
        swal({
          title: "Error!",
          text: "Please select a Supplier Discount first.",
          type: "error",
          timer: 2000,
          showConfirmButton: false,
        });
        return;
      }
  
      swal(
        {
          title: "Are you sure?",
          text: "Do you want to delete '" + name + "' Supplier Discount?",
          type: "warning",
          showCancelButton: true,
          confirmButtonColor: "#d33",
          cancelButtonColor: "#6c757d",
          confirmButtonText: "Yes, delete it!",
          cancelButtonText: "Cancel",
          closeOnConfirm: false,
        },
        function (isConfirm) {
          if (isConfirm) {
            $(".someBlock").preloader();
  
            $.ajax({
              url: "ajax/php/supplier-discount.php",
              type: "POST",
              data: {
                id: id,
                delete: true,
              },
              dataType: "json",
              success: function (response) {
                $(".someBlock").preloader("remove");
  
                if (response.status === "success") {
                  swal({
                    title: "Deleted!",
                    text: "Supplier Discount has been deleted.",
                    type: "success",
                    timer: 2000,
                    showConfirmButton: false,
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
                    showConfirmButton: false,
                  });
                }
              },
            });
          }
        }
      );
    });
  
    //remove input field values
    $("#new").click(function (e) {
      e.preventDefault();
  
      // Reset all fields in the form
      $("#form-data")[0].reset();
  
      // Optional: Reset selects to default option (if needed)
      $("#id").prop("selectedIndex", 0);
      $("#create").show();
    });
  
    //model click append value form
    $(document).on("click", ".select-model", function () {
      const id = $(this).data("id");
      const code = $(this).data("code");
      const date = $(this).data("date");
      const discount_id = $(this).data("discount_id");
      const suplier_id = $(this).data("suplier_id");
      const name = $(this).data("name");
      const brand_id = $(this).data("brand_id");
      const discount = $(this).data("discount");
      const is_active = $(this).data("is_active");

      $("#id").val(id);
      $("#code").val(code);
      $("#date").val(date);
      $("#discount_id").val(discount_id);
      $("#suplier_id").val(suplier_id);
      $("#name").val(name);
      $("#brand_id").val(brand_id);
      $("#discount").val(discount);
      $("#is_active").prop("checked", is_active == 1);

      $("#create").hide();
      $("#discountModel").modal("hide"); // Close the modal using correct ID
    });
  });
  
  $(document).on('click', '#supplierTable tbody tr', function () {
    var table = $('#supplierTable').DataTable();
    var data = table.row(this).data();
    if (!data) return;

    // Fill the supplier fields in your form
    $('#suplier_id').val(data.code);
    $('#name').val(data.name);

    // Close the modal
    $('#supplierModal').modal('hide');
});
  
jQuery(document).ready(function () {

   
    var table = $('#supplierTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: "ajax/php/supplier-discount.php",
            type: "POST",
            data: function (d) {
                d.filter = true;
                d.supplier_only = true; // Add a flag to filter suppliers only
                d.category = [2, 3]; // Only show Supplier or Both
            },
            dataSrc: function (json) {
                return json.data;
            },
            error: function (xhr) {
                console.error("Server Error Response:", xhr.responseText);
            }
        },
        columns: [
            { data: "id", title: "#ID" },
            { data: "code", title: "Code" },
            { data: "name", title: "Name" },
            { data: "mobile_number", title: "Mobile" },
            { data: "email", title: "Email" },
            { data: "category", title: "Category" },
            { data: "province", title: "Province" },
            { data: "credit_limit", title: "Credit Limit" },
            { data: "vat_no", title: "Is Vat" },
            { data: "status_label", title: "Status" }
        ],
        order: [[0, 'desc']],
        pageLength: 100
    });
});
