<div class="row collapse" id="PersonalInformation">
  <div class="col-md-12">
    <div class="card invoices-add-card">
      <div class="card-body">
        <form id="personalInfoForm" class="invoices-form">
          <div>
            <div class="row">
              <!-- Poid Number -->
              <div class="col-xl-4 col-md-6 col-sm-12 col-12">
                <div class="form-group">
                  <label>Poid Number</label>
                  <input class="form-control" type="text" name="poid" value="<?php echo $poid ?>" disabled>
                </div>
              </div>

              <!-- Customer Name -->
              <div class="col-xl-4 col-md-6 col-sm-12 col-12">
                <div class="form-group">
                  <label>Customer Name</label>
                  <input class="form-control" type="text" name="customer_name" value="<?php echo isset($_SESSION['customer_name']) ? $_SESSION['customer_name'] : ''; ?>" placeholder="Customer Name">
                </div>
              </div>

              <!-- Email Address -->
              <div class="col-xl-4 col-md-6 col-sm-12 col-12">
                <div class="form-group">
                  <label>Email Address</label>
                  <input class="form-control" type="email" name="email" value="<?php echo isset($_SESSION['email']) ? $_SESSION['email'] : ''; ?>" placeholder="Email Address">
                </div>
              </div>

              <!-- Mobile Number -->
              <div class="col-xl-4 col-md-6 col-sm-12 col-12">
                <div class="form-group">
                  <label>Mobile Number</label>
                  <input class="form-control" type="text" name="mobile_number" value="<?php echo isset($_SESSION['mobile_number']) ? $_SESSION['mobile_number'] : ''; ?>" placeholder="Mobile Number">
                </div>
              </div>

              <!-- Complete Address -->
              <div class="col-xl-8 col-md-6 col-sm-12 col-12">
                <div class="form-group">
                  <label>Complete Address</label>
                  <textarea name="address" class="form-control"><?php echo isset($_SESSION['address']) ? $_SESSION['address'] : ''; ?></textarea>
                </div>
              </div>

              <!-- Delivery Option -->
              <div class="col-xl-4 col-md-6 col-sm-12 col-12">
                <div class="form-group">
                  <label>Delivery Option</label>
                  <select name="delivery_option" class="form-control">
                    <option value="Post Office Pick Up" <?php echo (isset($_SESSION['delivery_option']) && $_SESSION['delivery_option'] == 'Post Office Pick Up') ? 'selected' : ''; ?>>Post Office Pick Up</option>
                    <option value="Direct Mail Box" <?php echo (isset($_SESSION['delivery_option']) && $_SESSION['delivery_option'] == 'Direct Mail Box') ? 'selected' : ''; ?>>Direct Mail Box</option>
                  </select>
                </div>
              </div>

              <!-- Country -->
              <div class="col-xl-4 col-md-6 col-sm-12 col-12">
                <div class="form-group">
                  <label>Country</label>
                  <select name="country" id="country" class="form-control">
                    <option value="PHILIPPINES" <?php echo (isset($_SESSION['country']) && $_SESSION['country'] == 'PHILIPPINES') ? 'selected' : ''; ?>>PHILIPPINES</option>
                    <option value="KOREA" <?php echo (isset($_SESSION['country']) && $_SESSION['country'] == 'KOREA') ? 'selected' : ''; ?>>KOREA</option>
                    <option value="TAIWAN" <?php echo (isset($_SESSION['country']) && $_SESSION['country'] == 'TAIWAN') ? 'selected' : ''; ?>>TAIWAN</option>
                    <option value="JAPAN" <?php echo (isset($_SESSION['country']) && $_SESSION['country'] == 'JAPAN') ? 'selected' : ''; ?>>JAPAN</option>
                    <option value="CANADA" <?php echo (isset($_SESSION['country']) && $_SESSION['country'] == 'CANADA') ? 'selected' : ''; ?>>CANADA</option>
                    <option value="USA" <?php echo (isset($_SESSION['country']) && $_SESSION['country'] == 'USA') ? 'selected' : ''; ?>>USA</option>
                    <option value="UNITED ARAB EMIRATES" <?php echo (isset($_SESSION['country']) && $_SESSION['country'] == 'UNITED ARAB EMIRATES') ? 'selected' : ''; ?>>UNITED ARAB EMIRATES</option>
                    <option value="HONGKONG" <?php echo (isset($_SESSION['country']) && $_SESSION['country'] == 'HONGKONG') ? 'selected' : ''; ?>>HONGKONG</option>
                  </select>
                </div>
              </div>

              <div class="col-xl-4 col-md-6 col-sm-12 col-12">
                <div class="form-group">
                    <label>State</label>
                    <select name="state" id="state" class="form-control select2"></select>
                </div>
            </div>

            <?php
              // Check if there are any records in upti_order_list
              $result = $conn->query("SELECT COUNT(*) AS count FROM upti_order_list WHERE ol_poid = '$poid'");
              $hasOrders = $result->fetch_assoc()['count'] > 0;
              ?>

              <!-- Save Information Button -->
              <div class="col-xl-4 col-md-6 col-sm-12 col-12">
                  <div class="form-group">
                      <button type="submit" id="saveBtn" class="btn btn-primary form-control text-white hideBtn"
                          <?= $hasOrders ? 'disabled' : ''; ?>>Save Information</button>
                  </div>
              </div>


            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<script>
$(document).ready(function() {
    // Load state when the country is selected
    $('#country').change(function() {
        var country = $(this).val(); // Get selected country

        if (country) {
            $.ajax({
                url: 'backend/order/state.php',
                type: 'GET',
                dataType: 'json',
                data: { country: country },
                success: function(data) {
                    var stateDropdown = $('#state');
                    stateDropdown.empty();  // Clear existing options
                    stateDropdown.append('<option value="">Select State</option>');

                    // Add new options for states
                    $.each(data, function(i, state) {
                        // Make sure to select the state that was saved in the session
                        stateDropdown.append('<option value="' + state.id + '" ' + (state.id === "<?php echo isset($_SESSION['state']) ? $_SESSION['state'] : ''; ?>" ? 'selected' : '') + '>' + state.text + '</option>');
                    });

                    // Re-initialize select2 for the state dropdown
                    stateDropdown.select2();
                }
            });
        }
    });

    // Trigger change to load states for the selected country
    $('#country').trigger('change');

    // Submit the form using AJAX
    $('#personalInfoForm').submit(function(e) {
        e.preventDefault();  // Prevent the form from submitting normally

        var formData = $(this).serialize();  // Serialize the form data

        $.ajax({
            url: 'backend/order/information',  // Post the form to the same page
            type: 'POST',
            data: formData,
            success: function(response) {
                var result = JSON.parse(response);
                if (result.success) {
                    // Success Toastr notification
                    toastr.success("Information saved successfully!", "Success");
                } else {
                    // Error Toastr notification
                    toastr.error("Failed to save information. Please try again.", "Error");
                }
            },
            error: function() {
                // If there's a request error, show error toastr
                toastr.error("An error occurred. Please try again.", "Error");
            }
        });
    });


      function checkOrderList() {
          $.ajax({
              url: 'backend/order/info_button.php',
              type: 'GET',
              data: { poid: '<?php echo $poid; ?>' }, // Pass the poid dynamically
              dataType: 'json',
              success: function (response) {
                  if (response.hasOrders) {
                      $("#saveBtn").prop("disabled", true); // Disable button
                  } else {
                      $("#saveBtn").prop("disabled", false); // Enable button
                  }
              }
          });
      }

      // Call function every 5 seconds (adjust as needed)
      setInterval(checkOrderList, 500);

      // Run once when the page loads
      checkOrderList();


});
</script>
