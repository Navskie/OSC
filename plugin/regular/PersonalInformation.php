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
              <div class="col-xl-4 col-md-6 col-sm-12 col-12">
                <div class="form-group">
                  <label>Complete Address</label>
                  <textarea name="address" class="form-control"><?php echo isset($_SESSION['address']) ? $_SESSION['address'] : ''; ?></textarea>
                </div>
              </div>

              <div class="col-xl-4 col-md-6 col-sm-12 col-12">
                <div class="row">
                  <div class="col-4 d-flex justify-content-center align-items-center w-100 mb-3">
                    <img src="assets/img/replace.png" alt="image" id="uploadedImg" class="img-fluid rounded" width="200" />
                  </div>
                  <div class="col-8 d-flex justify-content-center align-items-center w-100">
                    <div class="form-group students-up-files">
                      <div class="uplod">
                        <label class="file-upload image-upbtn mb-0">
                          Upload Image Address <input type="file" id="fileInput">
                        </label>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Delivery Option -->
              <div class="col-xl-4 col-md-6 col-sm-12 col-12">
                <div class="form-group">
                  <label>Delivery Option</label>
                  <select name="delivery_option" class="form-control">
                    <option value="">Select Option</option>
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
              $hasOrders = $result->fetch_assoc()['count'];
              $disabled = ($hasOrders > 0) ? 'disabled' : ''; // Assign 'disabled' only if there are orders
              ?>

            <!-- Save Information Button -->
            <div class="col-xl-4 col-md-6 col-sm-12 col-12">
              <div class="form-group">
                <button type="submit" id="saveBtn" class="btn btn-primary form-control text-white" 
                  <?php echo $disabled; ?>>Save Information</button>
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
  $(document).ready(function () {
  // Country and State Dropdown
  $("#country").change(function () {
    var country = $(this).val();

    if (country) {
      $.ajax({
        url: "backend/order/state.php",
        type: "GET",
        dataType: "json",
        data: { country: country },
        success: function (data) {
          var stateDropdown = $("#state");
          stateDropdown.empty();
          stateDropdown.append('<option value="">Select State</option>');

          $.each(data, function (i, state) {
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

          stateDropdown.select2();
        },
      });
    }
  });

  $("#country").trigger("change");

  // Handle form submission via AJAX
  $("#personalInfoForm").submit(function (e) {
    e.preventDefault();

    var formData = new FormData(this); // Use FormData to collect all form data including files
    var fileInput = $('#fileInput')[0]; // Get the file input element

    if (fileInput.files.length > 0) {
        formData.append('image', fileInput.files[0]); // Append the image file to FormData
    }

    $.ajax({
        url: 'backend/order/information', // Endpoint that handles both form data and image upload
        type: 'POST',
        data: formData,
        processData: false,  // Don't process the data
        contentType: false,  // Don't set content type
        success: function(response) {
            var result = JSON.parse(response);
            // console.log(response);
            if (result.success) {
                toastr.success("Information saved successfully!", "Success");
            } else {
                toastr.error("Failed to save information. Please try again.", "Error");
            }
        },
        error: function() {
            toastr.error("An error occurred. Please try again.", "Error");
        }
    });
  });


  function checkOrderList() {
    $.ajax({
      url: "backend/order/info_button.php",
      type: "GET",
      data: { poid: "<?php echo $poid; ?>" },
      dataType: "json",
      success: function (response) {
        if (response.hasOrders) {
          $("#saveBtn").prop("disabled", true);
        } else {
          $("#saveBtn").prop("disabled", false);
        }
      },
    });
  }

  // Check if there is an image in localStorage and update the image preview
  if (localStorage.getItem('uploadedImage')) {
    var savedImage = localStorage.getItem('uploadedImage');
    $('#uploadedImg').attr('src', savedImage);
  }

  // Handle file input change event
  $('#fileInput').change(function (e) {
    var file = e.target.files[0];

    if (file) {
      var reader = new FileReader();

      reader.onload = function (e) {
        var uploadedImage = e.target.result;

        // Save the uploaded image in localStorage
        localStorage.setItem('uploadedImage', uploadedImage);

        // Update the image preview with the uploaded image
        $('#uploadedImg').attr('src', uploadedImage);
      };

      reader.readAsDataURL(file);  // Read the file as Data URL
    }
  });

  setInterval(checkOrderList, 3000);
  checkOrderList();
});

</script>