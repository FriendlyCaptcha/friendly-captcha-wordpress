(function () {
  function findCaptchaElements(node) {
    return node.querySelectorAll(".frc-captcha");
  }

  function setupCaptchaElements(node) {
    if (!window.friendlyChallenge) {
      // The friendly-challenge library has not been loaded yet
      return;
    }

    let autoWidget = window.friendlyChallenge.autoWidget;

    const elements = findCaptchaElements(node);
    for (let index = 0; index < elements.length; index++) {
      const hElement = elements[index];

      // friendly-challenge adds the "friendlyChallengeWidget" property to the element when it's initialized
      if (hElement && !hElement.friendlyChallengeWidget) {
        autoWidget = new window.friendlyChallenge.WidgetInstance(hElement);
      }
    }

    window.friendlyChallenge.autoWidget = autoWidget;
  }

  const observer = new MutationObserver((mutationList) => {
    for (const mutation of mutationList) {
      if (mutation.type === "childList") {
        // We only care about new nodes being added
        for (const node of mutation.addedNodes) {
          setupCaptchaElements(node);
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
