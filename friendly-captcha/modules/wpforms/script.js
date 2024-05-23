(($) => {
  function submitWidgetInForm(form) {
    const elements = form.find(".frc-captcha");
    if (!elements) {
      return;
    }

    elements.each((_, e) => {
      if (e.friendlyChallengeWidget) {
        e.friendlyChallengeWidget.reset();
      } else if (e.frcWidget) {
        e.frcWidget.reset();
      }
    });
  }

  $("form.wpforms-form").on(
    "wpformsAjaxSubmitSuccess wpformsAjaxSubmitFailed",
    (event) => {
      const form = $(event.target);
      if (form) {
        submitWidgetInForm(form);
      }
    }
  );
})(jQuery);
