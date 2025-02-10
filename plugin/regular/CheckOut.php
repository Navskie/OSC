<div class="collapse" id="CheckOut">
  <form action="your_checkout_handler.php" method="POST" enctype="multipart/form-data">
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
                    <h6 class="invoice-name"><?php echo $_SESSION['customer_name'] ?></h6>
                    <p class="invoice-details">
                      <?php echo $_SESSION['mobile_number'] ?> <br>
                      <?php echo $_SESSION['email'] ?> <br>
                      <?php echo $_SESSION['address'] ?> <br>
                      <?php echo $_SESSION['country'] ?> <br>
                      <?php echo $_SESSION['state'] ?> <br>
                    </p>
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
                      <tbody id="orderTableBody">
                        <!-- Order details will be dynamically inserted here -->
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
                      <p>Subtotal <span class="subtotal-amount">0</span></p>
                      <p class="mb-0">Shipping Fee <span class="shipping-fee">0</span></p>
                    </div>
                    <div class="invoice-total-footer">
                      <h4>Total Amount <span class="total-amount">0</span></h4>
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
            <h5>Payments</h5>
            <div class="row">
              <div class="col-12">
                <div class="form-group">
                  <label for="paymentMethod">Choose Payment Method</label>
                  <select name="paymentMethod" id="paymentMethod" class="form-control select2">
                    <option value="">Choose</option>
                    <option value="E-Payment">E-Payment</option>
                    <option value="Cash On Pick Up">Cash On Pick Up</option>
                    <option value="Cash On Delivery">Cash On Delivery</option>
                  </select>
                </div>
              </div>

              <div class="col-12 mb-3" id="paymentDetails" style="display: none;">
                <div class="row">
                  <div class="col-4 d-flex justify-content-center align-items-center w-100 mb-3">
                    <img src="assets/img/replace.png" alt="image" class="img-fluid rounded" width="200" />
                  </div>

                  <div class="col-8 d-flex justify-content-center align-items-center w-100">
                    <div class="form-group students-up-files">
                      <div class="uplod">
                        <label class="file-upload image-upbtn mb-0">
                          Choose File <input type="file" id="fileInput">
                        </label>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <div class="col-12">
              <h5 class="text-center">Official Statement</h5>
              <p>
                It has come to the attention of UPTIMISED CORP. that our products, Soosul and Beauty by Uptimised, are being sold to the general public through various online platforms. These products are being sold below the SRP of the company. This leads to doubt its authenticity and safety in consumption. The company cannot guarantee the safety, efficacy, and authenticity of the products.
              </p>
              <p>
                Likewise, we have a standing policy to all our UVIP Resellers that selling our products below the SRP is strictly prohibited. And to add in the policy, Unboxing Video from your client's order is strictly required. No Unboxing Video - No Replacement, No Return, No Refund Policy.
              </p>
              <p>
                We strongly remind everyone to abide by and comply with the company policy. Failure to comply with the policy will be investigated and may lead to disciplinary action being taken.
              </p>
              <p class="text-center font-weight-bold">Thank you for your cooperation.</p>
              <div class="form-check text-center">
                <input type="checkbox" id="agreeStatement" class="form-check-input">
                <label for="agreeStatement" class="form-check-label">I have read and agree to the statement above.</label>
              </div>
              </div>

              <div class="col-12">
                <button id="purchaseButton" class="btn btn-primary text-white form-control" disabled>Purchase</button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

  </form>
</div>

<script>
  $(document).ready(function() {
    // Temporary image placeholder
    let defaultImage = 'assets/img/placeholder.png';
    $('#paymentDetails img').attr('src', defaultImage);
    
    $('#paymentMethod').change(function() {
      if ($(this).val() === "E-Payment") {
        $('#paymentDetails').fadeIn();
      } else {
        $('#paymentDetails').fadeOut();
      }
    });

    // Image preview before upload
    $("#fileInput").change(function(event) {
      let reader = new FileReader();
      reader.onload = function(e) {
        $('#paymentDetails img').attr('src', e.target.result);
      };
      reader.readAsDataURL(event.target.files[0]);
    });

    function fetchInvoiceData() {
      let poid = "<?php echo $poid; ?>"; // Get POID from PHP

      $.ajax({
        url: 'backend/order/checkoutInfo',
        type: 'GET',
        data: { poid: poid },
        dataType: 'json',
        success: function(response) {
          if (response.status === 'success') {
            let tableBody = $("#orderTableBody");
            tableBody.empty(); // Clear existing data

            response.data.forEach(order => {
              let row = `<tr>
                <td>${order.ol_code}</td>
                <td>${order.ol_desc}`;

              if (order.bundles.length > 0) {
                row += `<br><strong>Bundle Includes:</strong><br>`;
                order.bundles.forEach(bundle => {
                  row += `<span>${bundle.p_s_qty} - ${bundle.p_s_desc}</span><br>`;
                });
              }

              row += `</td>
                <td>${order.ol_qty}</td>
                <td>${order.ol_price}</td>
                <td class="text-end subtotal">${order.ol_subtotal}</td>
              </tr>`;
              tableBody.append(row);
            });

            calculateTotal();
            calculateShippingFee();
          }
        },
        error: function(xhr, status, error) {
          console.error("AJAX Error:", error);
          console.log("XHR Response:", xhr.responseText);
          alert("Error fetching invoice data. Check console for details.");
        }
      });
    }

    function calculateTotal() {
      let totalAmount = 0;
      $(".subtotal").each(function() {
        totalAmount += parseFloat($(this).text()) || 0;
      });

      let shippingFee = parseFloat($(".shipping-fee").text()) || 0;
      $(".subtotal-amount").text(totalAmount.toFixed(2));
      $(".total-amount").text((totalAmount + shippingFee).toFixed(2));
    }

    function calculateShippingFee() {
      let poid = "<?php echo $poid; ?>"; // POID for shipping fee calculation

      $.ajax({
        url: 'backend/order/shippingFee', // Backend endpoint for calculating shipping fee
        type: 'POST',
        data: { poid: poid },
        dataType: 'json',
        success: function(response) {
          if (response.status === 'success') {
            // Ensure that shipping fee is numeric
            let shippingFee = parseFloat(response.shippingFee) || 0;
            $(".shipping-fee").text(shippingFee.toFixed(2));  // Update the shipping fee displayed in the UI
            calculateTotal();  // Recalculate the total with updated shipping fee
          } else {
            console.error('Error:', response.message);
          }
        },
        error: function(xhr, status, error) {
          console.error("AJAX Error:", error);
          alert("Error updating shipping fee. Check console for details.");
        }
      });
    }

    $('#agreeStatement').change(function() {
      $('#purchaseButton').prop('disabled', !this.checked);
    });

    // Submit form using AJAX
    $('#purchaseButton').click(function(e) {
      e.preventDefault();
      
      // Collect form data including payment method, agreement, and receipt image if provided
      let formData = new FormData();
      formData.append('paymentMethod', $('#paymentMethod').val());
      formData.append('agreeStatement', $('#agreeStatement').is(':checked'));

      formData.append('shippingFee', $('.shipping-fee').text());  // Shipping Fee
      formData.append('totalAmount', $('.total-amount').text());  // Total Amount
      
      // Check if a file is selected and append it to the FormData
      let file = $('#fileInput')[0].files[0];
      if (file) {
        formData.append('receiptImage', file);
      }

      // AJAX request to submit the form
      $.ajax({
        url: 'backend/order/checkoutProcess', // Your backend PHP file to handle the process
        type: 'POST',
        data: formData,
        contentType: false,
        processData: false,
        success: function(response) {
          let res = JSON.parse(response);
          if (res.status === 'success') {
            toastr.success("Checkout completed successfully!", "Success");
            setTimeout(function() {
              window.location.href = 'purchaselist'; // Redirect to the list page after successful checkout
            }, 2000);
          } else {
            toastr.error('Error: ' + res.message, 'Error');
          }
        },
        error: function(xhr, status, error) {
          console.error('AJAX Error:', error);
          toastr.error('Error submitting checkout. Check console for details.', 'Error');
        }
      });
    });

    // Initial fetch and refresh every 3 seconds
    fetchInvoiceData();
    calculateShippingFee();
    setInterval(calculateShippingFee, 3000);
    setInterval(fetchInvoiceData, 3000);
  });
</script>
