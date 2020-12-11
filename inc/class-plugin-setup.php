<?php
/**
 * Plugin setup procedures.
 *
 * @package Email_Pdf_Card
 */

namespace EmailPdfCard;

/**
 * Links under the plugin name on the dashboard.
 */
class Plugin_Setup {

	/**
	 * Singleton.
	 */
	private function __construct() {
	}

	/**
	 * Factory function.
	 *
	 * @return self
	 */
	public static function get_instance(): self {
		static $obj;
		return isset( $obj ) ? $obj : $obj = new self();
	}

	/**
	 * Executes the WordPress hooks add_action and add_filter.
	 *
	 * @return self
	 */
	public function wp_hooks():self {
		$basename = plugin_basename( $this->plugin_file );
		add_filter( 'plugin_action_links_' . $basename, array( $this, 'action_links' ) );

		return $this;
	}

	/**
	 * Creates links under the plugin name in the dashboard's plugin list.
	 *
	 * @param array $links The links passed by WordPress.
	 * @return array
	 */
	public function action_links( $links ): array {
		$links[] = '<a href="' . esc_url( get_admin_url( null, 'options-general.php?page=' . $this->plugin_slug ) ) . '">' . __( 'Settings', 'email-pdf' ) . '</a>';
		return $links;
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
}
