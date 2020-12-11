

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
        multiple: true
    }).on('select', function() {

      wp_media.state().get('selection').each(function(i) {
        var el = i.toJSON();
        console.log(el);
        var $clone = $template.clone().removeClass('template');
        $clone.find('.image-url img').attr('src', el.sizes.thumbnail.url).attr('title', el.title);
        $clone.find('.image-url a').attr('href', el.url);
        $clone.find('.image-actions input').val(el.id);
        $table.find('tbody').append($clone);
      });

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
