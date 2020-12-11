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
$plugin_slug = 'email-pdf-card';

Plugin_Setup::get_instance()
	->set_file( __FILE__ )
	->set_slug( $plugin_slug )
	->wp_hooks();

Settings_Page::get_instance()
    ->set_slug($plugin_slug)
	->wp_hooks();
/*
$getWellMail = Mail\Send::instance()->start();
Shortcodes\GetWellForm::instance( $getWellMail )->start();

Pdf\Generate::instance()->start();

// Do not create the PDF class unless is needed
if ( ! empty( $_POST['get_well_name'] ) ) {
	$mpdf = new \Mpdf\Mpdf(
		array(
			'default_font_size' => 12,
			'default_font'      => 'helvetica',
		)
	);
	Pdf\Send::instance( $mpdf )->start();
}
 */
