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
	protected $_headers = array(
		'Replay-To' => 'Mario Yepes <mario.yepes@dazzet.co>',
	);

	/**
	 * Sets the To: mail field.
	 *
	 * @var string
	 */
	protected $_to = 'marioy47@gmail.com';

	/**
	 * The Subject: email field.
	 *
	 * @var string
	 */
	protected $_subject = 'Get Well Card';

	/**
	 * The message body content.
	 *
	 * @var string
	 */
	protected $_body = 'Message body';

	/**
	 * Array of paths to attachments
	 *
	 * @var array
	 */
	protected $_attachments = array();

	/**
	 * Adds the required actions to WordPress
	 * - New mail error handling functinos
	 * - New page on the submenu
	 *
	 * @return self
	 */
	private function __constructor(): self {
		add_action( 'wp_mail_failed', array( $this, 'on_mail_error' ), 10, 1 );
		add_filter( 'wp_mail_content_type', array( $this, 'mail_content_type' ) );

		return $this;
	}

	/**
	 * Factory.
	 *
	 * @return self
	 */
	static function get_instance() {
		static $obj;
		return isset( $obj ) ? $obj : $obj = new self();
	}

	/**
	 * Mail error handling function.
	 */
	public function on_mail_error( $wp_error ) {
		echo '<div class="error"><p><pre>';
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
		$this->_headers[ $name ] = $val;
		return $this;
	}

	/**
	 * Changes the `To:` field of the mail.
	 *
	 * @param string $val Email of the `To` field.
	 * @return self
	 */
	public function to( $val ) {
		$this->_to = $val;
		return $this;
	}

	public function subject( $val ) {
		$this->_subject = $val;
		return $this;
	}
	public function body( $val ) {
		$this->_body = $val;
		return $this;
	}

	public function attachment( $val ) {
		$this->_attachments[] = $val;
		return $this;
	}

	public function send() {
		return wp_mail( $this->_to, $this->_subject, $this->_body, $this->_headers, $this->_attachments );
	}

}
