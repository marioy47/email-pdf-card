<?php
/**
 * Creates the Settings Page.
 *
 * @package Email_Pdf_Card
 */

namespace EmailPdfCard;

/**
 * Creates the settigs page.
 */
class Settings_Page {


	/**
	 * Singleton.
	 */
	private function __construct() {
	}

	/**
	 * Factory.
	 *
	 * @return self
	 */
	public static function get_instance(): self {
		static $obj;
		return isset( $obj ) ? $obj : $obj = new self();
	}

	/**
	 * Calls add_action and add_filter hooks from WordPress.
	 *
	 * @return self
	 */
	public function wp_hooks(): self {
		add_action( 'admin_enqueue_scripts', array( $this, 'register_scripts' ) );

		add_action( 'admin_menu', array( $this, 'register_page' ) );
		add_action( 'admin_init', array( $this, 'register_fields' ) );

		return $this;
	}

	/**
	 * Register (not enqueue) the script that enables image selection on the dashboard.
	 */
	public function register_scripts() {
		wp_register_script( $this->plugin_slug . '-admin', plugin_dir_url( $this->plugin_file ) . 'js/email-pdf-admin.js', array(), EMAIL_PDF_CARD_VERSION, false );
	}

	/**
	 * Regisers the actual page in the dashboard under `Settings`
	 */
	public function register_page() {
		add_options_page(
			__( 'Email PDF Card', 'email-pdf' ),
			__( 'Email PDF Card', 'email-pdf' ),
			'manage_options',
			$this->plugin_slug,
			array( $this, 'create_page' )
		);
	}

	/**
	 * Creates the HTML for the settings page.
	 *
	 * @phpcs:disable Squiz.Commenting.FileComment.Missing
	 */
	public function create_page() {
		$this->options = get_option( $this->options_key, array( 'images' => array() ) );
		?>
		<div class="wrap">
			<h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
			<form method="post" action="options.php">
				<?php settings_fields( 'email-pdf' ); ?>
				<?php do_settings_sections( 'email-pdf' ); ?>
				<?php submit_button(); ?>
			</form>
		</div>

		<?php
	}

	/**
	 * Fields and section creation for the settings page.
	 */
	public function register_fields() {
		register_setting( 'email-pdf', $this->options_key );

		add_settings_section(
			'default',
			__( 'Email and PDF configuration', 'email-pdf' ),
			false,
			'email-pdf'
		);

		add_settings_field(
			'default-recipients',
			__( 'Recipients', 'email-pdf' ),
			array( $this, 'field_recipients' ),
			'email-pdf'
		);

		add_settings_section(
			'email-pdf-images',
			__( 'Images', 'email-pdf' ),
			array( $this, 'section_images' ),
			'email-pdf'
		);
	}

	/**
	 * Text field for recipient list.
	 */
	public function field_recipients() {
		$val = array_key_exists( 'recipients', $this->options ) ? $this->options['recipients'] : '';
		echo '<textarea name="' . esc_attr( $this->options_key ) . '[recipients]" placeholder="email@example.com | Name or desc" class="widefat" rows="10">' . esc_attr( $val ) . '</textarea>';
		echo '<p class="description">' . esc_html__( 'One recipient per line. Use the pipe (|) simbol to separate the email from the name', 'email-pdf' ) . '</p>';
	}


	/**
	 * Creates the settings page section Images.
	 *
	 * @phpcs:disable WordPress.Security.EscapeOutput.OutputNotEscaped
	 */
	public function section_images() {
		wp_enqueue_media();
		wp_enqueue_script( $this->plugin_slug . '-admin' );
		esc_html_e( 'Choose the images you want as card backgrounds', 'email-pdf' );
		$images = array_key_exists( 'images', $this->options ) ? $this->options['images'] : array();

		?>
<table class="wp-list-table widefat fixed striped table-view-list" id="email-pdf-images-table" width="90%" align="center">
	<thead>
		<tr>
			<th><?php esc_html_e( 'Images', 'email-pdf' ); ?></th>
			<th width="150px"><?php esc_html_e( 'Actions', 'email-pdf' ); ?></th>
		</tr>
	</thead>
	<tbody>
		<?php foreach ( $images as $v ) : ?>
		<tr class="row">
			<td class="image-url"><a target="_blank" href="<?php echo wp_get_attachment_url( $v ); ?>"><?php echo wp_get_attachment_image( $v ); ?></a></td>
			<td class="image-actions">
				<a class="remove" href="#"><?php esc_html_e( 'Remove', 'email-pdf' ); ?></a>
				<input type="hidden" name="<?php echo $this->options_key; ?>[images][]" value="<?php echo $v; ?>" />
			</td>
		</tr>
		<?php endforeach; ?>
	</tbody>
</table>
<div style="text-align: right">
	<button class="button" id="email-pdf-add-image" style="margin: 1rem 0">Add Image</button>
</div>
<template id="email-pdf-template">
	<tr class="row">
		<td class="image-url"><a target="_blank" href=""><img class="aligncenter" /></a></td>
		<td class="image-actions">
			<a class="remove" href="#"><?php esc_html_e( 'Remove', 'email-pdf' ); ?></a>
			<input type="hidden" name="<?php echo $this->options_key; ?>[images][]" value="" />
		</td>
	</tr>
</template>
		<?php
	}

	/**
	 * The path to the plugin initial file.
	 *
	 * @var string
	 */
	protected $plugin_file;

	/**
	 * Sets the path to the plugin initial file.
	 *
	 * @param string $file The path to set.
	 * @return self
	 */
	public function set_file( $file ): self {
		$this->plugin_file = $file;
		return $this;
	}

	/**
	 * The plugin slug for admin links.
	 *
	 * @var string
	 */
	protected $plugin_slug;

	/**
	 * Sets the plugin slug.
	 *
	 * @param string $slug The slug.
	 * @return self
	 */
	public function set_slug( $slug ): self {
		$this->plugin_slug = $slug;
		return $this;
	}

	/**
	 * Store all the options of this plugin
	 *
	 * @var array
	 */
	private $options = array();

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

}
