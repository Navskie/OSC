$(document).ready(function () {
  // Load state when the country is selected
  $("#country").change(function () {
    var country = $(this).val(); // Get selected country

    if (country) {
      $.ajax({
        url: "backend/order/state.php",
        type: "GET",
        dataType: "json",
        data: { country: country },
        success: function (data) {
          var stateDropdown = $("#state");
          stateDropdown.empty(); // Clear existing options
          stateDropdown.append('<option value="">Select State</option>');

          // Add new options for states
          $.each(data, function (i, state) {
            // Make sure to select the state that was saved in the session
            stateDropdown.append(
              '<option value="' +
                state.id +
                '" ' +
                (state.id ===
                "<?php echo isset($_SESSION['state']) ? $_SESSION['state'] : ''; ?>"
                  ? "selected"
                  : "") +
                ">" +
                state.text +
                "</option>"
            );
          });

          // Re-initialize select2 for the state dropdown
          stateDropdown.select2();
        },
      });
    }
  });

  // Trigger change to load states for the selected country
  $("#country").trigger("change");

  // Submit the form using AJAX
  $("#personalInfoForm").submit(function (e) {
    e.preventDefault(); // Prevent the form from submitting normally

    var formData = $(this).serialize(); // Serialize the form data

    $.ajax({
      url: "backend/order/information", // Post the form to the same page
      type: "POST",
      data: formData,
      success: function (response) {
        var result = JSON.parse(response);
        if (result.success) {
          // Success Toastr notification
          toastr.success("Information saved successfully!", "Success");
        } else {
          // Error Toastr notification
          toastr.error(
            "Failed to save information. Please try again.",
            "Error"
          );
        }
      },
      error: function () {
        // If there's a request error, show error toastr
        toastr.error("An error occurred. Please try again.", "Error");
      },
    });
  });

  function checkOrderList() {
    $.ajax({
      url: "backend/order/info_button.php",
      type: "GET",
      data: { poid: "<?php echo $poid; ?>" }, // Pass the poid dynamically
      dataType: "json",
      success: function (response) {
        if (response.hasOrders) {
          $("#saveBtn").prop("disabled", true); // Disable button
        } else {
          $("#saveBtn").prop("disabled", false); // Enable button
        }
      },
    });
  }

  // Call function every 5 seconds (adjust as needed)
  setInterval(checkOrderList, 3000);

  // Run once when the page loads
  checkOrderList();
});
