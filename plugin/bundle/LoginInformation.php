<div class="row collapse" id="ResellerLoginInformation">
  <div class="col-md-12">
    <div class="card invoices-add-card">
      <div class="card-body">
        <form id="accountForm" class="invoices-form">
          <div>
            <div class="row align-items-end">

              <!-- Customer Name -->
              <div class="col-xl-4 col-md-6 col-sm-12 col-12">
                <div class="form-group">
                  <label>Username</label>
                  <small id="usernameAvailability" class="fw-bold f-italic"></small>
                  <input class="form-control" type="text" name="username" id="username" value="<?php echo isset($_SESSION['rsusername']) ? $_SESSION['rsusername'] : ''; ?>" placeholder="Username">
                </div>
              </div>

              <!-- Email Address -->
              <div class="col-xl-4 col-md-6 col-sm-12 col-12">
                <div class="form-group">
                  <label>Password</label>
                  <input class="form-control" type="email" name="email" value="123456" readonly>
                </div>
              </div>

              <!-- Save Information Button -->
              <div class="col-xl-4 col-md-6 col-sm-12 col-12">
                <div class="form-group">
                  <button type="submit" id="saveBtn" class="btn btn-primary form-control text-white" style="width: 100%;">Check Account</button>
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
    // Trigger AJAX call when username is changed
    $('#username').on('input', function() {
      var username = $(this).val();
      if(username != '') {
        $.ajax({
          url: 'backend/bundle/check_username', // PHP script to check if username exists
          type: 'POST',
          data: { username: username },
          success: function(response) {
            // If username exists, show a message with red text
            if(response == 'exists') {
              $('#usernameAvailability').text('(Username is already taken)').css('color', 'red');
            } else {
              // If username is available, show a message with green text
              $('#usernameAvailability').text('(Username is available)').css('color', 'green');
            }
          }
        });
      } else {
        $('#usernameAvailability').text('').css('color', ''); // Clear message if input is empty
      }
    });

    // Form submit to save session
    $('#accountForm').on('submit', function(e) {
      e.preventDefault();
      var username = $('#username').val();
      
      if (username != '') {
        $.ajax({
          url: 'backend/bundle/save_username', // PHP script to save username in session
          type: 'POST',
          data: { username: username },
          success: function(response) {
            toastr.success('Username saved in session', 'Success'); // Display success toastr notification
          },
          error: function() {
            toastr.error('There was an error saving the username', 'Error'); // Handle error in saving username
          }
        });
      } else {
        toastr.warning('Please enter a username before submitting.', 'Warning'); // Warning if username is empty
      }
    });
  });
</script>
