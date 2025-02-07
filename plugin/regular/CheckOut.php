<div class="collapse" id="CheckOut">
  <div class="row">
    
    <div class="col-md-9 col-12">
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
                    <tbody>
                      <?php
                        $orderDetails = mysqli_query($conn, "SELECT * FROM upti_order_list WHERE ol_poid = '$poid'");
                        foreach ($orderDetails as $orderData) {
                      ?>
                      <tr>
                        <td><?php echo $orderData['ol_code'] ?></td>
                        <td><?php echo $orderData['ol_desc'] ?></td>
                        <td><?php echo $orderData['ol_qty'] ?></td>
                        <td><?php echo $orderData['ol_price'] ?></td>
                        <td class="text-end subtotal"><?php echo $orderData['ol_subtotal'] ?></td>
                      </tr>
                      <?php } ?>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>

          <div class="row align-items-center justify-content-center">
            <div class="col-lg-6 col-md-6">

            </div>
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

    <div class="col-md-3 col-12">
      asd
    </div>

  </div>
</div>

<script>
  $(document).ready(function() {
    function fetchInvoiceData() {
        let poid = "<?php echo $poid; ?>"; // Kukunin ang PO ID mula sa PHP

        $.ajax({
            url: 'backend/order/checkoutInfo',
            type: 'GET',
            data: { poid: poid },
            dataType: 'json',
            success: function(response) {
                // Update Customer Details
                $('.invoice-name').text(response.customer.name);
                $('.invoice-details').html(
                    response.customer.mobile + "<br>" +
                    response.customer.email + "<br>" +
                    response.customer.address + "<br>" +
                    response.customer.country + "<br>" +
                    response.customer.state
                );

                // Update Order Table
                let orderTable = $(".invoice-table tbody");
                orderTable.empty(); // Clear existing rows

                response.orders.forEach(function(order) {
                    orderTable.append(`
                        <tr>
                            <td>${order.ol_code}</td>
                            <td>${order.ol_desc}</td>
                            <td>${order.ol_qty}</td>
                            <td>${order.ol_price}</td>
                            <td class="text-end subtotal">${order.ol_subtotal}</td>
                        </tr>
                    `);
                });

                // Call function to calculate total
                calculateTotal();
            }
        });
    }

    function calculateTotal() {
        let totalAmount = 0;

        // Iterate through all subtotal values in the table
        $(".subtotal").each(function() {
            let subtotalValue = parseFloat($(this).text()) || 0; // Convert to number
            totalAmount += subtotalValue;
        });

        let shippingFee = parseFloat($(".shipping-fee").text()) || 0; // Get shipping fee
        let finalTotal = totalAmount + shippingFee;

        $(".subtotal-amount").text(totalAmount.toFixed(2));
        $(".total-amount").text(finalTotal.toFixed(2));
    }

    // I-refresh ang data kada 3 segundo
    setInterval(fetchInvoiceData, 3000);
});
</script>
