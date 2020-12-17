<?php
/**
 * Plugin Name: Email PDF Card
 * Plugin URI: https://marioyepes.com/portfolio
 * Description: Enables a user to send a PDF Card or Document with a custom message
 * Version: 1.0.0
 * Author: Mario Yepes <marioy47@gmail.com>
 *
 * Version:     1.0.0
 * License:     MIT
 * License URI: license.txt
 * Text Domain: email-pdf
 * Domain Path: /languages
 *
 * @package Email_Pdf_Card
 */

namespace EmailPdfCard;

define( 'EMAIL_PDF_CARD_VERSION', '1.0.0' );

require_once __DIR__ . '/vendor/autoload.php';
$plugin_slug     = 'email-pdf-card'; // Used for the settings page url.
$form_shortcode  = 'email-pdf-card'; // Name of the shortcode.
$options_key     = 'email_pdf_card'; // for the get_option() calls.
$url_query_param = 'email-pdf-generate'; // URL param for the on-the-fly pdf gen.

Plugin_Setup::get_instance()
	->set_file( __FILE__ )
	->set_slug( $plugin_slug )
	->wp_hooks();

Settings_Page::get_instance()
	->set_slug( $plugin_slug )
	->set_file( __FILE__ )
	->set_options_key( $options_key )
	->wp_hooks();

Generate_Display_Pdf::get_instance()
	->set_url_param( $url_query_param )
	->wp_hooks();

Shortcode_Email_Pdf_Form::get_instance()
	->set_file( __FILE__ )
	->set_slug( $plugin_slug )
	->set_shortcode_name( $form_shortcode )
	->set_options_key( $options_key )
	->set_url_param( $url_query_param )
	->wp_hooks();

