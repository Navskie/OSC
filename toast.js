$(function () {
  "use strict";

  var isRTL = $("html").attr("data-textdirection") === "rtl";
  var toastOptions = { closeButton: true, tapToDismiss: false, rtl: isRTL };

  function showToast(type, message, title, options = {}) {
    toastr[type](message, title, { ...toastOptions, ...options });
  }

  // Notification Type Buttons
  $("#type-success").on("click", function () {
    showToast("success", "Have fun storming the castle!", "Miracle Max Says");
  });

  $("#type-info").on("click", function () {
    showToast(
      "info",
      "We do have the Kapua suite available.",
      "Turtle Bay Resort"
    );
  });

  $("#type-warning").on("click", function () {
    showToast(
      "warning",
      "My name is Inigo Montoya. You killed my father, prepare to die!"
    );
  });

  $("#type-error").on("click", function () {
    showToast(
      "error",
      "I do not think that word means what you think it means.",
      "Inconceivable!"
    );
  });

  // Positioning Buttons
  function setupPositionButton(selector, positionClass, title) {
    $(selector).on("click", function () {
      showToast(
        "info",
        "I do not think that word means what you think it means.",
        title,
        { positionClass }
      );
    });
  }

  setupPositionButton("#position-top-left", "toast-top-left", "Top Left!");
  setupPositionButton(
    "#position-top-center",
    "toast-top-center",
    "Top Center!"
  );
  setupPositionButton("#position-top-right", "toast-top-right", "Top Right!");
  setupPositionButton(
    "#position-top-full",
    "toast-top-full-width",
    "Top Full Width!"
  );
  setupPositionButton(
    "#position-bottom-left",
    "toast-bottom-left",
    "Bottom Left!"
  );
  setupPositionButton(
    "#position-bottom-center",
    "toast-bottom-center",
    "Bottom Center!"
  );
  setupPositionButton(
    "#position-bottom-right",
    "toast-bottom-right",
    "Bottom Right!"
  );
  setupPositionButton(
    "#position-bottom-full",
    "toast-bottom-full-width",
    "Bottom Full Width!"
  );

  // Other Functional Buttons
  $("#close-button").on("click", function () {
    showToast("success", "Have fun storming the castle!", "With Close Button", {
      closeButton: true,
    });
  });

  $("#progress-bar").on("click", function () {
    showToast("success", "Have fun storming the castle!", "Progress Bar", {
      progressBar: true,
    });
  });

  $("#clear-toast-btn").on("click", function () {
    var toast = toastr.info(
      'Clear itself?<br /><br /><button type="button" class="btn btn-primary clear">Yes</button>',
      "Clear Toast Button",
      {
        closeButton: true,
        timeOut: 0,
        extendedTimeOut: 0,
        tapToDismiss: false,
      }
    );

    if (toast.find(".clear").length) {
      toast.delegate(".clear", "click", function () {
        toastr.clear(toast, { force: true });
      });
    }
  });

  $("#remove-toast").on("click", function () {
    toastr.remove();
  });

  $("#clear-toast").on("click", function () {
    toastr.clear();
  });

  // Duration & Animation
  $("#fast-duration").on("click", function () {
    showToast("success", "Have fun storming the castle!", "Fast Duration", {
      showDuration: 500,
    });
  });

  $("#slow-duration").on("click", function () {
    showToast("warning", "Have fun storming the castle!", "Slow Duration", {
      hideDuration: 3000,
    });
  });

  $("#timeout").on("click", function () {
    showToast(
      "error",
      "I do not think that word means what you think it means.",
      "Timeout!",
      { timeOut: 5000 }
    );
  });

  $("#sticky").on("click", function () {
    showToast(
      "info",
      "I do not think that word means what you think it means.",
      "Sticky!",
      { timeOut: 0 }
    );
  });

  $("#slide-toast").on("click", function () {
    showToast(
      "success",
      "I do not think that word means what you think it means.",
      "Slide Down / Slide Up!",
      {
        showMethod: "slideDown",
        hideMethod: "slideUp",
        timeOut: 2000,
      }
    );
  });

  $("#fade-toast").on("click", function () {
    showToast(
      "success",
      "I do not think that word means what you think it means.",
      "Fade In / Fade Out!",
      {
        showMethod: "fadeIn",
        hideMethod: "fadeOut",
        timeOut: 2000,
      }
    );
  });
});
