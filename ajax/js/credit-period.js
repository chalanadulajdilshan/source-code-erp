jQuery(document).ready(function () {
    // Create Expense Type
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
      } else if (!$("#days").val() || $("#days").val().length === 0) {
        swal({
          title: "Error!",
          text: "Please enter Time Period",
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
          url: "ajax/php/credit-period.php", // Adjust the URL based on your needs
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
                text: "Credit Period added Successfully!",
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
      } else if (!$("#days").val() || $("#days").val().length === 0) {
        swal({
          title: "Error!",
          text: "Please enter Time Period",
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
          url: "ajax/php/credit-period.php",
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
                text: "Credit Period updated Successfully!",
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
  
    // Delete credit
    $(document).on("click", ".delete-credit-period", function (e) {
      e.preventDefault();
  
      var id = $("#id").val();
      var code = $("#code").val();
  
      if (!code || code === "") {
        swal({
          title: "Error!",
          text: "Please select a Credit Period first.",
          type: "error",
          timer: 2000,
          showConfirmButton: false,
        });
        return;
      }
  
      swal(
        {
          title: "Are you sure?",
          text: "Do you want to delete '" + code + "' Credit Period?",
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
              url: "ajax/php/credit-period.php",
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
                    text: "Credit Period has been deleted.",
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
    $(document).on("click", ".select-credit", function () {
      const id = $(this).data("id");
      const code = $(this).data("code");
      const days = $(this).data("days");
      const is_active = $(this).data("is_active");
  
      $("#id").val($(this).data("id"));
      $("#code").val($(this).data("code"));
      $("#days").val($(this).data("days"));
      $("#is_active").prop("checked", is_active == 1);
  
      $("#create").hide();
      $(".bs-example-modal-xl").modal("hide"); // Close the modal
    });
  });
  