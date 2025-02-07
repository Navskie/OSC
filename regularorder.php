<!DOCTYPE html>
<html lang="en">
<?php include_once 'database/conn.php' ?>
  <!-- Head -->
  <?php include_once 'include/head.php' ?>
  <body>
    <div class="main-wrapper">
      <!-- Navbar -->
      <?php include_once 'include/navbar.php' ?>

      <!-- Sidebar -->
      <?php include_once 'include/sidebar.php' ?>

      <div class="page-wrapper">
        <div class="content container-fluid">
          <!-- Buttons -->
          <div class="row">
            <div class="col-12">
              <div class="card invoices-add-card">
                <div class="card-body">
                  <div class="row">
                    <div class="col-xl-4 col-md-6 col-sm-12 col-12 mb-2">
                      <button class="btn btn-primary form-control text-white accordion-btn" type="button" data-bs-toggle="collapse"
                        data-bs-target="#PersonalInformation" aria-expanded="false" aria-controls="PersonalInformation" id="btnPersonalInformation">
                        Personal Information
                      </button>
                    </div>

                    <div class="col-xl-4 col-md-6 col-sm-12 col-12 mb-2">
                      <button class="btn btn-primary form-control text-white accordion-btn" type="button" data-bs-toggle="collapse"
                        data-bs-target="#OrderDetails" aria-expanded="false" aria-controls="OrderDetails" id="btnOrderDetails">
                        Order Details
                      </button>
                    </div>

                    <div class="col-xl-4 col-md-6 col-sm-12 col-12 mb-2">
                      <button class="btn btn-primary form-control text-white accordion-btn" type="button" data-bs-toggle="collapse"
                        data-bs-target="#CheckOut" aria-expanded="false" aria-controls="CheckOut" id="btnCheckOut">
                        Checkout
                      </button>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <!-- Buttons Plugin -->
          <?php include_once 'plugin/regular/personalInformation.php' ?>
          <?php include_once 'plugin/regular/OrderDetails.php' ?>
          <?php include_once 'plugin/regular/CheckOut.php' ?>
          
        </div>
      </div>

    </div>

    <!-- Footer -->
    <?php include_once 'include/footer.php' ?>
    <script src="function/order/mainpage.js"></script>
  </body>
</html>
