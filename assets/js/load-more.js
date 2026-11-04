(function () {
  'use strict';

  if (typeof punsBlogLoadMore === 'undefined') {
    return;
  }

  var config = punsBlogLoadMore;
  var button = document.querySelector('.puns-load-more');
  var grid = document.querySelector('.puns-posts__grid');

  if (!button || !grid) {
    return;
  }

  var currentPage = parseInt(config.page, 10) || 1;
  var maxPages = parseInt(config.maxPages, 10) || 1;
  var isLoading = false;
  var skeletonNodes = [];

  function setButtonState(disabled) {
    button.disabled = disabled;
  }

  function hideButton() {
    var wrapper = button.closest('.puns-posts__more');
    if (wrapper) {
      wrapper.remove();
    } else {
      button.remove();
    }
  }

  function skeletonCardHtml() {
    return (
      '<article class="puns-card puns-card--skeleton" aria-hidden="true">' +
        '<div class="puns-card__media"><div class="puns-skeleton-block puns-skeleton-block--media"></div></div>' +
        '<div class="puns-card__body">' +
          '<div class="puns-skeleton-block puns-skeleton-block--tag"></div>' +
          '<div class="puns-skeleton-block puns-skeleton-block--title"></div>' +
          '<div class="puns-skeleton-block puns-skeleton-block--line"></div>' +
          '<div class="puns-skeleton-block puns-skeleton-block--line puns-skeleton-block--short"></div>' +
          '<div class="puns-skeleton-block puns-skeleton-block--meta"></div>' +
        '</div>' +
      '</article>'
    );
  }

  function showSkeleton() {
    hideSkeleton();

    var count = parseInt(config.skeletons, 10) || 3;
    var fragment = document.createDocumentFragment();
    var wrapper = document.createElement('div');
    wrapper.className = 'puns-posts__skeleton';

    for (var i = 0; i < count; i++) {
      var temp = document.createElement('div');
      temp.innerHTML = skeletonCardHtml();
      wrapper.appendChild(temp.firstChild);
    }

    fragment.appendChild(wrapper);
    grid.appendChild(fragment);
    skeletonNodes.push(wrapper);
  }

  function hideSkeleton() {
    skeletonNodes.forEach(function (node) {
      node.remove();
    });
    skeletonNodes = [];
  }

  if (currentPage >= maxPages) {
    hideButton();
    return;
  }

  button.addEventListener('click', function () {
    if (isLoading || currentPage >= maxPages) {
      return;
    }

    isLoading = true;
    setButtonState(true);
    showSkeleton();

    var formData = new FormData();
    formData.append('action', config.action);
    formData.append('nonce', config.nonce);
    formData.append('page', String(currentPage + 1));
    formData.append('context', config.context || 'home');

    if (config.search) {
      formData.append('search', config.search);
    }

    if (config.archive_type) {
      formData.append('archive_type', config.archive_type);
    }

    if (config.term_id) {
      formData.append('term_id', String(config.term_id));
    }

    if (config.author_id) {
      formData.append('author_id', String(config.author_id));
    }

    if (config.year) {
      formData.append('year', String(config.year));
    }

    if (config.monthnum) {
      formData.append('monthnum', String(config.monthnum));
    }

    if (config.day) {
      formData.append('day', String(config.day));
    }

    if (config.post_type) {
      formData.append('post_type', config.post_type);
    }

    fetch(config.ajaxUrl, {
      method: 'POST',
      credentials: 'same-origin',
      body: formData,
    })
      .then(function (response) {
        return response.json();
      })
      .then(function (payload) {
        if (!payload || !payload.success || !payload.data || !payload.data.html) {
          throw new Error('load_failed');
        }

        hideSkeleton();
        grid.insertAdjacentHTML('beforeend', payload.data.html);
        currentPage = parseInt(payload.data.page, 10) || currentPage + 1;
        maxPages = parseInt(payload.data.max_pages, 10) || maxPages;

        if (!payload.data.has_more || currentPage >= maxPages) {
          hideButton();
          return;
        }

        setButtonState(false);
      })
      .catch(function () {
        hideSkeleton();
        button.textContent = config.loadError;
        setButtonState(false);

        window.setTimeout(function () {
          button.textContent = config.loadMore;
        }, 2500);
      })
      .finally(function () {
        isLoading = false;
      });
  });
})();
