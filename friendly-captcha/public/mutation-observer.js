(function () {
  function findCaptchaElements(node) {
    if (node.querySelectorAll) {
      return node.querySelectorAll(".frc-captcha");
    } else {
      return [];
    }
  }

  function setupCaptchaElements(node) {
    if (!window.friendlyChallenge) {
      // The friendly-challenge library has not been loaded yet
      return;
    }

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

  const observer = new MutationObserver((mutationList) => {
    for (let m = 0; m < mutationList.length; m++) {
      const mutation = mutationList[m];

      const nodes = mutation.addedNodes;

      for (let n = 0; n < nodes.length; n++) {
        setupCaptchaElements(nodes[n]);
      }
    }
  });

  function init()  {
    // Start observing the document body for changes
    observer.observe(document.body, {
      attributes: false,
      childList: true,
      subtree: true,
  })};

  init();
})();
