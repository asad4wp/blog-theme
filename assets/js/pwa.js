(function () {
  'use strict';

  if (!('serviceWorker' in navigator) || typeof punsBlogPwa === 'undefined') {
    return;
  }

  window.addEventListener('load', function () {
    navigator.serviceWorker
      .register(punsBlogPwa.swUrl, { scope: punsBlogPwa.scope })
      .catch(function () {
        /* Silent fail — PWA is progressive enhancement. */
      });
  });
})();
