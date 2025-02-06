<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <meta
      name="viewport"
      content="width=device-width, initial-scale=1.0, user-scalable=0"
    />
    <title>Accounting - Login</title>
    <link rel="shortcut icon" href="assets/img/favicon.png" />
    <link
      href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,400;0,500;0,700;0,900;1,400;1,500;1,700&display=swap"
      rel="stylesheet"
    />
    <link
      rel="stylesheet"
      href="assets/plugins/bootstrap/css/bootstrap.min.css"
    />
    <link rel="stylesheet" href="assets/plugins/feather/feather.css" />
    <link rel="stylesheet" href="assets/plugins/icons/flags/flags.css" />
    <link
      rel="stylesheet"
      href="assets/plugins/fontawesome/css/fontawesome.min.css"
    />

    <link rel="stylesheet" href="assets/plugins/fontawesome/css/all.min.css" />

    <link rel="stylesheet" href="assets/plugins//toastr/toatr.css">

    <link rel="stylesheet" href="assets/css/style.css" />
  </head>
  <body>
    <div class="main-wrapper login-body">
      <div class="login-wrapper">
        <div class="container">
          <div class="loginbox">
            <div class="login-left">
              <img class="img-fluid" src="assets/img/lady.png" alt="Logo" />
            </div>
            <div class="login-right">
              <div class="login-right-wrap">
                <h1>Welcome to Accounting</h1>
                <p class="account-subtitle">
                  Please Signin to access your data.</a>
                </p>
                <h2>Sign in</h2>

                <form method="POST">
                  <div class="form-group">
                    <label>Username <span class="login-danger">*</span></label>
                    <input class="form-control" type="text" />
                    <span class="profile-views"
                      ><i class="fas fa-user-circle"></i
                    ></span>
                  </div>
                  <div class="form-group">
                    <label>Password <span class="login-danger">*</span></label>
                    <input class="form-control pass-input" type="text" />
                    <span
                      class="profile-views feather-eye toggle-password"
                    ></span>
                  </div>
                  <div class="forgotpass">
                    <div class="remember-me">
                      <label
                        class="custom_check mr-2 mb-0 d-inline-flex remember-me"
                      >
                        Remember me
                        <input type="checkbox" name="radio" />
                        <span class="checkmark"></span>
                      </label>
                    </div>

                  </div>
                  <div class="form-group">
                    <button class="btn btn-primary btn-block" type="submit" id="loginSubmit">
                      Login
                    </button>
                  </div>
                </form>

              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <script src="assets/js/jquery-3.6.0.min.js"></script>
    <script src="assets/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/feather.min.js"></script>
    <script src="assets/plugins/toastr/toastr.min.js"></script>
    <!-- <script src="assets/plugins/toastr/toastr.js"></script> -->
    <script src="assets/js/script.js"></script>
    <script>
      $(document).ready(function(){
        var isRTL = $("html").attr("data-textdirection") === "rtl";
        var toastOptions = { closeButton: true, tapToDismiss: false, rtl: isRTL };
        
        function showToast(type, message, title, options = {}) {
          toastr[type](message, title, { ...toastOptions, ...options });
        }

        $('#loginSubmit').on('click', function(e) {
          e.preventDefault();        

          $.ajax({
            url: 'backend/login', // Change this to your actual login URL
            type: 'POST',
            data: { username: $('#username').val(), password: $('#password').val() },
            success: function(response) {
              showToast("success", "Login successful!", "Success");
            },
            error: function(xhr, status, error) {
              showToast("error", "Login failed: " + xhr.responseText, "Error");
            }
          });
        });
      });
    </script>
  </body>
</html>
