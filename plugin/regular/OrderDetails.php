<div class="collapse" id="OrderDetails">
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
                    <select name="item_code" id="item_code" class="form-control select2"></select>
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
                    <button id="submitOrder" class="btn btn-primary form-control text-white">Submit Order</button>
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
    <div class="col-sm-12">
      <div class="card">
        <div class="card-header">
          <h5 class="card-title mb-2">Items List</h5>
        </div>
        <div class="card-body">
          <div class="table-responsive">
            <table class="datatable table table-stripped" id="orderTable">
              <thead>
                <tr>
                  <th>Code</th>
                  <th>Description</th>
                  <th>Qty</th>
                  <th>Price</th>
                  <th>Subtotal</th>
                </tr>
              </thead>
              <tbody id="orderDetails">
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
  $(document).ready(function() {
    $('#item_code').select2({
      placeholder: "Select an Option",
      width: '100%',
      ajax: {
        url: "backend/order/product",
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

    $("#orderForm").submit(function(e) {
      e.preventDefault();
      var formData = $(this).serialize();
      var submitButton = $("#submitOrder");

      submitButton.html('<span class="spinner-border text-white"></span>').prop("disabled", true);

      $.ajax({
        url: "backend/order/items",
        type: "POST",
        data: formData,
        dataType: "json",
        success: function(response) {
          if (response.status === "success") {
            toastr.success("Data loaded successfully!", "Success");
            setTimeout(() => {
              submitButton.html('Submit Order').prop("disabled", false);
            }, 2000);
            $("#orderForm")[0].reset();
            $('#item_code').val(null).trigger("change");
            fetchOrderDetails();
          } else if (response.status === "stock") {
            toastr.error("Not Enough Stocks", "Error");
            setTimeout(() => {
              submitButton.html('Submit Order').prop("disabled", false);
            }, 2000);
          } else if (response.status === "price") {
            toastr.error("No price indicated, cannot add", "Error");
            setTimeout(() => {
              submitButton.html('Submit Order').prop("disabled", false);
            }, 2000);
          } else if (response.status === "update") {
            toastr.success("Data updated successfully!", "Success");
            setTimeout(() => {
              submitButton.html('Submit Order').prop("disabled", false);
            }, 2000);
            $("#orderForm")[0].reset();
            $('#item_code').val(null).trigger("change");
            fetchOrderDetails();
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
      $.ajax({
        url: "backend/order/items_list",
        type: "GET",
        dataType: "json",
        success: function(response) {
          if (response.status === "success") {
            var orderDetails = response.data;
            var orderDetailsHtml = '';
            orderDetails.forEach(function(order) {
              orderDetailsHtml += '<tr>';
              orderDetailsHtml += '<td>' + order.ol_code + '</td>';
              orderDetailsHtml += '<td>' + order.ol_desc + '</td>';
              orderDetailsHtml += '<td>' + order.ol_qty + '</td>';
              orderDetailsHtml += '<td>' + order.ol_price + '</td>';
              orderDetailsHtml += '<td>' + order.ol_subtotal + '</td>';
              orderDetailsHtml += '</tr>';
            });
            $('#orderDetails').html(orderDetailsHtml);
            $('#inventoryTable').DataTable().clear().destroy();
            $('#inventoryTable').DataTable({
              paging: true,
              searching: true,
              ordering: true,
              info: true,
            });
          } else {
            toastr.error("Failed to load order details", "Error");
          }
        },
        error: function(xhr) {
          toastr.error("Failed to fetch order details", "Error");
        }
      });
    }

    fetchOrderDetails();
  });
</script>
