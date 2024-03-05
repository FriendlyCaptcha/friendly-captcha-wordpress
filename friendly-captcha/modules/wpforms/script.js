(($) => {
  function submitWidgetInForm(form) {
    const elements = form.find(".frc-captcha");
    if (!elements) {
      return;
    }

    elements.each((_, e) => {
      const widget = e.friendlyChallengeWidget;
      console.log(widget);
      if (!widget) {
        return;
      }

      widget.reset();
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
