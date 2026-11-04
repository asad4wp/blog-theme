(function () {
  'use strict';

  if (typeof punsBlogSearch === 'undefined') {
    return;
  }

  var config = punsBlogSearch;
  var modal = document.getElementById('puns-search-modal');
  var openBtn = document.querySelector('.puns-search-open');
  var input = document.getElementById('puns-search-input');
  var results = document.getElementById('puns-search-results');
  var form = modal ? modal.querySelector('.puns-search-modal__form') : null;
  var lastFocusedElement = null;
  var debounceTimer = null;
  var activeRequest = null;

  function setResults(html) {
    if (!results) return;
    results.innerHTML = html;
  }

  function skeletonItemHtml() {
    return (
      '<li class="puns-search-results__item puns-search-results__item--skeleton" aria-hidden="true">' +
        '<div class="puns-search-results__skeleton">' +
          '<div class="puns-skeleton-block puns-skeleton-block--search-thumb"></div>' +
          '<span class="puns-search-results__content">' +
            '<div class="puns-skeleton-block puns-skeleton-block--search-title"></div>' +
            '<div class="puns-skeleton-block puns-skeleton-block--search-line"></div>' +
            '<div class="puns-skeleton-block puns-skeleton-block--search-line puns-skeleton-block--short"></div>' +
            '<div class="puns-skeleton-block puns-skeleton-block--search-date"></div>' +
          '</span>' +
        '</div>' +
      '</li>'
    );
  }

  function showSkeleton() {
    var count = parseInt(config.skeletons, 10) || 4;
    var html = '<ul class="puns-search-results__list puns-search-results__list--skeleton" aria-busy="true">';
    var i;

    for (i = 0; i < count; i += 1) {
      html += skeletonItemHtml();
    }

    html += '</ul>';
    setResults(html);

    if (results) {
      results.setAttribute('aria-busy', 'true');
    }
  }

  function openSearchModal() {
    if (!modal) return;

    lastFocusedElement = document.activeElement;
    modal.classList.add('puns-search-modal--open');
    modal.setAttribute('aria-hidden', 'false');
    document.body.classList.add('puns-search-open');

    if (input) {
      if (config.initialQuery) {
        input.value = config.initialQuery;
        fetchResults(config.initialQuery);
      } else {
        setResults('<p class="puns-search-results__hint">' + config.emptyHint + '</p>');
      }

      window.setTimeout(function () {
        input.focus();
        if (input.value) {
          input.select();
        }
      }, 50);
    }
  }

  function closeSearchModal() {
    if (!modal) return;

    modal.classList.remove('puns-search-modal--open');
    modal.setAttribute('aria-hidden', 'true');
    document.body.classList.remove('puns-search-open');

    if (activeRequest && typeof activeRequest.abort === 'function') {
      activeRequest.abort();
      activeRequest = null;
    }

    if (lastFocusedElement && typeof lastFocusedElement.focus === 'function') {
      lastFocusedElement.focus();
    }
  }

  function fetchResults(query) {
    var trimmed = query.trim();

    if (trimmed.length < (parseInt(config.minChars, 10) || 2)) {
      setResults('<p class="puns-search-results__hint">' + config.emptyHint + '</p>');

      if (results) {
        results.setAttribute('aria-busy', 'false');
      }

      return;
    }

    showSkeleton();

    var formData = new FormData();
    formData.append('action', config.action);
    formData.append('nonce', config.nonce);
    formData.append('search', trimmed);

    if (activeRequest && typeof activeRequest.abort === 'function') {
      activeRequest.abort();
    }

    var controller = typeof AbortController !== 'undefined' ? new AbortController() : null;
    activeRequest = controller;

    fetch(config.ajaxUrl, {
      method: 'POST',
      credentials: 'same-origin',
      body: formData,
      signal: controller ? controller.signal : undefined,
    })
      .then(function (response) {
        return response.json();
      })
      .then(function (payload) {
        if (!payload || !payload.success) {
          throw new Error('search_failed');
        }

        setResults(payload.data && payload.data.html ? payload.data.html : '');

        if (results) {
          results.setAttribute('aria-busy', 'false');
        }
      })
      .catch(function (error) {
        if (error && error.name === 'AbortError') {
          return;
        }

        setResults('<p class="puns-search-results__empty">' + config.error + '</p>');

        if (results) {
          results.setAttribute('aria-busy', 'false');
        }
      })
      .finally(function () {
        activeRequest = null;
      });
  }

  if (openBtn) {
    openBtn.addEventListener('click', function (event) {
      event.preventDefault();
      openSearchModal();
    });
  }

  if (modal) {
    modal.addEventListener('click', function (event) {
      if (event.target.closest('[data-puns-search-close]')) {
        closeSearchModal();
      }
    });

    document.addEventListener('keydown', function (event) {
      if (event.key === 'Escape' && modal.classList.contains('puns-search-modal--open')) {
        closeSearchModal();
      }
    });
  }

  if (input) {
    input.addEventListener('input', function () {
      window.clearTimeout(debounceTimer);
      debounceTimer = window.setTimeout(function () {
        fetchResults(input.value);
      }, 280);
    });
  }

  if (form) {
    form.addEventListener('submit', function (event) {
      if (!input || input.value.trim().length < (parseInt(config.minChars, 10) || 2)) {
        event.preventDefault();
        return;
      }
    });
  }
})();
