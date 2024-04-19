(function () {
  function findCaptchaElements(node) {
    return node.querySelectorAll(".frc-captcha");
  }

  function setupV1CaptchaElements(node) {
    let autoWidget = window.friendlyChallenge.autoWidget;

    const elements = findCaptchaElements(node);
    for (let i = 0; i < elements.length; i++) {
      const hElement = elements[i];

      // friendly-challenge adds the "friendlyChallengeWidget" property to the element when it's initialized
      if (hElement && !hElement.friendlyChallengeWidget) {
        autoWidget = new window.friendlyChallenge.WidgetInstance(hElement);
      }
    }

    window.friendlyChallenge.autoWidget = autoWidget;
  }

  function setupV2CaptchaElements(node) {
    const elements = findCaptchaElements(node);
    window.frcaptcha.attach(elements);
  }

  const observer = new MutationObserver((mutationList) => {
    for (let m = 0; m < mutationList.length; m++) {
      const mutation = mutationList[m];

      if (mutation.type === "childList") {
        // We only care about new nodes being added
        const nodes = mutation.addedNodes;

        for (let n = 0; n < nodes.length; n++) {
          if (window.friendlyChallenge) {
            setupV1CaptchaElements(nodes[n]);
          } else if (window.frcaptcha) {
            setupV2CaptchaElements(nodes[n]);
          }
        }
      }
    }
  });

  // Start observing the document body for changes
  observer.observe(document.body, {
    attributes: false,
    childList: true,
    subtree: false,
  });
})();
