<!-- Auto Free Modal -->
<div class="modal fade" id="autoFreeModal" tabindex="-1" aria-labelledby="autoFreeModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="autoFreeModalLabel">Free Items</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="autoFreeForm">
          <div class="mb-3">
            <label for="mainItemCode" class="form-label">Choose Main Items</label>
            <select name="mainItem" class="form-control" id="mainItemCode">
              <option selected disabled>Choose main code</option>
              <?php
                $autoFree = mysqli_query($conn, "SELECT ol_code, ol_desc FROM upti_order_list INNER JOIN upti_code ON ol_code = code_name WHERE code_category = 'AUTO FREE' AND ol_poid = '$poid'");
                foreach ($autoFree as $dataFree) {
              ?>
                <option value="<?php echo $dataFree['ol_code'] ?>"><?php echo $dataFree['ol_code'].' - '.$dataFree['ol_desc'] ?></option>
              <?php 
                }
              ?>
            </select>
          </div>

          <div class="mb-3">
            <label>Free Items</label>
            <select name="freeItem" id="free" class="form-control select2"></select>
          </div>

          <button type="submit" class="btn btn-success">Add Item</button>
        </form>
      </div>
    </div>
  </div>
</div>

<script>
$(document).ready(function() {
  // Initialize select2 for Free Items dropdown
  $('#free').select2({
    placeholder: "Select Free Item",
    width: '100%',
    dropdownParent: $('#autoFreeModal') // Ensures select2 works inside Bootstrap modal
  });

  // When the main item code is selected
  $('#mainItemCode').on("change", function() {
    var mainItemCode = $(this).val();
    console.log("Selected Main Item Code:", mainItemCode); // Debugging

    // Reset the Free Items dropdown
    $('#free').empty().trigger('change');

    // Make the AJAX request to fetch the free items
    $.ajax({
      url: "backend/order/free",
      type: "GET",
      data: { maincode: mainItemCode }, 
      dataType: "json",
      success: function(response) {
        console.log("AJAX Response Data:", response); // Debugging

        if (response.status === 'success' && response.data.length > 0) {
          var freeOptions = '<option value="" disabled selected>Select Free Item</option>'; // Placeholder

          // Loop through response data and append options
          response.data.forEach(function(item) {
            freeOptions += `<option value="${item.freecode}">${item.freecode} - ${item.freedesc}</option>`;
          });

          // Append the new options and refresh select2
          $('#free').html(freeOptions).trigger('change');

          console.log("Updated Free Items Dropdown");
        } else {
          toastr.warning("No free items found for this main code.");
        }
      },
      error: function(xhr, status, error) {
        console.error("AJAX Error:", error);
        toastr.error("Failed to fetch free items.");
      }
    });
  });

  // Handle form submission
  $("#autoFreeForm").on("submit", function(e) {
    e.preventDefault(); // Prevent default form submission

    var mainItem = $("#mainItemCode").val();
    var freeItem = $("#free").val();

    // Validate input before sending
    if (!mainItem || !freeItem) {
      toastr.error("Please select both main item and free item!");
      return;
    }

    $.ajax({
      url: "backend/order/freeProcess", 
      type: "POST",
      data: $(this).serialize(), // Serialize form data
      dataType: "json",
      success: function(response) {
        if (response.status === "success") {
          toastr.success(response.message);

          // Hide the modal automatically after success
          $("#autoFreeModal").modal("hide");

          // Reset the form
          $("#autoFreeForm")[0].reset();

          // Reset select2 dropdown
          $("#free").val(null).trigger("change");

          // Refresh the table (use DataTable API or AJAX reload method)
          $("#orderTable").DataTable().ajax.reload(null, false); // Replace 'orderTable' with your actual table ID
        } else {
          toastr.error(response.message);
        }
      },
      error: function() {
        toastr.error("Error adding free item. Please try again.");
      }
    });
  });
});
</script>
