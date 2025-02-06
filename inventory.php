<!DOCTYPE html>
<html lang="en">
  <?php include_once 'include/head.php' ?>
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
                  <h3 class="page-title">Welcome Name!</h3>
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
                <div class="card-body">
                  <form id="filterForm">
                    <div class="row">
                      <div class="col-12">
                        <h5 class="form-title"><span>Filter Inventory</span></h5>
                      </div>

                      <div class="col-12 col-sm-9">
                        <div class="form-group local-forms">
                          <label>Choose Country <span class="login-danger">*</span></label>
                          <select class="form-control" id="country" name="country">
                            <option value="" selected>Select Country</option>
                            <option value="PHILIPPINES">PHILIPPINES</option>
                            <option value="KOREA">KOREA</option>
                            <option value="TAIWAN">TAIWAN</option>
                            <option value="JAPAN">JAPAN</option>
                            <option value="CANADA">CANADA</option>
                            <option value="USA">USA</option>
                            <option value="HONGKONG">HONGKONG</option>
                            <option value="UNITED ARAB EMIRATES">UNITED ARAB EMIRATES</option>
                          </select>
                        </div>
                      </div>

                      <div class="col-12 col-sm-3">
                        <div class="student-submit">
                          <button type="submit" class="btn btn-primary form-control text-white">Submit</button>
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
                  <h5 class="card-title mb-2">Generated Inventory</h5>
                  <span id="inventoryCount" class="text-muted">Total Records: 0</span>
                </div>
                <div class="card-body">
                  <div class="table-responsive">
                    <table class="datatable table table-stripped" id="inventoryTable">
                      <thead>
                        <tr>
                          <th>Item Code</th>
                          <th>Item Description</th>
                          <th>Item Stocks</th>
                          <th>Status</th>
                        </tr>
                      </thead>
                      <tbody id="inventoryBody">
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
        let table = $("#inventoryTable").DataTable({
            responsive: true,
            autoWidth: false,
            destroy: true
        });

        $("#filterForm").submit(function (event) {
            event.preventDefault();

            let country = $("#country").val();
            if (!country || country === "Select Country") {
                toastr.warning("Please select a valid country.", "Warning");
                return;
            }

            $("#inventoryBody").html(`<tr><td colspan="4" class="text-center">Loading...</td></tr>`);

            $.ajax({
                url: "backend/inventory/generatedStocks",
                type: "POST",
                data: { country: country },
                dataType: "json",
                success: function (response) {
                    // console.log("AJAX Response:", response); // Debugging

                    // Check if response has `data` property and is an array
                    if (!response.data || !Array.isArray(response.data)) {
                        console.error("Invalid response format:", response);
                        toastr.error("Invalid response format from server.", "Error");
                        return;
                    }

                    table.clear().draw(); // Clear DataTable before adding new data

                    if (response.data.length > 0) {
                        response.data.forEach(item => {
                            table.row.add([
                                item.item_code,
                                item.item_desc,
                                item.item_stock,
                                `<span class="badge ${item.status === 'Low Stocks' ? 'bg-danger' : 'bg-success'}">${item.status}</span>`
                            ]).draw(false);
                        });
                    } else {
                        $("#inventoryBody").html(`<tr><td colspan="4" class="text-center text-muted">No records found.</td></tr>`);
                    }

                    $("#inventoryCount").text("Total Records: " + response.count);
                    toastr.success("Data loaded successfully!", "Success");
                },
                error: function (xhr, status, error) {
                    table.clear().draw();
                    $("#inventoryBody").html(`<tr><td colspan="4" class="text-center text-danger">Error loading data.</td></tr>`);
                    toastr.error("Error fetching data: " + xhr.responseText, "Error");
                }
            });
        });
    });

    </script>

  </body>
</html>
