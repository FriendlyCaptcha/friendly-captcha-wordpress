(function () {
  function findCaptchaElements() {
    return document.querySelectorAll(".frc-captcha");
  }

  function setupCaptchaElements() {
    let autoWidget = window.friendlyChallenge.autoWidget;

    const elements = findCaptchaElements();
    for (let index = 0; index < elements.length; index++) {
      const hElement = elements[index];
      if (hElement && !hElement.dataset["attached"]) {
        autoWidget = new window.friendlyChallenge.WidgetInstance(hElement);
        // We set the "data-attached" attribute so we don't attach to the same element twice.
        hElement.dataset["attached"] = "1";
      }
    }
    window.friendlyChallenge.autoWidget = autoWidget;
  }

  jQuery(document).ready(function () {
    jQuery(document).on("gform_page_loaded", setupCaptchaElements);
  });
})();
