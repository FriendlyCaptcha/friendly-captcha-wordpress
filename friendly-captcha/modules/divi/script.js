(function () {
  function findCaptchaElements() {
    return document.querySelectorAll(".frc-captcha");
  }

  var observeDOM = (function(){
    var MutationObserver = window.MutationObserver || window.WebKitMutationObserver;

    return function( obj, callback ){
      if( !obj || obj.nodeType !== 1 ) return;

      if( MutationObserver ){
        // define a new observer
        var mutationObserver = new MutationObserver(callback)

        // have the observer observe for changes in children
        mutationObserver.observe( obj, { childList:true, subtree:true })
        return mutationObserver
      }

      // browser support fallback
      else if( window.addEventListener ){
        obj.addEventListener('DOMNodeInserted', callback, false)
        obj.addEventListener('DOMNodeRemoved', callback, false)
      }
    }
  })()

  function setupCaptchaElements() {
    let autoWidget;

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

  function init() {
    jQuery(document).ready(function () {
      setupCaptchaElements();

      // DOM observing seems necessary because on failed form submits, the container is replaced via AJAX
      // and the captcha needs to be reinitialized;
      observeDOM(document.querySelector('.et_pb_contact_form_container'), function() {
        setupCaptchaElements();
      });
    });

  }

  document.getElementById('friendly-captcha-widget-module-js').addEventListener('load', init);
  document.getElementById('friendly-captcha-widget-fallback-js').addEventListener('load', init);
})();
