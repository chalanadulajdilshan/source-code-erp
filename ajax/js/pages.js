jQuery(document).ready(function () {
  // Create Page
  $("#create").click(function (event) {
    event.preventDefault();

    // Validation
    if (!$("#page_category").val() || $("#page_category").val().length === 0) {
      swal({
        title: "Error!",
        text: "Please select a Page Category",
        type: "error",
        timer: 2000,
        showConfirmButton: false,
      });
    } else if (!$("#page_name").val() || $("#page_name").val().length === 0) {
      swal({
        title: "Error!",
        text: "Please enter a Page Name",
        type: "error",
        timer: 2000,
        showConfirmButton: false,
      });
    } else if (!$("#page_url").val() || $("#page_url").val().length === 0) {
      swal({
        title: "Error!",
        text: "Please enter the Page Url",
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
        url: "ajax/php/pages.php", // Adjust the URL based on your needs
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
              text: "Page added Successfully!",
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
    if (!$("#page_name").val() || $("#page_name").val().length === 0) {
      swal({
        title: "Error!",
        text: "Please enter a Page Name",
        type: "error",
        timer: 2000,
        showConfirmButton: false,
      });
    } else if (!$("#page_url").val() || $("#page_url").val().length === 0) {
      swal({
        title: "Error!",
        text: "Please enter the Page Url",
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
        url: "ajax/php/pages.php",
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
              text: "Page updated Successfully!",
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
  $(document).on("click", ".select-pages", function () {
    $("#page_id").val($(this).data("id"));
    $("#page_category").val($(this).data("category"));
    $("#page_name").val($(this).data("name"));
    $("#page_url").val($(this).data("url"));

    $("#create").hide();
    $(".bs-example-modal-xl").modal("hide"); // Close the modal
  });

  //show sub category section 
  $('#page_category').on('change', function () {
    const selectedVal = $(this).val();

    if (selectedVal === '4') {
      $('#sub_page_category').closest('.col-md-3').show();
    } else {
      $('#sub_page_category').closest('.col-md-3').hide();
      $('#sub_page_category').val(''); // reset value if hidden
    }
  });

});
