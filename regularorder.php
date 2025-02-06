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

    <script>
  $(document).ready(function () {
    // Set the initial state of buttons to btn-dark
    $('.accordion-btn').addClass('btn-dark').removeClass('btn-primary');

    // Collapse other accordions when one is clicked
    $('.accordion-btn').on('click', function () {
      var target = $(this).attr('data-bs-target');
      
      // Collapse all other accordions except the one clicked
      $('.accordion-btn').not(this).each(function () {
        var otherTarget = $(this).attr('data-bs-target');
        $(otherTarget).collapse('hide');  // Hide other targets
        $(this).attr('aria-expanded', 'false');  // Update aria-expanded attribute
        $(this).removeClass('btn-primary').addClass('btn-dark');  // Set inactive buttons to btn-dark
      });
      
      // Toggle the current accordion's state
      var isExpanded = $(target).hasClass('show');
      $(this).attr('aria-expanded', isExpanded ? 'false' : 'true');
      
      // Add btn-primary to the clicked button and remove btn-dark
      $(this).removeClass('btn-dark').addClass('btn-primary');

      // Store the clicked accordion's ID in localStorage
      localStorage.setItem('lastAccordion', target);
    });

    // On page load, check if there's a stored accordion state
    var lastAccordion = localStorage.getItem('lastAccordion');
    if (lastAccordion) {
      // Open the last clicked accordion
      $(lastAccordion).collapse('show');
      // Set the button's aria-expanded to true
      $('[data-bs-target="' + lastAccordion + '"]').attr('aria-expanded', 'true');
      // Set the last clicked button to btn-primary
      $('[data-bs-target="' + lastAccordion + '"]').removeClass('btn-dark').addClass('btn-primary');
    } else {
      // Default to opening Personal Information if no previous state is stored
      $('#PersonalInformation').collapse('show');
      $('#btnPersonalInformation').attr('aria-expanded', 'true');
      // Set the default button to btn-primary
      $('#btnPersonalInformation').removeClass('btn-dark').addClass('btn-primary');
    }
  });
</script>



  </body>
</html>
