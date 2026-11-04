(function ($) {
  'use strict';

  function updateStyle(id, css) {
    var style = document.getElementById(id);

    if (!style) {
      style = document.createElement('style');
      style.id = id;
      document.head.appendChild(style);
    }

    style.textContent = css;
  }

  wp.customize('puns_logo_max_height', function (setting) {
    setting.bind(function (value) {
      var height = parseInt(value, 10) || 40;

      updateStyle(
        'puns-logo-css',
        '.puns-header__logo img,.puns-header .custom-logo{max-height:' + height + 'px;}'
      );
    });
  });

  wp.customize('puns_footer_logo_max_height', function (setting) {
    setting.bind(function (value) {
      var height = parseInt(value, 10) || 40;

      updateStyle(
        'puns-footer-logo-css',
        '.puns-footer__logo img,.puns-footer__logo-image{max-height:' + height + 'px;width:auto;height:auto;}' +
          '.puns-footer .puns-brand-logo__mark{width:' + height + 'px;height:' + height + 'px;}'
      );
    });
  });

  wp.customize('puns_footer_logo', function (setting) {
    setting.bind(function () {
      wp.customize.preview.send('punsBlogRefreshFooterBrand');
    });
  });

  wp.customize('puns_footer_logo_text', function (setting) {
    setting.bind(function (value) {
      var $logo = $('.puns-footer__logo').not('.puns-footer__logo--image');

      if ($logo.length) {
        $logo.text(value || (wp.customize('blogname') ? wp.customize('blogname').get() : ''));
      }
    });
  });

  wp.customize('puns_home_posts_title', function (setting) {
    setting.bind(function (value) {
      var title = (value || '').trim() || 'Latest Posts';
      $('.puns-page-home .puns-posts__title').text(title);
    });
  });

  wp.customize('puns_footer_copyright', function (setting) {
    setting.bind(function (value) {
      var text = value.trim();
      var siteName = wp.customize('blogname') ? wp.customize('blogname').get() : '';
      var year = new Date().getFullYear();

      if (!text) {
        text = '© {year} {site_name}. Dedicated to the written word.';
      }

      text = text.split('{year}').join(String(year));
      text = text.split('{site_name}').join(siteName);

      $('.puns-footer__copyright').text(text);
    });
  });

  wp.customize.preview.bind('punsBlogRefreshFooterBrand', function () {
    wp.customize.selectiveRefresh.partial('puns_footer_brand').refresh();
  });
})(jQuery);
