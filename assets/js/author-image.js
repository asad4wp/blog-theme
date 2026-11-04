(function ($) {
  'use strict';

  function getPreviewUrl(attachment) {
    if (!attachment || !attachment.sizes) {
      return '';
    }

    if (attachment.sizes.thumbnail && attachment.sizes.thumbnail.url) {
      return attachment.sizes.thumbnail.url;
    }

    return attachment.url || '';
  }

  function setPreview($field, url) {
    var $preview = $field.find('[data-puns-author-image-preview]');

    if (url) {
      if ($preview.is('img')) {
        $preview.attr('src', url);
      } else {
        $preview.replaceWith(
          '<img class="puns-author-image-preview" src="' + url + '" alt="" data-puns-author-image-preview>'
        );
      }
      return;
    }

    if ($preview.is('img')) {
      $preview.replaceWith(
        '<div class="puns-author-image-preview puns-author-image-preview--empty" data-puns-author-image-preview>No custom image</div>'
      );
      return;
    }

    $preview.text('No custom image');
  }

  function updateUploadLabel($field, hasImage) {
    var $upload = $field.find('[data-puns-author-image-upload]');

    if (!$upload.length) {
      return;
    }

    $upload.text(hasImage ? 'Replace image' : 'Upload image');
  }

  $(document).on('click', '[data-puns-author-image-upload]', function (event) {
    event.preventDefault();

    if (!window.punsBlogAuthorImage || !punsBlogAuthorImage.canUpload || typeof wp === 'undefined' || !wp.media) {
      return;
    }

    var $field = $(this).closest('[data-puns-author-image]');
    var $input = $field.find('#puns_author_image_id');
    var frame = wp.media({
      title: punsBlogAuthorImage.title,
      button: { text: punsBlogAuthorImage.button },
      library: { type: 'image' },
      multiple: false
    });

    frame.on('select', function () {
      var attachment = frame.state().get('selection').first().toJSON();
      $input.val(String(attachment.id));
      setPreview($field, getPreviewUrl(attachment));
      $field.find('[data-puns-author-image-remove]').show();
      updateUploadLabel($field, true);
    });

    frame.open();
  });

  $(document).on('click', '[data-puns-author-image-remove]', function (event) {
    event.preventDefault();

    var $field = $(this).closest('[data-puns-author-image]');

    $field.find('#puns_author_image_id').val('0');
    setPreview($field, '');
    $(this).hide();
    updateUploadLabel($field, false);
  });
})(jQuery);
