document.addEventListener("DOMContentLoaded", function () {
  function resetWidgets() {
    if (window.frcaptcha) {
      window.frcaptcha.widgets.forEach((c) => c.reset());
    } else if (window.friendlyChallenge) {
      window.friendlyChallenge.autoWidget.reset();
    }
  }

  // Old WooCommerce
  document.body.addEventListener("checkout_error", function () {
    resetWidgets();
  });

  // WooCommerce Blocks
  if (window.wp && window.wp.data && window.wc && window.wc.wcBlocksData) {
    const checkoutStore = window.wc.wcBlocksData.checkoutStore;

    // Subscribe to store changes
    window.wp.data.subscribe(function () {
      const state = window.wp.data.select(checkoutStore);

      if (state.isAfterProcessing && state.validationErrors) {
        resetWidgets();
      }
    });
  }
});
