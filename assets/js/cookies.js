(function () {
  'use strict';

  var config = window.punsCookies || {};
  var storageKey = config.storageKey || 'puns_cookie_consent';
  var banner = document.getElementById('puns-cookie-banner');

  if (!banner) {
    return;
  }

  function getConsent() {
    try {
      return window.localStorage.getItem(storageKey);
    } catch (error) {
      return null;
    }
  }

  function setConsent(value) {
    try {
      window.localStorage.setItem(storageKey, value);
    } catch (error) {
      // Ignore private mode / blocked storage.
    }
  }

  function showBanner() {
    banner.hidden = false;
    document.body.classList.add('puns-cookie-banner-visible');
  }

  function hideBanner() {
    banner.hidden = true;
    document.body.classList.remove('puns-cookie-banner-visible');
  }

  function choose(value) {
    setConsent(value);
    hideBanner();
    document.dispatchEvent(
      new CustomEvent('punsCookieConsent', {
        detail: { value: value },
      })
    );
  }

  if (!getConsent()) {
    showBanner();
  }

  banner.addEventListener('click', function (event) {
    var button = event.target.closest('[data-puns-cookie]');
    if (!button) {
      return;
    }

    choose(button.getAttribute('data-puns-cookie'));
  });

  document.addEventListener('click', function (event) {
    var openLink = event.target.closest('[data-puns-cookie-open]');
    if (!openLink) {
      return;
    }

    event.preventDefault();
    showBanner();
  });
})();
