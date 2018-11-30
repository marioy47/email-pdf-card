
jQuery(document).ready(function($) {
  var $form = jQuery('#'+ihs_get_well.form_id);
  var $notice = jQuery(ihs_get_well.notices_el)
  jQuery('.delivery-date .get-well-input').datepicker();
  jQuery.validate({
    form: '#'+ihs_get_well.form_id
  });

  $form.submit(function(e)  {
    e.preventDefault();
    $notice
      .removeClass('success')
      .addClass('error')
      .removeClass('success')
      .addClass('loading')
      .html('<p></p>');

    var data = {
      'action': ihs_get_well.action,
      'values': $form.serialize()
    };

    jQuery.post(ihs_get_well.ajax_url, data, function(response) {
      if (!response.success) {
        $notice
          .removeClass('loading')
          .removeClass('success')
          .addClass('error')
          .html('<p>'+response.error_msg+'</p>');
      } else {
        $notice
          .removeClass('loading')
          .removeClass('error')
          .addClass('success')
          .html('<p>Message Delivered</p>');
      }
    });
  });


});

