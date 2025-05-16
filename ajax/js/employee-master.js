jQuery(document).ready(function () {

    // Create Employee
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
      } else if (!$("#name").val() || $("#name").val().length === 0) {
        swal({
          title: "Error!",
          text: "Please enter a Name",
          type: "error",
          timer: 2000,
          showConfirmButton: false,
        });
      } else if (!$("#full_name").val() || $("#full_name").val().length === 0) {
        swal({
          title: "Error!",
          text: "Please enter a Full Name",
          type: "error",
          timer: 2000,
          showConfirmButton: false,
        });
      } else if (!$("#gender").val() || $("#gender").val().length === 0) {
        swal({
          title: "Error!",
          text: "Please select a Gender",
          type: "error",
          timer: 2000,
          showConfirmButton: false,
        });
      } else if (!$("#birthday").val() || $("#birthday").val().length === 0) {
        swal({
          title: "Error!",
          text: "Please select a Birthday",
          type: "error",
          timer: 2000,
          showConfirmButton: false,
        });
      } else if (!$("#nic_no").val() || $("#nic_no").val().length === 0) {
        swal({
          title: "Error!",
          text: "Please enter a NIC No",
          type: "error",
          timer: 2000,
          showConfirmButton: false,
        });
      } else if (!$("#mobile_1").val() || $("#mobile_1").val().length === 0) {
        swal({
          title: "Error!",
          text: "Please enter a mobile ",
          type: "error",
          timer: 2000,
          showConfirmButton: false,
        });
      } else if (!$("#epf_available").val() || $("#epf_available").val().length === 0) {
        swal({
          title: "Error!",
          text: "Please select a EPF Available",
          type: "error",
          timer: 2000,
          showConfirmButton: false,
        });
      } else if (!$("#department_id").val() || $("#department_id").val().length === 0) {
        swal({
          title: "Error!",
          text: "Please select a Department",
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
          url: "ajax/php/employee-master.php", // Adjust the URL based on your needs
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
                text: "Employee Master added Successfully!",
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
      } else if (!$("#name").val() || $("#name").val().length === 0) {
        swal({
          title: "Error!",
          text: "Please enter a Name",
          type: "error",
          timer: 2000,
          showConfirmButton: false,
        });
      } else if (!$("#full_name").val() || $("#full_name").val().length === 0) {
        swal({
          title: "Error!",
          text: "Please enter a Full Name",
          type: "error",
          timer: 2000,
          showConfirmButton: false,
        });
      } else if (!$("#gender").val() || $("#gender").val().length === 0) {
        swal({
          title: "Error!",
          text: "Please select a Gender",
          type: "error",
          timer: 2000,
          showConfirmButton: false,
        });
      } else if (!$("#birthday").val() || $("#birthday").val().length === 0) {
        swal({
          title: "Error!",
          text: "Please select a Birthday",
          type: "error",
          timer: 2000,
          showConfirmButton: false,
        });
      } else if (!$("#nic_no").val() || $("#nic_no").val().length === 0) {
        swal({
          title: "Error!",
          text: "Please enter a NIC No",
          type: "error",
          timer: 2000,
          showConfirmButton: false,
        });
      } else if (!$("#mobile_1").val() || $("#mobile_1").val().length === 0) {
        swal({
          title: "Error!",
          text: "Please enter a mobile ",
          type: "error",
          timer: 2000,
          showConfirmButton: false,
        });
      } else if (!$("#epf_available").val() || $("#epf_available").val().length === 0) {
        swal({
          title: "Error!",
          text: "Please select a EPF Available",
          type: "error",
          timer: 2000,
          showConfirmButton: false,
        });
      } else if (!$("#department_id").val() || $("#department_id").val().length === 0) {
        swal({
          title: "Error!",
          text: "Please select a Department",
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
          url: "ajax/php/employee-master.php",
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
                text: "Employee Master updated Successfully!",
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
  
    // Delete Employee
    $(document).on("click", ".delete-employee-master", function (e) {
      e.preventDefault();
  
      var id = $("#id").val();
      var name = $("#name").val();
  
      if (!name || name === "") {
        swal({
          title: "Error!",
          text: "Please select a Employee Master first.",
          type: "error",
          timer: 2000,
          showConfirmButton: false,
        });
        return;
      }
  
      swal(
        {
          title: "Are you sure?",
          text: "Do you want to delete '" + name + "' Employee Master?",
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
              url: "ajax/php/employee-master.php",
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
                    text: "Employee Master has been deleted.",
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
    $(document).on("click", ".select-employee", function () {
      const id = $(this).data("id");
      const code = $(this).data("code");
      const name = $(this).data("name");
      const full_name = $(this).data("full_name");
      const gender = $(this).data("gender");
      const birthday = $(this).data("birthday");
      const nic_no = $(this).data("nic_no");
      const mobile_1 = $(this).data("mobile_1");
      const mobile_2 = $(this).data("mobile_2");
      const email = $(this).data("email");
      const epf_available = $(this).data("epf_available");
      const epf_no = $(this).data("epf_no");
      const finger_print_no = $(this).data("finger_print_no");
      const department_id = $(this).data("department_id");
  
      $("#id").val($(this).data("id"));
      $("#code").val($(this).data("code"));
      $("#name").val($(this).data("name"));
      $("#full_name").val($(this).data("full_name"));
      $("#gender").val($(this).data("gender"));
      $("#birthday").val($(this).data("birthday"));
      $("#nic_no").val($(this).data("nic_no"));
      $("#mobile_1").val($(this).data("mobile_1"));
      $("#mobile_2").val($(this).data("mobile_2"));
      $("#email").val($(this).data("email"));
      $("#epf_no").val($(this).data("epf_no"));
      $("#finger_print_no").val($(this).data("finger_print_no"));
      $("#department_id").val($(this).data("department_id"));
      $("#epf_available").prop("checked", epf_available == 1);
  
      $("#create").hide();
      $(".bs-example-modal-xl").modal("hide"); // Close the modal
    });


        // epf-handler.js
    function initializeEpfFields() {
        // Get references to the select and input elements
        const epfAvailableSelect = document.getElementById('epf_available');
        const epfNoInput = document.getElementById('epf_no');
        
        if (!epfAvailableSelect || !epfNoInput) {
            console.error('EPF form elements not found!');
            return;
        }
        
        // Add change event listener to the select element
        epfAvailableSelect.addEventListener('change', function() {
            // If "Available" is selected, enable the EPF No input field
            if (this.value === 'available') {
                epfNoInput.disabled = false;
            } else {
                // Otherwise, disable the input field and clear its value
                epfNoInput.disabled = true;
                epfNoInput.value = '';
            }
        });
        
        // Initialize the state based on current selection
        epfAvailableSelect.dispatchEvent(new Event('change'));
    }

    // Run when DOM is fully loaded
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initializeEpfFields);
    } else {
        // DOM already loaded, run immediately
        initializeEpfFields();
    }

    function handleEpfField() {
        const epfAvailableSelect = $('#epf_available');
        const epfNoInput = $('#epf_no');
        
        epfAvailableSelect.on('change', function() {
            if ($(this).val() === 'available') {
                epfNoInput.prop('disabled', false);
            } else {
                epfNoInput.prop('disabled', true);
                epfNoInput.val('');
            }
        });
        
        // Initialize on page load based on current selection
        epfAvailableSelect.trigger('change');
    }
    
    // Run the handler
    handleEpfField();
    
    // Also handle when data is loaded from the modal
    $(document).on("click", ".select-employee", function() {
        // Wait a brief moment for the data to be populated
        setTimeout(function() {
            // Set the EPF Available dropdown to the correct value
            const epfAvailableValue = $("#epf_available").data("epf_available");
            if (epfAvailableValue === "1" || epfAvailableValue === 1 || epfAvailableValue === "available") {
                $("#epf_available").val("available");
            } else {
                $("#epf_available").val("not_available");
            }
            
            // Trigger the change event to update field status
            $("#epf_available").trigger('change');
        }, 100);
    });

});
  