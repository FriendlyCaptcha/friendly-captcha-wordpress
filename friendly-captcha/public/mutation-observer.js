(function () {
  function findCaptchaElements(node) {
    if (node.querySelectorAll) {
      return node.querySelectorAll(".frc-captcha");
    } else {
      return [];
    }
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
    for (let i = 0; i < elements.length; i++) {
      const element = elements[i];

      // friendly-captcha-sdk adds the "frcWidget" property to the element when it's initialized
      if (element && !element.frcWidget) {
        // If the widget was initialized before and then re-inserted into the DOM, the iframe will still be there
        // We remove any existing content to make sure we end up with exactly one iframe
        element.innerHTML = "";
        window.frcaptcha.attach(element);
      }
    }
  }

  const observer = new MutationObserver((mutationList) => {
    for (let m = 0; m < mutationList.length; m++) {
      const mutation = mutationList[m];

      const nodes = mutation.addedNodes;
      for (let n = 0; n < nodes.length; n++) {
        if (window.friendlyChallenge) {
          setupV1CaptchaElements(nodes[n]);
        } else if (window.frcaptcha) {
          setupV2CaptchaElements(nodes[n]);
        }
      }
    }
  });

  function init() {
    // Start observing the document body for changes
    observer.observe(document.body, {
      attributes: false,
      childList: true,
      subtree: true,
    });
  }

  init();
})();
