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
                    <div class="col-xl-3 col-md-6 col-sm-12 col-12 mb-2">
                      <button class="btn form-control text-white accordion-btn btn-dark" type="button" data-bs-toggle="collapse"
                        data-bs-target="#ResellerInformation" aria-expanded="false" aria-controls="ResellerInformation" id="btnResellerInformation">
                        Reseller Information
                      </button>
                    </div>

                    <div class="col-xl-3 col-md-6 col-sm-12 col-12 mb-2">
                      <button class="btn form-control text-white accordion-btn btn-dark" type="button" data-bs-toggle="collapse"
                        data-bs-target="#ResellerLoginInformation" aria-expanded="false" aria-controls="ResellerLoginInformation" id="btnResellerLoginInformation">
                        Reseller Account
                      </button>
                    </div>

                    <div class="col-xl-3 col-md-6 col-sm-12 col-12 mb-2">
                      <button class="btn form-control text-white accordion-btn btn-dark" type="button" data-bs-toggle="collapse"
                        data-bs-target="#ResellerOrderDetails" aria-expanded="false" aria-controls="ResellerOrderDetails" id="btnResellerOrderDetails">
                        Reseller Order
                      </button>
                    </div>

                    <div class="col-xl-3 col-md-6 col-sm-12 col-12 mb-2">
                      <button class="btn form-control text-white accordion-btn btn-dark" type="button" data-bs-toggle="collapse"
                        data-bs-target="#ResellerCheckout" aria-expanded="false" aria-controls="ResellerCheckout" id="btnResellerCheckout">
                        Reseller Checkout
                      </button>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <!-- Buttons Plugin -->
          <?php include_once 'plugin/bundle/personalInformation.php' ?>
          <?php include_once 'plugin/bundle/loginInformation.php' ?>
          <?php include_once 'plugin/bundle/OrderDetails.php' ?>
          <?php include_once 'plugin/bundle/CheckOut.php' ?>
          
        </div>
      </div>

    </div>

    <!-- Footer -->
    <?php include_once 'include/footer.php' ?>
    <script src="function/bundle/mainpage.js"></script>
  </body>
</html>
