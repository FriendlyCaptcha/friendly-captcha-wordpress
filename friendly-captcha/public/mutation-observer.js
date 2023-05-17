(function () {
  function findCaptchaElements(node) {
    return parent.querySelectorAll(".frc-captcha");
  }

  function setupCaptchaElements(node) {
    let autoWidget = window.friendlyChallenge.autoWidget;

    const elements = findCaptchaElements(node);
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

  const observer = new MutationObserver((mutationList) => {
    for (const mutation of mutationList) {
      if (mutation.type === "childList") {
        for (const node of mutation.addedNodes) {
          setupCaptchaElements(node);
        }
      }
    }
  });

  // Start observing the target node for configured mutations
  observer.observe(document.body, {
    attributes: false,
    childList: true,
    subtree: false,
  });
})();
