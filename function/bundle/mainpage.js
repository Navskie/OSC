$(document).ready(function () {
  // Set the initial state of buttons to btn-dark
  $(".accordion-btn").addClass("btn-dark").removeClass("btn-primary");

  // Collapse other accordions when one is clicked
  $(".accordion-btn").on("click", function () {
    var target = $(this).attr("data-bs-target");

    // Collapse all other accordions except the one clicked
    $(".accordion-btn")
      .not(this)
      .each(function () {
        var otherTarget = $(this).attr("data-bs-target");
        $(otherTarget).collapse("hide"); // Hide other targets
        $(this).attr("aria-expanded", "false"); // Update aria-expanded attribute
        $(this).removeClass("btn-primary").addClass("btn-dark"); // Set inactive buttons to btn-dark
      });

    // Toggle the current accordion's state
    var isExpanded = $(target).hasClass("show");
    $(this).attr("aria-expanded", isExpanded ? "false" : "true");

    // Add btn-primary to the clicked button and remove btn-dark
    $(this).removeClass("btn-dark").addClass("btn-primary");

    // Store the clicked accordion's ID in localStorage
    localStorage.setItem("lastAccordion", target);
  });

  // On page load, check if there's a stored accordion state
  var lastAccordion = localStorage.getItem("lastAccordion");
  if (lastAccordion) {
    // Open the last clicked accordion
    $(lastAccordion).collapse("show");
    // Set the button's aria-expanded to true
    $('[data-bs-target="' + lastAccordion + '"]').attr("aria-expanded", "true");
    // Set the last clicked button to btn-primary
    $('[data-bs-target="' + lastAccordion + '"]')
      .removeClass("btn-dark")
      .addClass("btn-primary");
  } else {
    // Default to opening Personal Information if no previous state is stored
    $("#ResellerInformation").collapse("show");
    $("#btnResellerInformation").attr("aria-expanded", "true");
    // Set the default button to btn-primary
    $("#btnResellerInformation")
      .removeClass("btn-dark")
      .addClass("btn-primary");
  }
});
