

jQuery(function($) {

  var $table = $('#ihs-get-well-images-table');
  var $add = $('#ihs-get-well-add-image');
  var $template = $table.find('.template');

  $table.find('tbody').sortable();
  $template.remove();

  $add.on('click', function(e) {
    e.preventDefault();
    var wp_media = wp.media({
        title: 'Select background image for card',
        library: {
            type: 'image'
        },
        button: {
            text: 'Add this image'
        },
        multiple: false
    }).on('select', function() {
      var el = wp_media.state().get('selection').first().toJSON();
      console.log(el.url);
      var $clone = $template.clone().removeClass('template');
      $clone.find('.image-name').html(el.name);
      $clone.find('.image-url img').attr('src', el.url);
      $clone.find('.image-actions input').val(el.url);
      $table.find('tbody').append($clone);

      var count = $table.find('tbody')

    }).open();

  });

  $table.delegate('.ihs-remove-image-button', 'click', function(e) {
    e.preventDefault();
    var $button = $(this);
    if (confirm('Remove this image from the list of backgrounds?')) {
        $button.parent().parent().remove();
    }
  })
})
