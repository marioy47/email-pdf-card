<?php
/**
 * Creates a PDF to display inline.
 *
 * @package Email_Pdf_Card
 */

namespace EmailPdfCard;

/**
 * Creates a PDF from Query Parammeters.
 */
class Generate_Display_Pdf {

	/**
	 * Singleton.
	 */
	private function __construct() {

	}

	/**
	 * Factory.
	 */
	public static function get_instance() {
		static $obj;
		return isset( $obj ) ? $obj : $obj = new self();
	}

	/**
	 * Calls add_action and add_filter hooks.
	 *
	 * @return self
	 */
	public function wp_hooks(): self {
		add_filter( 'query_vars', array( $this, 'query_vars' ) );
		add_action( 'parse_request', array( $this, 'parse_request' ) );
		return $this;
	}

	/**
	 * Adds new Url Query Parammeters to watch for.
	 *
	 * @param array $vars Array passed by WordPress.
	 * @return array
	 */
	public function query_vars( $vars ): array {
		$new_vars = array( $this->url_param );
		$vars     = $new_vars + $vars;
		return $vars;
	}

	/**
	 * If a URL has the registered query_vars it will output a PDF.
	 *
	 * @param stdClass $wp The WordPress object.
	 */
	public function parse_request( $wp ) {
		if ( ! array_key_exists( $this->url_param, $wp->query_vars ) ) {
			return;
		}

		$html = <<<EOF
<style>
@page {
    margin-top: 25%;
    margin-left: 45%;
    background-image: url(https://images.unsplash.com/photo-1532274402911-5a369e4c4bb5?ixlib=rb-0.3.5&ixid=eyJhcHBfaWQiOjEyMDd9&s=81a5f1725ca68c549e0054dcfdf269de&w=1000&q=80);
    background-image-resize: 6;
    padding-right: 25%;
}

body {
    font-family: sans-serif;

}
</style>
<body>
<h1>mPDF</h1>
<h2>Backgrounds & Borders</h2>
</body>
EOF;

		$mpdf = new \Mpdf\Mpdf(
			array(
				'mode'        => 'utf-8',
				'format'      => 'A4-L',
				'orientation' => 'L',
			)
		);
		$mpdf->SetDisplayMode( 'fullpage' );
		$mpdf->WriteHTML( $html );
		$mpdf->Output();
		exit;
	}

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
