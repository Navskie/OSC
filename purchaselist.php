<!DOCTYPE html>
<html lang="en">
  <?php include_once 'include/head.php' ?>
  <?php include_once 'database/conn.php' ?>
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
            <div class="col-sm-12">
              <div class="card">
                <div class="card-header">
                  <h5 class="card-title mb-2">Purchase Order List</h5>
                  <span id="inventoryCount" class="text-muted">Total Records: 0</span>
                </div>
                <div class="card-body">
                  <div class="table-responsive">
                    <table class="datatable table table-stripped" id="purchaseList">
                      <thead>
                        <tr>
                          <th>Poid</th>
                          <th>Date</th>
                          <th>Customer Name</th>
                          <th>Country</th>
                          <th>Shipping Fee</th>
                          <th>Total Amount</th>
                          <th>Status</th>
                          <th>Action</th>
                        </tr>
                      </thead>
                      <tbody id="purchaseListBody">
                        <!-- Data will be inserted here -->
                      </tbody>
                    </table>
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
      $(document).ready(function () {
        // Initialize the DataTable once
        var table = $("#purchaseList").DataTable({
          responsive: true,
          autoWidth: false,
          searching: true,
          lengthChange: true,
          paging: true,
          info: true,
          ordering: true,
          destroy: true
        });

        // Function to load and display purchase orders
        function loadPurchaseOrders() {
          $.ajax({
            url: 'backend/order/purchaseList', // Ensure this is the correct URL
            method: 'GET',
            success: function(response) {
              try {
                // Parse JSON response from the server
                const data = JSON.parse(response); 

                // Check if data is an array
                if (!Array.isArray(data)) {
                  console.error("Data is not an array: ", data);
                  return;
                }

                // Clear the table before adding new rows
                table.clear();

                // Add new rows to the table
                data.forEach(function(row) {
                  // Set badge color based on status
                  let statusClass = '';
                  let actionButton = ''; // Initialize action button variable

                  // Set status color and action button for "Pending" orders
                  switch (row.trans_status) {
                    case 'Pending':
                      statusClass = 'badge bg-dark'; // Pending color
                      actionButton = `<button class="btn btn-danger btn-sm cancel-btn" data-id="${row.trans_poid}">Cancel</button>`;
                      break;
                    case 'On Process':
                      statusClass = 'badge bg-info'; // On Process color
                      break;
                    case 'In Transit':
                      statusClass = 'badge bg-secondary'; // In Transit color
                      break;
                    default:
                      statusClass = 'badge bg-warning'; // Default color
                  }

                  let rowHtml = `
                    <tr>
                      <td><a href="orderInformation?poid=${row.trans_poid}" class="btn btn-sm btn-primary">${row.trans_poid}</a></td>
                      <td>${row.trans_date}</td>
                      <td>${row.trans_fname}</td>
                      <td>${row.trans_country}</td>
                      <td>${row.trans_ship}</td>
                      <td>${row.trans_subtotal}</td>
                      <td><span class="${statusClass}">${row.trans_status}</span></td>
                      <td>${actionButton}</td> <!-- Add action button for "Pending" orders -->
                    </tr>
                  `;
                  table.row.add($(rowHtml));
                });

                // Draw the table after adding rows
                table.draw();

                // Update total records count
                $('#inventoryCount').text('Total Records: ' + data.length);
              } catch (error) {
                console.error("Error parsing the response: ", error);
              }
            },
            error: function(xhr, status, error) {
              console.error("AJAX Error: ", status, error);
              alert('Error fetching data.');
            }
          });
        }

        // Handle the Cancel button click
        $('#purchaseListBody').on('click', '.cancel-btn', function () {
          const poid = $(this).data('id');
          
          // Make AJAX request to cancel the order
          $.ajax({
            url: 'backend/order/cancelOrder.php', // Adjust the path to the correct PHP file
            method: 'POST',
            data: { poid: poid },
            success: function(response) {
              // console.log(response); // Log the raw response to check what is being returned
              try {
                let data = JSON.parse(response);
                if (data.status === 'success') {
                  toastr.success(data.message, "Success");
                  loadPurchaseOrders(); // Reload the orders to show updated status
                } else {
                  toastr.error(data.message, "Error");
                }
              } catch (e) {
                console.error("Error parsing JSON: ", e);
              }
            },

            error: function(xhr, status, error) {
              console.error("Error canceling order:", error);
              alert('Error canceling order.');
            }
          });

        });

        // Load purchase orders when the page loads
        loadPurchaseOrders();
      });
    </script>
  </body>
</html>
