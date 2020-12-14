<?php
/**
 * Functions to send emails.
 *
 * @package Email_Pdf_Card
 */

namespace EmailPdfCard;

/* The mail function might not be yet loaded. We have to make sure */
require_once ABSPATH . '/wp-load.php';

/**
 * Wrapper of the `wp_mail` function in a class with some addittions like
 * changin the headers and setting an error function.
 */
class Send {

	/**
	 * Sets the Reply-to mail header.
	 *
	 * @var array
	 */
	protected $the_headers = array(
		'Replay-To' => 'Mario Yepes <mario.yepes@dazzet.co>',
	);

	/**
	 * Sets the To: mail field.
	 *
	 * @var string
	 */
	protected $the_to = 'marioy47@gmail.com';

	/**
	 * The Subject: email field.
	 *
	 * @var string
	 */
	protected $the_subject = 'Get Well Card';

	/**
	 * The message body content.
	 *
	 * @var string
	 */
	protected $the_body = 'Message body';

	/**
	 * Array of paths to attachments
	 *
	 * @var array
	 */
	protected $the_attachments = array();

	/**
	 * Adds the required actions to WordPress
	 * - New mail error handling functinos
	 * - New page on the submenu
	 *
	 * @return self
	 */
	private function __construct() {
		add_action( 'wp_mail_failed', array( $this, 'on_mail_error' ), 10, 1 );
		add_filter( 'wp_mail_content_type', array( $this, 'mail_content_type' ) );

		return $this;
	}

	/**
	 * Factory.
	 *
	 * @return self
	 */
	public static function get_instance() {
		static $obj;
		return isset( $obj ) ? $obj : $obj = new self();
	}

	/**
	 * Mail error handling function.
	 *
	 * @param WP_Error $wp_error The error object sent by wp_mail.
	 */
	public function on_mail_error( $wp_error ) {
		echo '<div class="error"><p><pre>';
		// phpcs:disable WordPress.PHP.DevelopmentFunctions.error_log_print_r
		print_r( $wp_error->errors );
		echo '</pre></div>';
	}

	/**
	 * Executed by `wp_mail_content_type` filter hook
	 *
	 * @return string
	 */
	public function mail_content_type() {
		return 'text/html';
	}

	/**
	 * Sets one header field for the email.
	 *
	 * @param string $name Name of the field to change. Pe `X-Return-Path`.
	 * @param string $val Value of the field.
	 *
	 * @return self
	 */
	public function set_header( $name, $val ) {
		$this->the_headers[ $name ] = $val;
		return $this;
	}

	/**
	 * Changes the `To:` field of the mail.
	 *
	 * @param string $val Email of the `To` field.
	 * @return self
	 */
	public function to( $val ) {
		$this->the_to = $val;
		return $this;
	}

	/**
	 * Sets the email subject.
	 *
	 * @param string $val The email subject.
	 * @return self
	 */
	public function subject( $val ):self {
		$this->the_subject = $val;
		return $this;
	}

	/**
	 * Sets the email body.
	 *
	 * @param string $val The email body.
	 * @return self
	 */
	public function body( $val ):self {
		$this->the_body = $val;
		return $this;
	}

	/**
	 * Adds an attachment to the email. Can be called multiple times.
	 *
	 * @param string $val The PATH to the attachment.
	 * @return self
	 */
	public function attachment( $val ):self {
		$this->the_attachments[] = $val;
		return $this;
	}

	/**
	 * Sends the email with the configured data using `wp_mail`.
	 *
	 * @return bool
	 */
	public function send() {
		return wp_mail( $this->the_to, $this->the_subject, $this->the_body, $this->the_headers, $this->the_attachments );
	}

}
