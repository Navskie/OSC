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

          <?php 
            $poid = $_GET['poid'];

            $orderInformation = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM upti_transaction WHERE trans_poid = '$poid' AND trans_seller = '$users_code'"));
          ?>
            <div class="row">
              <div class="col-md-8 col-12">
                <div class="card invoice-info-card">
                  <div class="card-body">
                    <div class="invoice-item invoice-item-one">
                      <div class="row">
                        <div class="col-md-6">
                          <div class="invoice-logo">
                            <img src="assets/img/logo.png" alt="logo">
                          </div>
                          <div class="invoice-head">
                            <h2>Purchase Number</h2>
                            <p>Invoice Number : <?php echo $poid ?></p>
                          </div>
                        </div>
                        <div class="col-md-6">
                          <div class="invoice-info">
                            <strong class="customer-text-one">Customer Details</strong>
                            <h6 class="invoice-name"><?php echo $orderInformation['trans_fname'] ?></h6>
                            <p class="invoice-details">
                              <?php echo $orderInformation['trans_number'] ?> <br>
                              <?php echo $orderInformation['trans_email'] ?> <br>
                              <?php echo $orderInformation['trans_address'] ?> <br>
                              <?php echo $orderInformation['trans_country'] ?> <br>
                              <?php echo $orderInformation['trans_state'] ?> <br>
                            </p>
                            <span class="badge bg-dark"><?php echo $orderInformation['trans_status'] ?></span>
                          </div>
                        </div>
                      </div>
                    </div>

                    <div class="invoice-item invoice-table-wrap">
                      <div class="row">
                        <div class="col-md-12">
                          <div class="table-responsive">
                            <table class="invoice-table table table-center mb-0">
                              <thead>
                                <tr>
                                  <th>Code</th>
                                  <th>Description</th>
                                  <th>Qty</th>
                                  <th>Price</th>
                                  <th>Subtotal</th>
                                </tr>
                              </thead>
                              <tbody id="orderList">
                                <?php 
                                  $sqlList = mysqli_query($conn, "SELECT * FROM upti_order_list WHERE ol_poid = '$poid' AND ol_seller = '$users_code'");

                                  foreach ($sqlList as $sqlData) {
                                ?>
                                    <tr>
                                      <td><?php echo $sqlData['ol_code'] ?></td>
                                      <td><?php echo $sqlData['ol_desc'] ?></td>
                                      <td><?php echo $sqlData['ol_qty'] ?></td>
                                      <td><?php echo $sqlData['ol_price'] ?></td>
                                      <td><?php echo $sqlData['ol_subtotal'] ?></td>
                                    </tr>
                                <?php
                                    $totalSubtotal += $sqlData['ol_subtotal'];
                                  }
                                ?>
                              </tbody>
                            </table>
                          </div>
                        </div>
                      </div>
                    </div>

                    <div class="row align-items-center justify-content-center">
                      <div class="col-lg-6 col-md-6"></div>
                      <div class="col-lg-6 col-md-6">
                        <div class="invoice-total-card">
                          <div class="invoice-total-box">
                            <div class="invoice-total-inner">
                              <p>Subtotal <span class="subtotal-amount"><?php echo $totalSubtotal ?></span></p>
                              <p class="mb-0">Shipping Fee <span class="shipping-fee"><?php echo $orderInformation['trans_ship'] ?></span></p>
                            </div>
                            <div class="invoice-total-footer">
                              <h4>Total Amount <span class="total-amount"><?php echo $totalSubtotal + $orderInformation['trans_ship'] ?></span></h4>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-md-4 col-12">
                <div class="card invoice-info-card">
                  <div class="card-body">
                    <h5>Remarks</h5>
                    <hr>
                    <form id="remarksForm" method="post">
                      <div class="form-group">
                        <textarea name="remark_content" id="remark_content" class="form-control" placeholder="Add a remark..."></textarea>
                      </div>
                      <div class="form-group">
                        <button type="submit" class="btn btn-primary">Submit</button>
                      </div>
                    </form>
                  </div>
                </div>

                <div class="card">
                  <div class="card-body" id="remarksList">
                    <!-- Remarks will be loaded here dynamically -->
                  </div>
                </div>
              </div>
            </div>
          
        </div>
      </div>

    </div>

    <!-- Footer -->
    <?php include_once 'include/footer.php' ?>
    <script src="function/order/mainpage.js"></script>
    <script>
      $(document).ready(function () {
        // Function to load remarks via AJAX
        function loadRemarks() {
          $.ajax({
            url: 'backend/remark/fetchRemarks.php', // Adjust the URL to match your server-side file
            method: 'GET',
            data: { poid: '<?php echo $poid; ?>' },  // Send the POID to the server for filtering remarks
            success: function (response) {
              $('#remarksList').html(response);
            },
            error: function (xhr, status, error) {
              console.error('Error fetching remarks:', error);
              toastr.error("Invalid response format from server.", "Error");
            }
          });
        }

        // Initial loading of remarks
        loadRemarks();

        // Function to handle form submission for adding remarks
        $('#remarksForm').on('submit', function (e) {
          e.preventDefault(); // Prevent page refresh on form submission
          const remarkContent = $('#remark_content').val();

          if (remarkContent.trim() === '') {
            toastr.error("Please enter some remark!", "Error");
            return;
          }

          $.ajax({
            url: 'backend/remark/addRemark.php', // Adjust the URL to your back-end file
            method: 'POST',
            data: {
              poid: '<?php echo $poid; ?>',  // Send the POID to associate with the remark
              remark_content: remarkContent
            },
            success: function (response) {
              $('#remark_content').val('');  // Clear the input field
              loadRemarks();  // Reload the remarks list
              toastr.success("Remark added successfully!", "Success");
            },
            error: function (xhr, status, error) {
              console.error('Error adding remark:', error);
              toastr.error("Error adding remark. Please try again.", "Error");
            }
          });
        });
      });
    </script>
  </body>
</html>
