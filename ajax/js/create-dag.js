jQuery(document).ready(function () {

    // Create Dag
    $("#create").click(function (event) {
      event.preventDefault();
  
      // Validation
      if (!$("#code").val() || $("#code").val().length === 0) {
        swal({
          title: "Error!",
          text: "Please enter a Invoice No",
          type: "error",
          timer: 2000,
          showConfirmButton: false,
        });
      } else if (!$("#date").val() || $("#date").val().length === 0) {
        swal({
          title: "Error!",
          text: "Please enter a Date",
          type: "error",
          timer: 2000,
          showConfirmButton: false,
        });
      } else if (!$("#casing_cost").val() || $("#casing_cost").val().length === 0) {
        swal({
          title: "Error!",
          text: "Please enter a Casing Cost",
          type: "error",
          timer: 2000,
          showConfirmButton: false,
        });
      } else if (!$("#type").val() || $("#type").val().length === 0) {
        swal({
          title: "Error!",
          text: "Please select a type",
          type: "error",
          timer: 2000,
          showConfirmButton: false,
        });
      } else if (!$("#size").val() || $("#size").val().length === 0) {
        swal({
          title: "Error!",
          text: "Please select a size",
          type: "error",
          timer: 2000,
          showConfirmButton: false,
        });
      } else if (!$("#make").val() || $("#make").val().length === 0) {
        swal({
          title: "Error!",
          text: "Please Select make",
          type: "error",
          timer: 2000,
          showConfirmButton: false,
        });
      } else if (!$("#belt_design").val() || $("#belt_design").val().length === 0) {
        swal({
          title: "Error!",
          text: "Please Select Belt Design ",
          type: "error",
          timer: 2000,
          showConfirmButton: false,
        });
      } else if (!$("#job_no").val() || $("#job_no").val().length === 0) {
        swal({
          title: "Error!",
          text: "Please Enter job_no",
          type: "error",
          timer: 2000,
          showConfirmButton: false,
        });
      } else if (!$("#serial_no").val() || $("#serial_no").val().length === 0) {
        swal({
          title: "Error!",
          text: "Please enter Serial No",
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
          url: "ajax/php/create-dag.php", // Adjust the URL based on your needs
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
                text: "Dag added Successfully!",
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
          text: "Please enter a Invoice No",
          type: "error",
          timer: 2000,
          showConfirmButton: false,
        });
      } else if (!$("#date").val() || $("#date").val().length === 0) {
        swal({
          title: "Error!",
          text: "Please enter a Date",
          type: "error",
          timer: 2000,
          showConfirmButton: false,
        });
      } else if (!$("#casing_cost").val() || $("#casing_cost").val().length === 0) {
        swal({
          title: "Error!",
          text: "Please enter a Casing Cost",
          type: "error",
          timer: 2000,
          showConfirmButton: false,
        });
      } else if (!$("#type").val() || $("#type").val().length === 0) {
        swal({
          title: "Error!",
          text: "Please select a type",
          type: "error",
          timer: 2000,
          showConfirmButton: false,
        });
      } else if (!$("#size").val() || $("#size").val().length === 0) {
        swal({
          title: "Error!",
          text: "Please select a size",
          type: "error",
          timer: 2000,
          showConfirmButton: false,
        });
      } else if (!$("#make").val() || $("#make").val().length === 0) {
        swal({
          title: "Error!",
          text: "Please Select make",
          type: "error",
          timer: 2000,
          showConfirmButton: false,
        });
      } else if (!$("#belt_design").val() || $("#belt_design").val().length === 0) {
        swal({
          title: "Error!",
          text: "Please Select Belt Design ",
          type: "error",
          timer: 2000,
          showConfirmButton: false,
        });
      } else if (!$("#job_no").val() || $("#job_no").val().length === 0) {
        swal({
          title: "Error!",
          text: "Please Enter job_no",
          type: "error",
          timer: 2000,
          showConfirmButton: false,
        });
      } else if (!$("#serial_no").val() || $("#serial_no").val().length === 0) {
        swal({
          title: "Error!",
          text: "Please enter Serial No",
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
          url: "ajax/php/create-dag.php",
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
                text: "Dag updated Successfully!",
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
  
    // Delete Dag
    $(document).on("click", ".delete-dag", function (e) {
      e.preventDefault();
  
      var id = $("#id").val();

      if (!id || id === "" || id === "0" || parseInt(id) <= 0) {
        swal({
          title: "Error!",
          text: "Please select a Dag first.",
          type: "error",
          timer: 3000,
          showConfirmButton: false,
        });
        return;
      }
  
      swal(
        {
          title: "Are you sure?",
          text: "Do you want to delete Dag?",
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
              url: "ajax/php/create-dag.php",
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
                    text: "Dag has been deleted.",
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
    $(document).on("click", ".select-dag", function () {
      const id = $(this).data("id");
      const code = $(this).data("code");
      const department_id = $(this).data("department_id");
      const date = $(this).data("date");
      const customer_id = $(this).data("customer_id");
      const customer_name = $(this).data("customer_name");
      const casing_cost = $(this).data("casing_cost");
      const type = $(this).data("type");
      const size = $(this).data("size");
      const make = $(this).data("make");
      const belt_design = $(this).data("belt_design");
      const job_no = $(this).data("job_no");
      const serial_no = $(this).data("serial_no");
      const warranty = $(this).data("warranty");
      const remark = $(this).data("remark");

      
  
      $("#id").val($(this).data("id"));
      $("#code").val($(this).data("code"));
      $("#department_id").val($(this).data("department_id"));
      $("#date").val($(this).data("date"));
      $("#customer_id").val($(this).data("customer_id"));
      $("#customer_name").val(customer_name);
      $("#casing_cost").val($(this).data("casing_cost"));
      $("#type").val($(this).data("type"));
      $("#size").val($(this).data("size"));
      $("#make").val($(this).data("make"));
      $("#belt_design").val($(this).data("belt_design"));
      $("#job_no").val($(this).data("job_no"));
      $("#serial_no").val($(this).data("serial_no"));
      $("#warranty").val($(this).data("warranty"));
      $("#remark").val($(this).data("remark"));
  
      $("#create").hide();
      $(".bs-example-modal-xl").modal("hide"); // Close the modal
    });

});
  