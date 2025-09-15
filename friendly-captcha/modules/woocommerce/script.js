jQuery(function ($) {
  function resetWidgets() {
    if (window.frcaptcha) {
      window.frcaptcha.widgets.forEach((c) => c.reset());
    } else if (window.friendlyChallenge) {
      window.friendlyChallenge.autoWidget.reset();
    }
  }

  $(document.body).on("checkout_error", function () {
    console.log("checkout_error jQuery");
    resetWidgets();
  });
});
