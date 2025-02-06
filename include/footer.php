<script src="assets/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="assets/js/feather.min.js"></script>
<script src="assets/plugins/slimscroll/jquery.slimscroll.min.js"></script>
<script src="assets/plugins/apexchart/apexcharts.min.js"></script>
<script src="assets/plugins/apexchart/chart-data.js"></script>
<script src="assets/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="assets/plugins/datatables/datatables.min.js"></script>
<script src="assets/plugins/toastr/toastr.min.js"></script>
<script src="assets/js/script.js"></script>
<script>
  $(document).ready(function () {
    
    var path = window.location.pathname.split("/").pop(); // Get the current page's name

    // Now, check for each link inside the menu
    $(".sidebar-menu li").each(function () {
      var link = $(this).find("a");
      var submenu = $(this).find("ul.removeActive"); // Only target <ul class="removeActive">
      var submenuLinks = submenu.find("a");

      // Check if the main link or any submenu item matches the current page
      if (link.attr("href") === path || submenuLinks.filter("[href='" + path + "']").length) {
        // Add active and subdrop classes to the main link
        link.addClass("active subdrop");

        // Add active class to the parent <li> to make the submenu active
        $(this).addClass("active"); // Add active to the parent <li> to open the submenu

        // Show the submenu when it's active
        submenu.show();

        // Add active class only to the matching submenu link
        submenuLinks.filter("[href='" + path + "']").addClass("active");
      } else {
        // Remove active classes from the main link and submenu
        link.removeClass("active subdrop");
        $(this).removeClass("active"); // Remove active from the parent <li>
        submenu.hide();

        // Remove active classes from all submenu links (but not <li>)
        submenuLinks.removeClass("active");
      }
    });

    // Remove 'active' class from all <li> elements inside <ul class="removeActive">
    $(".removeActive li").removeClass("active");
  });

</script>
