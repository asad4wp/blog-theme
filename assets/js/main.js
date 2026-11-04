(function () {
  'use strict';

  var header = document.querySelector('.puns-header');

  if (header) {
    window.addEventListener('scroll', function () {
      if (window.scrollY > 20) {
        header.classList.add('puns-header--scrolled');
      } else {
        header.classList.remove('puns-header--scrolled');
      }
    });
  }

  var mobileNav = document.getElementById('puns-mobile-nav');
  var menuToggle = document.querySelector('[data-puns-menu-toggle]');
  var menuOpenLabel = menuToggle ? menuToggle.getAttribute('data-label-open') || 'Open menu' : 'Open menu';
  var menuCloseLabel = menuToggle ? menuToggle.getAttribute('data-label-close') || 'Close menu' : 'Close menu';

  function openMobileMenu() {
    if (!mobileNav || !menuToggle) {
      return;
    }

    mobileNav.hidden = false;
    // Force reflow so the open transition runs.
    void mobileNav.offsetWidth;
    mobileNav.classList.add('puns-mobile-nav--open');
    document.body.classList.add('puns-menu-open');
    menuToggle.setAttribute('aria-expanded', 'true');
    menuToggle.setAttribute('aria-label', menuCloseLabel);
  }

  function closeMobileMenu() {
    if (!mobileNav || !menuToggle) {
      return;
    }

    mobileNav.classList.remove('puns-mobile-nav--open');
    document.body.classList.remove('puns-menu-open');
    menuToggle.setAttribute('aria-expanded', 'false');
    menuToggle.setAttribute('aria-label', menuOpenLabel);

    window.setTimeout(function () {
      if (!mobileNav.classList.contains('puns-mobile-nav--open')) {
        mobileNav.hidden = true;
      }
    }, 250);
  }

  if (menuToggle && mobileNav) {
    menuToggle.addEventListener('click', function () {
      if (mobileNav.classList.contains('puns-mobile-nav--open')) {
        closeMobileMenu();
      } else {
        openMobileMenu();
      }
    });

    mobileNav.addEventListener('click', function (event) {
      if (event.target.closest('[data-puns-menu-close]')) {
        closeMobileMenu();
        return;
      }

      if (event.target.closest('.puns-mobile-nav__link')) {
        closeMobileMenu();
      }
    });

    document.addEventListener('keydown', function (event) {
      if (event.key === 'Escape' && mobileNav.classList.contains('puns-mobile-nav--open')) {
        closeMobileMenu();
      }
    });
  }

  var subscribeModal = document.getElementById('puns-subscribe-modal');
  var subscribeForm = subscribeModal ? subscribeModal.querySelector('.puns-subscribe-modal__form') : null;
  var subscribeSuccess = subscribeModal ? subscribeModal.querySelector('.puns-subscribe-modal__success') : null;
  var subscribeEmail = subscribeModal ? subscribeModal.querySelector('#puns-subscribe-email') : null;
  var lastFocusedElement = null;

  function openSubscribeModal() {
    if (!subscribeModal) return;

    if (mobileNav && mobileNav.classList.contains('puns-mobile-nav--open')) {
      closeMobileMenu();
    }

    lastFocusedElement = document.activeElement;
    subscribeModal.classList.add('puns-subscribe-modal--open');
    subscribeModal.setAttribute('aria-hidden', 'false');
    document.body.classList.add('puns-subscribe-open');

    if (subscribeSuccess) {
      subscribeSuccess.hidden = true;
    }

    if (subscribeForm) {
      subscribeForm.hidden = false;
    }

    window.setTimeout(function () {
      if (subscribeEmail) {
        subscribeEmail.focus();
      }
    }, 50);
  }

  function closeSubscribeModal() {
    if (!subscribeModal) return;

    subscribeModal.classList.remove('puns-subscribe-modal--open');
    subscribeModal.setAttribute('aria-hidden', 'true');
    document.body.classList.remove('puns-subscribe-open');

    if (lastFocusedElement && typeof lastFocusedElement.focus === 'function') {
      lastFocusedElement.focus();
    }
  }

  document.addEventListener('click', function (event) {
    if (event.target.closest('.puns-subscribe-open')) {
      openSubscribeModal();
    }
  });

  if (subscribeModal) {
    subscribeModal.addEventListener('click', function (event) {
      if (event.target.closest('[data-puns-subscribe-close]')) {
        closeSubscribeModal();
      }
    });

    document.addEventListener('keydown', function (event) {
      if (event.key === 'Escape' && subscribeModal.classList.contains('puns-subscribe-modal--open')) {
        closeSubscribeModal();
      }
    });
  }

  if (subscribeForm) {
    subscribeForm.addEventListener('submit', function (event) {
      event.preventDefault();

      if (!subscribeEmail || !subscribeEmail.value.trim()) {
        subscribeEmail.focus();
        return;
      }

      subscribeForm.hidden = true;

      if (subscribeSuccess) {
        subscribeSuccess.hidden = false;
      }

      window.setTimeout(closeSubscribeModal, 1800);
    });
  }

  function execCopyCommand() {
    try {
      return document.execCommand('copy');
    } catch (error) {
      return false;
    }
  }

  function copyFromElement(el, keepSelection) {
    if (!el || !window.getSelection || !document.createRange) {
      return false;
    }

    var selection = window.getSelection();
    var range = document.createRange();

    range.selectNodeContents(el);
    selection.removeAllRanges();
    selection.addRange(range);

    var copied = execCopyCommand();

    if (!keepSelection) {
      selection.removeAllRanges();
    }

    return copied;
  }

  function selectElementText(el) {
    if (!el || !window.getSelection || !document.createRange) {
      return;
    }

    var selection = window.getSelection();
    var range = document.createRange();
    range.selectNodeContents(el);
    selection.removeAllRanges();
    selection.addRange(range);
  }

  function fallbackCopy(text) {
    var textarea = document.createElement('textarea');
    textarea.value = text;
    textarea.setAttribute('readonly', '');
    textarea.style.cssText =
      'position:absolute;left:-9999px;top:' + String(window.pageYOffset || 0) + 'px;font-size:16px;';
    document.body.appendChild(textarea);

    // iOS Safari needs editable + non-readonly while selecting.
    textarea.contentEditable = 'true';
    textarea.readOnly = false;
    textarea.focus();
    textarea.select();
    textarea.setSelectionRange(0, textarea.value.length);

    var copied = execCopyCommand();
    document.body.removeChild(textarea);
    return copied;
  }

  function copyToClipboard(text, sourceEl) {
    if (!text) {
      return Promise.reject(new Error('Empty text'));
    }

    // Prefer selecting the visible text node — most reliable on HTTP (.local).
    if (sourceEl && copyFromElement(sourceEl, false)) {
      return Promise.resolve();
    }

    if (navigator.clipboard && typeof navigator.clipboard.writeText === 'function' && window.isSecureContext) {
      return navigator.clipboard.writeText(text).catch(function () {
        if (fallbackCopy(text)) {
          return;
        }

        throw new Error('Copy failed');
      });
    }

    return new Promise(function (resolve, reject) {
      if (fallbackCopy(text)) {
        resolve();
        return;
      }

      reject(new Error('Copy failed'));
    });
  }

  function showCopiedToast(message) {
    var toast = document.querySelector('.puns-copy-toast');

    if (!toast) {
      toast = document.createElement('div');
      toast.className = 'puns-copy-toast';
      toast.setAttribute('role', 'status');
      toast.setAttribute('aria-live', 'polite');
      document.body.appendChild(toast);
    }

    toast.innerHTML =
      '<span class="puns-icon" aria-hidden="true">check_circle</span><span></span>';
    toast.querySelector('span:last-child').textContent = message || 'Copied to clipboard';

    toast.classList.add('puns-copy-toast--visible');

    if (toast._punsHideTimer) {
      window.clearTimeout(toast._punsHideTimer);
    }

    toast._punsHideTimer = window.setTimeout(function () {
      toast.classList.remove('puns-copy-toast--visible');
    }, 1800);
  }

  function markCopied(resource, btn) {
    resource.classList.add('puns-article__resource--copied');
    btn.classList.add('puns-article__copy-btn--copied');
    btn.setAttribute('aria-label', 'Copied');

    showCopiedToast('Copied to clipboard');

    if (btn._punsResetTimer) {
      window.clearTimeout(btn._punsResetTimer);
    }

    btn._punsResetTimer = window.setTimeout(function () {
      resource.classList.remove('puns-article__resource--copied');
      btn.classList.remove('puns-article__copy-btn--copied');
      btn.setAttribute('aria-label', 'Copy text');
    }, 1800);
  }

  function getResourceText(resource) {
    var textEl = resource.querySelector('.puns-article__resource-text');

    if (textEl) {
      return {
        el: textEl,
        text: textEl.textContent.replace(/\s+/g, ' ').trim()
      };
    }

    var clone = resource.cloneNode(true);
    var btnClone = clone.querySelector('.puns-article__copy-btn');

    if (btnClone) {
      btnClone.parentNode.removeChild(btnClone);
    }

    return {
      el: null,
      text: clone.textContent.replace(/\s+/g, ' ').trim()
    };
  }

  document.addEventListener('click', function (event) {
    var resource = event.target.closest('.puns-article__resource');
    if (!resource) return;

    event.preventDefault();

    var btn = resource.querySelector('.puns-article__copy-btn');
    if (!btn) return;

    var payload = getResourceText(resource);
    if (!payload.text) return;

    copyToClipboard(payload.text, payload.el)
      .then(function () {
        markCopied(resource, btn);
      })
      .catch(function () {
        if (payload.el) {
          selectElementText(payload.el);
          showCopiedToast('Text selected — press Cmd+C / Ctrl+C');
          return;
        }

        showCopiedToast('Could not copy. Try again.');
      });
  });

  document.addEventListener('keydown', function (event) {
    if (event.key !== 'Enter' && event.key !== ' ') return;

    var resource = event.target.closest('.puns-article__resource');
    if (!resource) return;

    if (event.target.closest('button') && !event.target.closest('.puns-article__copy-btn')) {
      return;
    }

    if (event.target !== resource && !event.target.closest('.puns-article__copy-btn')) {
      return;
    }

    if (event.key === ' ') {
      event.preventDefault();
    }

    resource.click();
  });

  function initFaqTabs(root) {
    var tabs = root.querySelectorAll('.puns-faq-tabs__tab');
    var panels = root.querySelectorAll('.puns-faq-tabs__panel');

    tabs.forEach(function (tab) {
      tab.addEventListener('click', function () {
        var targetId = tab.getAttribute('aria-controls');

        tabs.forEach(function (item) {
          var active = item === tab;
          item.classList.toggle('is-active', active);
          item.setAttribute('aria-selected', active ? 'true' : 'false');
          item.setAttribute('tabindex', active ? '0' : '-1');
        });

        panels.forEach(function (panel) {
          var active = panel.id === targetId;
          panel.classList.toggle('is-active', active);
          if (active) {
            panel.removeAttribute('hidden');
          } else {
            panel.setAttribute('hidden', '');
          }
        });
      });

      tab.addEventListener('keydown', function (event) {
        var keys = ['ArrowUp', 'ArrowDown', 'ArrowLeft', 'ArrowRight', 'Home', 'End'];
        if (keys.indexOf(event.key) === -1) return;

        event.preventDefault();
        var list = Array.prototype.slice.call(tabs);
        var index = list.indexOf(tab);
        var next = index;

        if (event.key === 'ArrowUp' || event.key === 'ArrowLeft') {
          next = (index - 1 + list.length) % list.length;
        } else if (event.key === 'ArrowDown' || event.key === 'ArrowRight') {
          next = (index + 1) % list.length;
        } else if (event.key === 'Home') {
          next = 0;
        } else if (event.key === 'End') {
          next = list.length - 1;
        }

        list[next].focus();
        list[next].click();
      });
    });
  }

  document.querySelectorAll('[data-puns-faq-tabs]').forEach(initFaqTabs);

  

})();

