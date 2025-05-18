jQuery(document).ready(function () {
  // Create Country
  $("#create").click(function (event) {
    event.preventDefault();

    // Validation (only validate name)
    if (!$("#name").val() || $("#name").val().length === 0) {
      swal({
        title: "Error!",
        text: "Please enter country name",
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
        url: "ajax/php/country.php", // Adjust the URL based on your needs
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
              text: "Country added successfully!",
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

  // Update Country
  $("#update").click(function (event) {
    event.preventDefault();

    // Validation (only validate name)
    if (!$("#name").val() || $("#name").val().length === 0) {
      swal({
        title: "Error!",
        text: "Please enter country name",
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
        url: "ajax/php/country.php",
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
              text: "Country updated successfully!",
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

  // Remove input field values
  $("#new").click(function (e) {
    e.preventDefault();

    // Reset all fields in the form
    $("#form-data")[0].reset();

    $("#create").show();
  });

  // Model click append value form
  $(document).on("click", ".select-country", function () {
    $("#id").val($(this).data("id")); // set hidden id
    $("#code").val($(this).data("code")); // set hidden country_id
    $("#name").val($(this).data("name"));

    if ($(this).data("active") == 1) {
      $("#is_active").prop("checked", true);
    } else {
      $("#is_active").prop("checked", false);
    }

    $("#create").hide();
    $(".bs-example-modal-xl").modal("hide"); // Close the modal
  });

  // Delete Country
  $(document).on("click", ".delete-country", function (e) {
    e.preventDefault();

    var id = $("#id").val();
    var name = $("#name").val();

    if (!name || name === "") {
      // Show an error message if no country is selected
      swal({
        title: "Error!",
        text: "Please select a country first.",
        type: "error",
        timer: 2000,
        showConfirmButton: false,
      });
      return; // Stop the deletion process if no country is selected
    }
    swal(
      {
        title: "Are you sure?",
        text: "Do you want to delete country '" + name + "'?",
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
          // Optional: Show preloader
          $(".someBlock").preloader();

          $.ajax({
            url: "ajax/php/country.php",
            type: "POST",
            data: {
              id: id,
              delete: true,
            },
            dataType: "JSON",
            success: function (response) {
              $(".someBlock").preloader("remove");

              if (response.status === "success") {
                swal({
                  title: "Deleted!",
                  text: "Country has been deleted.",
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
});
