<!DOCTYPE html>
<html lang="en">
  <?php include_once 'include/head.php' ?>
  <?php include_once 'database/conn.php' ?>
  <style>
    /* Skeleton Loader Styles */
    .skeleton {
      background: #e0e0e0;
      background: linear-gradient(90deg, #e0e0e0 25%, #f5f5f5 50%, #e0e0e0 75%);
      background-size: 200% 100%;
      animation: loading 1.2s infinite;
    }

    .skeleton-text {
      width: 100%;
      height: 16px;
      margin: 8px 0;
      border-radius: 4px;
    }

    .skeleton-icon {
      display: inline-block;
      background-color: #ccc;
      width: 40px;
      height: 40px;
      border-radius: 50%;
    }

    @keyframes loading {
      0% {
        background-position: -200% 0;
      }
      100% {
        background-position: 200% 0;
      }
    }
  </style>
  <body>
    <div class="main-wrapper">
      <!-- Navbar -->
      <?php include_once 'include/navbar.php' ?>

      <!-- Sidebar -->
      <?php include_once 'include/sidebar.php' ?>

      <div class="page-wrapper">
        <div class="content container-fluid">
          <div class="page-header">
            <div class="row">
              <div class="col-sm-12">
                <div class="page-sub-header">
                  <h3 class="page-title">Welcome <?php echo $users_name ?>!</h3>
                  <ul class="breadcrumb">
                    <li class="breadcrumb-item">
                      <a href="index">Home</a>
                    </li>
                    <li class="breadcrumb-item active">Dashboard</li>
                  </ul>
                </div>
              </div>
            </div>
          </div>

          <div class="row">
            <!-- My User ID Card -->
            <div class="col-xl-3 col-sm-6 col-12 d-flex">
              <div class="card bg-comman w-100">
                <div class="card-body">
                  <div class="db-widgets d-flex justify-content-between align-items-center">
                    <div class="db-info">
                      <h6>My User ID</h6>
                      <h3 id="user-id" class="skeleton skeleton-text"> </h3>
                    </div>
                    <div class="db-icon">
                      <!-- Skeleton for Image -->
                      <div class="skeleton skeleton-icon" style="width: 40px; height: 40px;"></div>
                      <img id="user-id-img" class="d-none" src="assets/img/icons/dash-icon-01.png" width="40" alt="Dashboard Icon" />
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Sales Data Cards -->
            <div class="col-xl-3 col-sm-6 col-12 d-flex">
              <div class="card bg-comman w-100">
                <div class="card-body">
                  <div class="db-widgets d-flex justify-content-between align-items-center">
                    <div class="db-info">
                      <h6>Today Sales</h6>
                      <h3 id="today-sales" class="skeleton skeleton-text"> </h3>
                    </div>
                    <div class="db-icon">
                      <!-- Skeleton for Image -->
                      <div class="skeleton skeleton-icon" style="width: 40px; height: 40px;"></div>
                      <img id="today-sales-img" class="d-none" src="assets/img/icons/dash-icon-04.svg" width="40" alt="Dashboard Icon" />
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <div class="col-xl-3 col-sm-6 col-12 d-flex">
              <div class="card bg-comman w-100">
                <div class="card-body">
                  <div class="db-widgets d-flex justify-content-between align-items-center">
                    <div class="db-info">
                      <h6>Delivered Sales</h6>
                      <h3 id="delivered-sales" class="skeleton skeleton-text"> </h3>
                    </div>
                    <div class="db-icon">
                      <!-- Skeleton for Image -->
                      <div class="skeleton skeleton-icon" style="width: 40px; height: 40px;"></div>
                      <img id="delivered-sales-img" class="d-none" src="assets/img/icons/dash-icon-04.svg" width="40" alt="Dashboard Icon" />
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <div class="col-3"></div>

            <div class="col-xl-3 col-sm-6 col-12 d-flex">
              <div class="card bg-comman w-100">
                <div class="card-body">
                  <div class="db-widgets d-flex justify-content-between align-items-center">
                    <div class="db-info">
                      <h6>Pending Sales</h6>
                      <h3 id="pending-sales" class="skeleton skeleton-text"> </h3>
                    </div>
                    <div class="db-icon">
                      <!-- Skeleton for Image -->
                      <div class="skeleton skeleton-icon" style="width: 40px; height: 40px;"></div>
                      <img id="pending-sales-img" class="d-none" src="assets/img/icons/dash-icon-04.svg" width="40" alt="Dashboard Icon" />
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <div class="col-xl-3 col-sm-6 col-12 d-flex">
              <div class="card bg-comman w-100">
                <div class="card-body">
                  <div class="db-widgets d-flex justify-content-between align-items-center">
                    <div class="db-info">
                      <h6>On Process Sales</h6>
                      <h3 id="on-process-sales" class="skeleton skeleton-text"> </h3>
                    </div>
                    <div class="db-icon">
                      <!-- Skeleton for Image -->
                      <div class="skeleton skeleton-icon" style="width: 40px; height: 40px;"></div>
                      <img id="on-process-sales-img" class="d-none" src="assets/img/icons/dash-icon-04.svg" width="40" alt="Dashboard Icon" />
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <div class="col-xl-3 col-sm-6 col-12 d-flex">
              <div class="card bg-comman w-100">
                <div class="card-body">
                  <div class="db-widgets d-flex justify-content-between align-items-center">
                    <div class="db-info">
                      <h6>In Transit Sales</h6>
                      <h3 id="in-transit-sales" class="skeleton skeleton-text"> </h3>
                    </div>
                    <div class="db-icon">
                      <!-- Skeleton for Image -->
                      <div class="skeleton skeleton-icon" style="width: 40px; height: 40px;"></div>
                      <img id="in-transit-sales-img" class="d-none" src="assets/img/icons/dash-icon-04.svg" width="40" alt="Dashboard Icon" />
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <div class="col-xl-3 col-sm-6 col-12 d-flex">
              <div class="card bg-comman w-100">
                <div class="card-body">
                  <div class="db-widgets d-flex justify-content-between align-items-center">
                    <div class="db-info">
                      <h6>RTS Sales</h6>
                      <h3 id="rts-sales" class="skeleton skeleton-text"> </h3>
                    </div>
                    <div class="db-icon">
                      <!-- Skeleton for Image -->
                      <div class="skeleton skeleton-icon" style="width: 40px; height: 40px;"></div>
                      <img id="rts-sales-img" class="d-none" src="assets/img/icons/dash-icon-04.svg" width="40" alt="Dashboard Icon" />
                    </div>
                  </div>
                </div>
              </div>
            </div>

          </div>
        </div>
      </div>
    </div>

    <!-- Footer -->
    <?php include_once 'include/footer.php' ?>

    <script>
      // Use jQuery to load sales data
      $(document).ready(function() {
        $.ajax({
          url: 'backend/dashboard/index',
          type: 'GET',
          dataType: 'json',
          success: function(data) {
            console.log('Data received:', data); // Log the data to make sure it's correct

            // Update the DOM with the fetched sales data
            $('#today-sales').text(parseFloat(data.todaySales).toFixed(2)).removeClass('skeleton skeleton-text');
            $('#delivered-sales').text(parseFloat(data.deliveredSales).toFixed(2)).removeClass('skeleton skeleton-text');
            $('#pending-sales').text(parseFloat(data.salesData['Pending']).toFixed(2)).removeClass('skeleton skeleton-text');
            $('#on-process-sales').text(parseFloat(data.salesData['On Process']).toFixed(2)).removeClass('skeleton skeleton-text');
            $('#in-transit-sales').text(parseFloat(data.salesData['In Transit']).toFixed(2)).removeClass('skeleton skeleton-text');
            $('#rts-sales').text(parseFloat(data.salesData['RTS']).toFixed(2)).removeClass('skeleton skeleton-text');

            // Update the "My User ID" field
            $('#user-id').text(data.userId).removeClass('skeleton skeleton-text');

            // Show the images after data is loaded and remove skeleton from div and img
            $('#user-id-img').removeClass('d-none').addClass('d-inline-block').removeClass('skeleton skeleton-icon');
            $('#today-sales-img').removeClass('d-none').addClass('d-inline-block').removeClass('skeleton skeleton-icon');
            $('#delivered-sales-img').removeClass('d-none').addClass('d-inline-block').removeClass('skeleton skeleton-icon');
            $('#pending-sales-img').removeClass('d-none').addClass('d-inline-block').removeClass('skeleton skeleton-icon');
            $('#on-process-sales-img').removeClass('d-none').addClass('d-inline-block').removeClass('skeleton skeleton-icon');
            $('#in-transit-sales-img').removeClass('d-none').addClass('d-inline-block').removeClass('skeleton skeleton-icon');
            $('#rts-sales-img').removeClass('d-none').addClass('d-inline-block').removeClass('skeleton skeleton-icon');

            // Also remove the skeleton div for icons once images are loaded
            $('.skeleton.skeleton-icon').remove();
          },
          error: function(xhr, status, error) {
            console.error('Error fetching sales data: ', error);
          }
        });
      });

    </script>
  </body>
</html>
