(function () {
  var resetFriendlyCaptchaWidget = function () {
    if (window.friendlyChallenge) {
      window.friendlyChallenge.autoWidget.reset();
    }
    if (window.frcaptcha) {
      window.frcaptcha.widgets.forEach(function(w) { w.reset() });
    }
  };
  document.addEventListener("DOMContentLoaded", function (event) {
    document.addEventListener("wpcf7mailsent", resetFriendlyCaptchaWidget);
    document.addEventListener("wpcf7mailfailed", resetFriendlyCaptchaWidget);
    document.addEventListener("wpcf7spam", resetFriendlyCaptchaWidget);
    document.addEventListener("wpcf7invalid", resetFriendlyCaptchaWidget);
    document.addEventListener("wpcf7submit", resetFriendlyCaptchaWidget);
  });
})();
