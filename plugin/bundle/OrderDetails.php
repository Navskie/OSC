<div class="row collapse" id="ResellerOrderDetails">
  <div class="row">
    <div class="col-md-12">
      <div class="card invoices-add-card">
        <div class="card-body">
          <form class="invoices-form" id="NeworderForm">
            <div>
              <div class="row align-items-end">
                <div class="col-xl-4 col-md-6 col-sm-12 col-12">
                  <div class="form-group">
                    <label>Product</label>
                    <select name="reseller_code" id="reseller_code" class="form-control select2"></select>
                  </div>
                </div>
                <div class="col-xl-4 col-md-6 col-sm-12 col-12">
                  <div class="form-group">
                    <label>Qty</label>
                    <input name="quantity" class="form-control" type="text" placeholder="Enter Quantity" autocomplete="OFF">
                  </div>
                </div>
                <div class="col-xl-4 col-md-6 col-sm-12 col-12">
                  <div class="form-group">
                    <button id="rssubmitOrder" class="btn btn-primary form-control text-white">Submit Order</button>
                  </div>
                </div>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>

  <div class="row">
    <div class="col-md-3 col-12">
      <div class="ribbon-wrapper card">
        <div class="card-body">
          <div class="ribbon ribbon-secondary">Order Notification</div>
          <div id="orderNotification"></div>
        </div>
      </div>
    </div>

    <div class="col-md-9 col-12">
      <div class="card">
        <div class="card-header">
          <h5 class="card-title mb-2">Items List</h5>
        </div>
        <div class="card-body">
          <div class="table-responsive">
            <table class="datatable table table-stripped" id="rsorderTable">
              <thead>
                <tr>
                  <th>Code</th>
                  <th>Description</th>
                  <th>Qty</th>
                  <th>Price</th>
                  <th>Subtotal</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody id="orderDetails"></tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>

  <?php include_once 'modal/order/delete_item.php' ?>
</div>

<script>
  $(document).ready(function() {
    $('#reseller_code').select2({
      placeholder: "Select an Option",
      width: '100%',
      ajax: {
        url: "backend/bundle/product",
        dataType: "json",
        delay: 250,
        data: function(params) {
          return { q: params.term };
        },
        processResults: function(data) {
          return { results: data };
        }
      }
    });

    $("#NeworderForm").submit(function(e) {
      e.preventDefault();
      var formData = $(this).serialize();
      var submitButton = $("#rssubmitOrder");

      submitButton.html('<span class="spinner-border text-white"></span>').prop("disabled", true);

      $.ajax({
        url: "backend/bundle/items",
        type: "POST",
        data: formData,
        dataType: "json",
        success: function(response) {
          if (response.status === "success") {
            toastr.success("Data loaded successfully!", "Success");
            setTimeout(() => {
              submitButton.html('Submit Order').prop("disabled", false);
            }, 2000);
            $("#NeworderForm")[0].reset();
            $('#reseller_code').val(null).trigger("change");
            fetchOrderDetails();      // Fetch updated order details
            fetchOrderNotification(); // Fetch updated order notification
          } else if (response.status === "stock") {
            toastr.error("Not Enough Stocks", "Error");
            setTimeout(() => {
              submitButton.html('Submit Order').prop("disabled", false);
            }, 2000);
          } else if (response.status === "price") {
            toastr.error(response.message, "Error");
            setTimeout(() => {
              submitButton.html('Submit Order').prop("disabled", false);
            }, 2000);
          } else if (response.status === "update") {
            toastr.success("Data updated successfully!", "Success");
            setTimeout(() => {
              submitButton.html('Submit Order').prop("disabled", false);
            }, 2000);
            $("#NeworderForm")[0].reset();
            $('#reseller_code').val(null).trigger("change");
            fetchOrderDetails();      // Fetch updated order details
            fetchOrderNotification(); // Fetch updated order notification
          } else {
            toastr.error("All fields are required", "Error");
            setTimeout(() => {
              submitButton.html('Submit Order').prop("disabled", false);
            }, 2000);
          }
        },
        error: function(xhr) {
          toastr.error("Failed to process the order", "Error");
          setTimeout(() => {
            submitButton.html('Submit Order').prop("disabled", false);
          }, 2000);
        }
      });
    });

    function fetchOrderDetails() {
        var table = $('#rsorderTable').DataTable({
            destroy: true,  // ✅ Destroy previous instance before re-initializing
            processing: true,
            serverSide: false, // Set to true only if using server-side processing
            ajax: {
                url: "backend/bundle/items_list.php",
                type: "GET",
                dataType: "json",
                error: function(xhr, textStatus, errorThrown) {
                    console.error("AJAX Error:", textStatus, errorThrown);
                    console.log("Server Response:", xhr.responseText); // Check JSON output
                    toastr.error("Error loading data. Check console for details.");
                }
            },
            columns: [
                { data: "ol_code" },
                { data: "ol_desc" },
                { data: "ol_qty" },
                { data: "ol_price" },
                { data: "ol_subtotal" },
                {
                    data: null,
                    render: function(data, type, row) {
                        return `<button class="btn btn-danger btn-sm delete-btn" data-id="${row.ol_code}">Delete</button>`;
                    }
                }
            ]
        });
    }

    function fetchOrderNotification() {
      $.ajax({
          url: "backend/bundle/order_notification", // Adjust URL as needed
          type: "GET",
          dataType: "json",
          success: function (response) {
              if (response.status === "success" && Array.isArray(response.data) && response.data.length > 0) {
                  var resellersQty = 0;
                  var upsellQty = 0;
                  var premiumQty = 0;
                  var otherCategoryQty = 0;
                  var notificationHtml = "";
                  var disableCheckout = false; // This will control whether to disable checkout button or not

                  // Iterate over the items in the response to accumulate quantities
                  response.data.forEach(function (item) {
                      switch (item.code_category) {
                          case "RESELLER":
                              resellersQty += parseInt(item.total_qty);
                              break;
                          case "UPSELL":
                              upsellQty += parseInt(item.total_qty);
                              break;
                          case "PREMIUM":
                              premiumQty += parseInt(item.total_qty);
                              break;
                          default:
                              otherCategoryQty += parseInt(item.total_qty);
                      }
                  });

                  // Validation checks

                  // 1. Only one RESELLER item is allowed
                  if (resellersQty > 1) {
                      notificationHtml += "<p class='text-danger'>Only one RESELLER item is allowed.</p>";
                      disableCheckout = true;
                  }

                  // 2. Validation for UPSELL and PREMIUM items
                  if (premiumQty > resellersQty) {
                    notificationHtml += `<p class='text-danger'>You can only add 1 PREMIUM per RESELLER PACKAGE.</p>`;
                    disableCheckout = true;
                  }
                  if ((upsellQty + premiumQty) > (resellersQty * 2)) {
                    notificationHtml += "<p class='text-danger'>The total of UPSELL and PREMIUM cannot exceed twice the sum of Regular Items.</p>";
                    disableCheckout = true;
                  }

                  // Default notification if no issues
                  if (notificationHtml === "") {
                      notificationHtml = "<p>All items are good to go for checkout!</p>";
                  }

                  // Update the notifications section
                  $("#orderNotification").html(notificationHtml);

                  // Enable or Disable the Checkout button based on conditions
                  if (disableCheckout) {
                      $('#btnResellerCheckout').prop('disabled', true); // Disable the Checkout button if any validation failed
                  } else {
                      $('#btnResellerCheckout').prop('disabled', false); // Enable the Checkout button if all conditions are met
                  }

              } else {
                  $("#orderNotification").html("<p>No order notifications available</p>");
                  $('#btnResellerCheckout').prop('disabled', true); // Disable Checkout if no notifications
              }
          },
          error: function () {
              $("#orderNotification").html("<p>Error fetching order notifications</p>");
              $('#btnResellerCheckout').prop('disabled', true); // Disable Checkout on error
          }
      });
  }



    $(document).on('click', '.delete-btn', function() {
      var orderCode = $(this).data('id');
      $('#deleteModal').modal('show');
      $('#confirmDelete').data('id', orderCode);
    });

    $('#confirmDelete').click(function() {
      var orderCode = $(this).data('id');

      $.ajax({
        url: "backend/bundle/delete_item",
        type: "POST",
        data: { ol_code: orderCode },
        dataType: "json",
        success: function(response) {
          if (response.status === "success") {
            toastr.success("Item deleted successfully!", "Success");

            fetchOrderDetails();      // Re-fetch order details to update the table
            fetchOrderNotification(); // Update order notifications

            $('#deleteModal').modal('hide');
          } else {
            toastr.error("Failed to delete item", "Error");
          }
        },
        error: function(xhr) {
          toastr.error("Error deleting item: " + xhr.responseText, "Error");
        }
      });
    });

    fetchOrderDetails();
    fetchOrderNotification();
  });
</script>