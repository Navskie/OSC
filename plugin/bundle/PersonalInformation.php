<div class="row collapse" id="ResellerInformation">
  <div class="col-md-12">
    <div class="card invoices-add-card">
      <div class="card-body">
        <form id="new_PersonalInfoForm" class="invoices-form" enctype="multipart/form-data">
          <div>
            <div class="row">
              <!-- Poid Number -->
              <div class="col-xl-4 col-md-6 col-sm-12 col-12">
                <div class="form-group">
                  <label>Poid Number</label>
                  <input class="form-control" type="text" name="rspoid" value="<?php echo $rspoid ?>" disabled>
                </div>
              </div>

              <!-- Customer Name -->
              <div class="col-xl-4 col-md-6 col-sm-12 col-12">
                <div class="form-group">
                  <label>Customer Name</label>
                  <input class="form-control" type="text" name="new_customer_name" value="<?php echo isset($_SESSION['new_customer_name']) ? $_SESSION['new_customer_name'] : ''; ?>" placeholder="Customer Name">
                </div>
              </div>

              <!-- Email Address -->
              <div class="col-xl-4 col-md-6 col-sm-12 col-12">
                <div class="form-group">
                  <label>Email Address</label>
                  <input class="form-control" type="email" name="new_email" value="<?php echo isset($_SESSION['new_email']) ? $_SESSION['new_email'] : ''; ?>" placeholder="Email Address">
                </div>
              </div>

              <!-- Mobile Number -->
              <div class="col-xl-4 col-md-6 col-sm-12 col-12">
                <div class="form-group">
                  <label>Mobile Number</label>
                  <input class="form-control" type="text" name="new_mobile_number" value="<?php echo isset($_SESSION['new_mobile_number']) ? $_SESSION['new_mobile_number'] : ''; ?>" placeholder="Mobile Number">
                </div>
              </div>

              <!-- Complete Address -->
              <div class="col-xl-4 col-md-6 col-sm-12 col-12">
                <div class="form-group">
                  <label>Complete Address</label>
                  <textarea name="new_address" class="form-control"><?php echo isset($_SESSION['new_address']) ? $_SESSION['new_address'] : ''; ?></textarea>
                </div>
              </div>

              <!-- Image Upload and Preview -->
              <div class="col-xl-4 col-md-6 col-sm-12 col-12">
                <div class="row">
                  <div class="col-4 d-flex justify-content-center align-items-center w-100 mb-3">
                    <!-- Image Displayed Here -->
                    <img src="assets/img/replace.png" id="new_uploadedImg" class="img-fluid rounded" width="200" />
                  </div>
                  <div class="col-8 d-flex justify-content-center align-items-center w-100">
                    <div class="form-group students-up-files">
                      <div class="uplod">
                        <label class="file-upload image-upbtn mb-0">
                          Upload Image Address <input type="file" id="new_fileInput">
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
                  <select name="new_delivery_option" class="form-control">
                    <option value="">Select Option</option>
                    <option value="Post Office Pick Up" <?php echo (isset($_SESSION['new_delivery_option']) && $_SESSION['new_delivery_option'] == 'Post Office Pick Up') ? 'selected' : ''; ?>>Post Office Pick Up</option>
                    <option value="Direct Mail Box" <?php echo (isset($_SESSION['new_delivery_option']) && $_SESSION['new_delivery_option'] == 'Direct Mail Box') ? 'selected' : ''; ?>>Direct Mail Box</option>
                  </select>
                </div>
              </div>

              <!-- Country -->
              <div class="col-xl-4 col-md-6 col-sm-12 col-12">
                <div class="form-group">
                  <label>Country</label>
                  <select name="new_country" id="new_country" class="form-control">
                    <option value="PHILIPPINES" <?php echo (isset($_SESSION['new_country']) && $_SESSION['new_country'] == 'PHILIPPINES') ? 'selected' : ''; ?>>PHILIPPINES</option>
                    <option value="KOREA" <?php echo (isset($_SESSION['new_country']) && $_SESSION['new_country'] == 'KOREA') ? 'selected' : ''; ?>>KOREA</option>
                    <option value="TAIWAN" <?php echo (isset($_SESSION['new_country']) && $_SESSION['new_country'] == 'TAIWAN') ? 'selected' : ''; ?>>TAIWAN</option>
                    <option value="JAPAN" <?php echo (isset($_SESSION['new_country']) && $_SESSION['new_country'] == 'JAPAN') ? 'selected' : ''; ?>>JAPAN</option>
                    <option value="CANADA" <?php echo (isset($_SESSION['new_country']) && $_SESSION['new_country'] == 'CANADA') ? 'selected' : ''; ?>>CANADA</option>
                    <option value="USA" <?php echo (isset($_SESSION['new_country']) && $_SESSION['new_country'] == 'USA') ? 'selected' : ''; ?>>USA</option>
                    <option value="UNITED ARAB EMIRATES" <?php echo (isset($_SESSION['new_country']) && $_SESSION['new_country'] == 'UNITED ARAB EMIRATES') ? 'selected' : ''; ?>>UNITED ARAB EMIRATES</option>
                    <option value="HONGKONG" <?php echo (isset($_SESSION['new_country']) && $_SESSION['new_country'] == 'HONGKONG') ? 'selected' : ''; ?>>HONGKONG</option>
                  </select>
                </div>
              </div>

              <!-- State -->
              <div class="col-xl-4 col-md-6 col-sm-12 col-12">
                <div class="form-group">
                  <label>State</label>
                  <select name="new_state" id="new_state" class="form-control select2"></select>
                </div>
              </div>

              <?php
              // Check if there are any records in upti_order_list
              $result = $conn->query("SELECT COUNT(*) AS count FROM upti_order_list WHERE ol_poid = '$rspoid'");
              $hasOrders = $result->fetch_assoc()['count'];
              $disabled = ($hasOrders > 0) ? 'disabled' : ''; // Assign 'disabled' only if there are orders
              ?>

              <!-- Save Information Button -->
              <div class="col-xl-4 col-md-6 col-sm-12 col-12">
                <div class="form-group">
                  <button type="submit" id="new_saveBtn" class="btn btn-primary form-control text-white">Save Information</button>
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

  // Country change event
  $("#new_country").change(function () {
    var new_country = $(this).val();

    if (new_country) {
      $.ajax({
        url: "backend/bundle/state.php",
        type: "GET",
        dataType: "json",
        data: { new_country: new_country },
        success: function (data) {
          var newstateDropdown = $("#new_state");
          newstateDropdown.empty();
          newstateDropdown.append('<option value="">Select State</option>');

          $.each(data, function (i, state) {
            newstateDropdown.append(
              '<option value="' +
                state.id +
                '" ' +
                (state.id === "<?php echo isset($_SESSION['new_state']) ? $_SESSION['new_state'] : ''; ?>" 
                  ? "selected"
                  : "") +
                ">" +
                state.text +
                "</option>"
            );
          });

          // Reinitialize select2 after appending options
          newstateDropdown.select2(); // Reinitialize after dynamically updating options
        },
      });
    }
  });

  $("#new_country").trigger("change");

  // Handle form submission via AJAX
  $("#new_PersonalInfoForm").submit(function (e) {  
    e.preventDefault();

    var formData = new FormData(this); 
    var fileInput = $('#new_fileInput')[0]; 

    if (fileInput.files.length > 0) {
      formData.append('image', fileInput.files[0]);
    }

    $.ajax({
      url: 'backend/bundle/save_information',
      type: 'POST',
      data: formData,
      processData: false, 
      contentType: false,
      success: function(response) {
        var result = JSON.parse(response);
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

  // Check for orders
  function NewcheckOrderList() {
    $.ajax({
      url: "backend/bundle/info_button.php",
      type: "GET",
      data: { rspoid: "<?php echo $rspoid; ?>" },
      dataType: "json",
      success: function (response) {
        if (response.hasOrders) {
          $("#new_saveBtn").prop("disabled", true);
        } else {
          $("#new_saveBtn").prop("disabled", false);
        }
      },
    });
  }

  // Check image in localStorage and update the preview
  if (localStorage.getItem('neWuploadedImage')) {
    var savedImage = localStorage.getItem('neWuploadedImage');
    $('#new_uploadedImg').attr('src', savedImage);
  }

  // File input change event
  $('#new_fileInput').change(function (e) {
    var file = e.target.files[0];

    if (file) {
      var reader = new FileReader();

      reader.onload = function (e) {
        var uploadedImage = e.target.result;

        localStorage.setItem('neWuploadedImage', uploadedImage);

        $('#new_uploadedImg').attr('src', uploadedImage);
      };

      reader.readAsDataURL(file); 
    }
  });

  setInterval(NewcheckOrderList, 3000);
  NewcheckOrderList();
});

</script>
