<div class="row collapse" id="ResellerOrderDetails">
  <div class="row">
    <div class="col-md-12">
      <div class="card invoices-add-card">
        <div class="card-body">
          <form class="invoices-form" id="orderForm">
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
    // $('#reseller_code').select2({
    //   placeholder: "Select an Option",
    //   width: '100%',
    //   ajax: {
    //     url: "backend/bundle/product",
    //     dataType: "json",
    //     delay: 250,
    //     data: function(params) {
    //       return { q: params.term };
    //     },
    //     processResults: function(data) {
    //       return { results: data };
    //     }
    //   }
    // });

    // $("#orderForm").submit(function(e) {
    //   e.preventDefault();
    //   var formData = $(this).serialize();
    //   var submitButton = $("#rssubmitOrder");

    //   submitButton.html('<span class="spinner-border text-white"></span>').prop("disabled", true);

    //   $.ajax({
    //     url: "backend/bundle/items",
    //     type: "POST",
    //     data: formData,
    //     dataType: "json",
    //     success: function(response) {
    //       if (response.status === "success") {
    //         toastr.success("Data loaded successfully!", "Success");
    //         setTimeout(() => {
    //           submitButton.html('Submit Order').prop("disabled", false);
    //         }, 2000);
    //         $("#orderForm")[0].reset();
    //         $('#item_code').val(null).trigger("change");
    //         fetchOrderDetails();      // Fetch updated order details
    //         // fetchOrderNotification(); // Fetch updated order notification
    //       } else if (response.status === "stock") {
    //         toastr.error("Not Enough Stocks", "Error");
    //         setTimeout(() => {
    //           submitButton.html('Submit Order').prop("disabled", false);
    //         }, 2000);
    //       } else if (response.status === "price") {
    //         toastr.error("Item cannot be added without price details", "Error");
    //         setTimeout(() => {
    //           submitButton.html('Submit Order').prop("disabled", false);
    //         }, 2000);
    //       } else if (response.status === "update") {
    //         toastr.success("Data updated successfully!", "Success");
    //         setTimeout(() => {
    //           submitButton.html('Submit Order').prop("disabled", false);
    //         }, 2000);
    //         $("#orderForm")[0].reset();
    //         $('#item_code').val(null).trigger("change");
    //         fetchOrderDetails();      // Fetch updated order details
    //         // fetchOrderNotification(); // Fetch updated order notification
    //       } else {
    //         toastr.error("All fields are required", "Error");
    //         setTimeout(() => {
    //           submitButton.html('Submit Order').prop("disabled", false);
    //         }, 2000);
    //       }
    //     },
    //     error: function(xhr) {
    //       toastr.error("Failed to process the order", "Error");
    //       setTimeout(() => {
    //         submitButton.html('Submit Order').prop("disabled", false);
    //       }, 2000);
    //     }
    //   });
    // });

    // function fetchOrderDetails() {
    //     var table = $('#rsorderTable').DataTable({
    //         destroy: true,  // ✅ Destroy previous instance before re-initializing
    //         processing: true,
    //         serverSide: false, // Set to true only if using server-side processing
    //         ajax: {
    //             url: "backend/bundle/items_list.php",
    //             type: "GET",
    //             dataType: "json",
    //             error: function(xhr, textStatus, errorThrown) {
    //                 console.error("AJAX Error:", textStatus, errorThrown);
    //                 console.log("Server Response:", xhr.responseText); // Check JSON output
    //                 toastr.error("Error loading data. Check console for details.");
    //             }
    //         },
    //         columns: [
    //             { data: "ol_code" },
    //             { data: "ol_desc" },
    //             { data: "ol_qty" },
    //             { data: "ol_price" },
    //             { data: "ol_subtotal" },
    //             {
    //                 data: null,
    //                 render: function(data, type, row) {
    //                     return `<button class="btn btn-danger btn-sm delete-btn" data-id="${row.ol_code}">Delete</button>`;
    //                 }
    //             }
    //         ]
    //     });
    // }

    // // function fetchOrderNotification() {
    // //   $.ajax({
    // //     url: "backend/order/order_notification.php", // Adjust URL as needed
    // //     type: "GET",
    // //     dataType: "json",
    // //     success: function (response) {
    // //       if (response.status === "success" && Array.isArray(response.data) && response.data.length > 0) {
    // //         var requiredUpsellQty = 0;
    // //         var upsellQty = 0;
    // //         var premiumQty = 0;
    // //         var promoQty = 0;
    // //         var specialPromoQty = 0;
    // //         var autoFreeQty = 0;
    // //         var freeQty = 0;
    // //         var otherCategoryQty = 0;
    // //         var hasAutoFree = false;
    // //         var autoFreeItems = []; // Store AUTO FREE items

    // //         response.data.forEach(function(item) {
    // //           switch(item.code_category) {
    // //             case "REQUIRED UPSELL":
    // //               requiredUpsellQty += parseInt(item.total_qty);
    // //               break;
    // //             case "UPSELL":
    // //               upsellQty += parseInt(item.total_qty);
    // //               break;
    // //             case "PREMIUM":
    // //               premiumQty += parseInt(item.total_qty);
    // //               break;
    // //             case "PROMO":
    // //               promoQty += parseInt(item.total_qty);
    // //               break;
    // //             case "SPECIAL PROMO":
    // //               specialPromoQty += parseInt(item.total_qty);
    // //               break;
    // //             case "FREE":
    // //               freeQty += parseInt(item.total_qty);
    // //               break;
    // //             case "AUTO FREE":
    // //               autoFreeQty += parseInt(item.total_qty);
    // //               hasAutoFree = true;
    // //               autoFreeItems.push({ code: item.code, maincode: item.maincode }); // Store both code & maincode
    // //               break;
    // //             default:
    // //               otherCategoryQty += parseInt(item.total_qty);
    // //           }
    // //         });

    // //         var notificationHtml = "";
    // //         var disableCheckout = false; // This will control whether to disable checkout button or not

    // //         // Validation checks and building of notificationHtml
    // //         if ((upsellQty + premiumQty) > 0 && promoQty === 0 && requiredUpsellQty === 0 && specialPromoQty === 0 && otherCategoryQty === 0) {
    // //           notificationHtml += "<p class='text-danger'>You cannot have only UPSELL or PREMIUM in the table. Please add other Regular Items</p>";
    // //           disableCheckout = true;
    // //         }

    // //         if (requiredUpsellQty > 0 && (upsellQty + premiumQty) < requiredUpsellQty) {
    // //           notificationHtml += "<p class='text-danger'>You need to add UPSELL or PREMIUM with the same quantity as REQUIRED UPSELL.</p>";
    // //           disableCheckout = true;
    // //         }

    // //         // ❌ Condition 4: 1 PREMIUM allowed per (PROMO + SPECIAL PROMO + REQUIRED UPSELL)
    // //         var allowedPremiumQty = promoQty + specialPromoQty + requiredUpsellQty;
    // //         if (premiumQty > allowedPremiumQty) {
    // //           notificationHtml += `<p class='text-danger'>You can only add 1 PREMIUM per PROMO, SPECIAL PROMO, or REQUIRED UPSELL.</p>`;
    // //           disableCheckout = true;
    // //         }

    // //         if ((upsellQty + premiumQty) > ((promoQty + requiredUpsellQty + specialPromoQty + otherCategoryQty) * 2)) {
    // //           notificationHtml += "<p class='text-danger'>The total of UPSELL and PREMIUM cannot exceed twice the sum of Regular Items.</p>";
    // //           disableCheckout = true;
    // //         }

    // //         // Default notification if no issues
    // //         if (notificationHtml === "") {
    // //           notificationHtml = "<p>All items are good to go for checkout!</p>";
    // //         }

    // //         // ✅ Show "Grab Exclusive Items" button only if `AUTO FREE` is greater than `FREE`
    // //         if (hasAutoFree && autoFreeQty > freeQty) {
    // //           autoFreeItems.forEach(function(item) {
    // //             notificationHtml += `<button type="button" class="btn btn-primary mt-2" 
    // //                                   data-bs-toggle="modal" 
    // //                                   data-bs-target="#autoFreeModal">
    // //                                   Grab Exclusive Items
    // //                                 </button>`;
    // //           });
    // //         }

    // //         // Update the notifications section
    // //         $("#orderNotification").html(notificationHtml);

    // //         // Enable or Disable the Checkout button based on conditions
    // //         if (disableCheckout) {
    // //           $('#btnCheckOut').prop('disabled', true); // Disable the Checkout button if any validation failed
    // //         } else {
    // //           $('#btnCheckOut').prop('disabled', false); // Enable the Checkout button if all conditions are met
    // //         }

    // //       } else {
    // //         $("#orderNotification").html("<p>No order notifications available</p>");
    // //         $('#btnCheckOut').prop('disabled', true); // Disable Checkout if no notifications
    // //       }
    // //     },
    // //     error: function () {
    // //       $("#orderNotification").html("<p>Error fetching order notifications</p>");
    // //       $('#btnCheckOut').prop('disabled', true); // Disable Checkout on error
    // //     }
    // //   });
    // // }

    // $(document).on('click', '.delete-btn', function() {
    //   var orderCode = $(this).data('id');
    //   $('#deleteModal').modal('show');
    //   $('#confirmDelete').data('id', orderCode);
    // });

    // $('#confirmDelete').click(function() {
    //   var orderCode = $(this).data('id');

    //   $.ajax({
    //     url: "backend/order/delete_item",
    //     type: "POST",
    //     data: { ol_code: orderCode },
    //     dataType: "json",
    //     success: function(response) {
    //       if (response.status === "success") {
    //         toastr.success("Item deleted successfully!", "Success");

    //         fetchOrderDetails();      // Re-fetch order details to update the table
    //         // fetchOrderNotification(); // Update order notifications

    //         $('#deleteModal').modal('hide');
    //       } else {
    //         toastr.error("Failed to delete item", "Error");
    //       }
    //     },
    //     error: function(xhr) {
    //       toastr.error("Error deleting item: " + xhr.responseText, "Error");
    //     }
    //   });
    // });

    // fetchOrderDetails();
    // // fetchOrderNotification();
  });
</script>