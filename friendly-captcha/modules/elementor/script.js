(function () {
  document.addEventListener("submit", function (e) {
    const form = e.submitter.closest("form");
    if (!form) {
      return;
    }

    const element = form.querySelector(".frc-captcha");
    if (!element) {
      return;
    }

    setTimeout(() => {
      // We reset the widget after a short delay to give the form time to grab the solution and submit it
      // This is a workaround for the fact that the form submit event is fired before the solution value is grabbed
      // 1 second is really conservative as this is not affected by network speed or anything like that
      if (element.friendlyChallengeWidget) {
        element.friendlyChallengeWidget.reset();
      } else if (element.frcWidget) {
        element.frcWidget.reset();
      }
    }, 1000);
  });
})();
