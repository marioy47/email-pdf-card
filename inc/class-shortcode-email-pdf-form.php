<?php
/**
 * Creates the shortcode.
 *
 * @package Email_Pdf_Card
 */

namespace EmailPdfCard;

/**
 * Creates the shortcode and registers it in shortcodes ultimate.
 */
class Shortcode_Email_Pdf_Form {

	/**
	 * Singleton.
	 */
	private function __construct() {
	}

	/**
	 * Factory.
	 */
	public static function get_instance(): self {
		static $obj;
		return isset( $obj ) ? $obj : $obj = new self();
	}

	/**
	 * Calls the add_action and add_filter hooks.
	 *
	 * @return self
	 */
	public function wp_hooks(): self {

		add_action( 'wp_enqueue_scripts', array( $this, 'register_scripts' ) );

		// Creacion del shortcode y el su-shortcode (shortcoes ultimate).
		add_shortcode( $this->shortcode_name, array( $this, 'shortcode' ) );
		add_shortcode( get_option( 'su_option_prefix' ) . $this->shortcode_name, array( $this, 'shortcode' ) );

		// Add ajax endpoint.
		add_action( 'wp_ajax_nopriv_' . $this->plugin_slug, array( $this, 'ajax_process' ) );
		add_action( 'wp_ajax_' . $this->plugin_slug, array( $this, 'ajax_process' ) );

		// Creacion de la interfaz en shortocodes ultimate.

		return $this;
	}

	/**
	 * Registers the scripts that will be used in the shortcode.
	 */
	public function register_scripts() {
		wp_register_script( $this->plugin_slug, plugin_dir_url( $this->plugin_file ) . 'js/email-pdf.js', array(), EMAIL_PDF_CARD_VERSION, true );
	}

	/**
	 * Creates the shortcode output.
	 *
	 * @param array $atts Attributes the shortcode recieves from the front-end.
     * @phpcs:disable Squiz.Commenting.FileComment.Missing
	 */
	public function shortcode( $atts ) {
		$opts = get_option( $this->options_key, array( 'recipients' => '' ) );
		$atts = shortcode_atts(
			array(
				'recipients' => str_replace( "\n", ',', $opts['recipients'] ),
			),
			$atts
		);
		if ( empty( $atts['recipients'] ) ) {
			return __( 'You have to provide a list of email recipients separated by commas', 'email-pdf' );
		}
		$recipients = array_map(
			function( $item ) {
				list($email, $label) = explode( '|', $item . '|' );
				if ( empty( $label ) ) {
					$label = $email;
				}
				return array(
					'email' => trim( $email ),
					'label' => trim( $label ),
				);
			},
			(array) explode( ',', $atts['recipients'] )
		);
		$form_id    = uniqid( 'email-pdf-form-' );
		wp_localize_script(
			$this->plugin_slug,
			'email_pdf_data', // Debe ser igual que en el JavaScript.
			array(
				'ajaxurl' => admin_url( 'admin-ajax.php' ),
				'formid'  => $form_id,
				'action'  => $this->plugin_slug,
			)
		);
		wp_enqueue_script( $this->plugin_slug );
		ob_start();
		?>
		<form id="<?php echo esc_attr( $form_id ); ?>" method="post">
			<?php wp_nonce_field( plugin_basename( __FILE__ ), 'ihs-get-well-nonce' ); ?>
			<h3><?php esc_html_e( 'Recipient Information', 'email-pdf' ); ?></h3>
			<div class="email-pdf-group">
				<label><?php esc_html_e( 'Select the recipient', 'email-pdf' ); ?></label>
				<select>
				<option value=""><?php esc_html_e( 'Select a recipient', 'email-pdf' ); ?></option>
					<?php foreach ( $recipients as $item ) : ?>
						<option value="<?php echo esc_attr( $item['email'] ); ?>"><?php echo esc_attr( $item['label'] ); ?></option>
					<?php endforeach; ?>
				</select>
			</div>

			<h3><?php esc_html_e( 'Sender Information', 'email-pdf' ); ?></h3>
			<div class="email-pdf-group my-name">
				<label><?php esc_html_e( 'My Name', 'email-pdf' ); ?></label>
				<input type="text" name="my_name" />
			</div>
			<div class="get-well-group">
				<label><?php esc_html_e( 'My Email', 'email-pdf' ); ?></label>
				<input type="email" name="my_email" />
			</div>
			<h3><?php esc_html_e( 'PDF Message', 'email-pdf' ); ?></h3>
			<div class="get-well-group message">
				<label><?php esc_html_e( 'Card Message', 'email-pdf' ); ?></label>
				<textarea name="message"></textarea>
			</div>

			<button><?php esc_html_e( 'Preview', 'email-pdf' ); ?></button>

			<h3><?php esc_html_e( 'Preview', 'email-pdf' ); ?></h3>
		<?php // phpcs:disable WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			<iframe id="#email-pdf-card-preview" src="<?php echo get_site_url() . '?' . $this->url_param . '=1#toolbar=0'; ?>" style="width: 100%; height: 50vh"></iframe>

			<div class="notices"></div>

			<input type="submit" name="send_pdf" value="<?php esc_html_e( 'Submit Data', 'email-pdf' ); ?>">
		</form>
		<?php
		$ret = ob_get_contents();
		ob_end_clean();
		return $ret;
	}

	/**
	 * Creates the ajax endpoint to receive the email-pdf-form data and send the pdf.
	 */
	public function ajax_process() {
		if ( isset( $_POST[ $this->nonce_name ] ) ) {
			return wp_send_json(
				array(
					'success' => false,
					'message' => __( 'Nonce not found', 'email-pdf' ),
				)
			);
		}

		if ( ! wp_verify_nonce( $_POST[ $this->nonce_name ], plugin_basename( __FILE__ ) ) ) {
			return wp_send_json(
				array(
					'success' => false,
					'message' => __( 'Invalid nonce', 'email-pdf' ),
				)
			);
		}

		return wp_send_json(
			array(
				'success' => true,
				'message' => __( 'Email sent', 'email-pdf' ),
			)
		);

	}

	/**
	 * This is a special name that the WordPress ajax needs.
	 *
	 * @var string
	 */
	protected $plugin_slug;

	/**
	 * Sets the slug of the plugin for the js handles.
	 *
	 * @param string $slug The plugin slug.
	 *
	 * @return self
	 */
	public function set_slug( $slug ): self {
		$this->plugin_slug = $slug;
		return $this;
	}

	/**
	 * The name the shortcode will have.
	 *
	 * @var string
	 */
	protected $shortcode_name;

	/**
	 * Sets the shortcode name.
	 *
	 * @param string $name The name the shortcode will have.
	 */
	public function set_shortcode_name( $name ): self {
		$this->shortcode_name = $name;
		return $this;
	}

	/**
	 * Path to the initial plugin file.
	 *
	 * @var string
	 */
	protected $plugin_file;

	/**
	 * Sets the path to the plugin file.
	 *
	 * @param string $file The path to the email-pdf-card.php file.
	 */
	public function set_file( $file ): self {
		$this->plugin_file = $file;
		return $this;
	}

	/**
	 * All plugins options are stored in an array. This is the key of the array.
	 *
	 * @var string P.e email_pdf.
	 */
	protected $options_key;

	/**
	 * Sets the key name of the var for the get_option().
	 *
	 * @param string $key key name for all options.
	 * @return self
	 */
	public function set_options_key( $key ): self {
		$this->options_key = $key;
		return $this;
	}

	/**
	 * String used in the ajax call verification.
	 *
	 * @var string
	 */
	private $nonce_name = 'email-pdf-form-nonce';

	/**
	 * URL param to watch out for.
	 *
	 * @var string
	 */
	protected $url_param;

	/**
	 * Sets the URL param to watch out for so the PDF gets generated
	 *
	 * @param string $param the URL param name.
	 * @return self
	 */
	public function set_url_param( $param ): self {
		$this->url_param = $param;
		return $this;
	}
}
