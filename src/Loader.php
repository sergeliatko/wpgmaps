<?php


namespace SergeLiatko\WPGmaps;

/**
 * Class Loader
 *
 * @package SergeLiatko\WPGmaps
 */
class Loader {

	/**
	 * @var \SergeLiatko\WPGmaps\Loader $instance
	 */
	protected static $instance;

	/**
	 * @var \SergeLiatko\WPGmaps\Map[]
	 */
	protected $maps;

	/**
	 * Loader constructor.
	 */
	protected function __construct() {
		$this->setMaps( array() );
		if ( is_admin() ) {
			add_action( 'admin_footer', array( $this, 'registerScripts' ), 10, 0 );
		} else {
			add_action( 'wp_footer', array( $this, 'registerScripts' ), 10, 0 );
		}
	}

	/**
	 * @return \SergeLiatko\WPGmaps\Loader
	 */
	public static function getInstance() {
		if ( !self::$instance instanceof Loader ) {
			self::setInstance( new self() );
		}

		return self::$instance;
	}

	/**
	 * @param \SergeLiatko\WPGmaps\Loader $instance
	 */
	public static function setInstance( Loader $instance ) {
		self::$instance = $instance;
	}

	/**
	 * @param \SergeLiatko\WPGmaps\Map $map
	 *
	 * @return int
	 */
	public static function registerMap( Map $map ): int {
		$loader = self::getInstance();

		return $loader->addMap( $map );
	}

	/**
	 * @return \SergeLiatko\WPGmaps\Map[]
	 */
	public function getMaps(): array {
		return $this->maps;
	}

	/**
	 * @param \SergeLiatko\WPGmaps\Map[] $maps
	 *
	 * @return Loader
	 */
	public function setMaps( array $maps ): Loader {
		$this->maps = $maps;

		return $this;
	}

	/**
	 * @param \SergeLiatko\WPGmaps\Map $map
	 *
	 * @return int
	 */
	public function addMap( Map $map ): int {
		$maps = $this->getMaps();
		array_push( $maps, $map );
		$this->setMaps( $maps );

		return ( count( $maps ) - 1 );
	}

	/**
	 * Loads scripts in footer.
	 */
	public function registerScripts() {
		wp_register_script(
			'wpgmaps-loader',
			'https://unpkg.com/@googlemaps/js-api-loader@^1.2.0/dist/index.min.js',
			array(),
			null,
			true
		);
		wp_enqueue_script(
			'wpgmaps',
			self::maybeMinify( self::pathToUrl(
				dirname( __FILE__, 2 ) . '/includes/js/wpgmaps.js'
			) ),
			array( 'wpgmaps-loader' ),
			null,
			true
		);
		wp_localize_script(
			'wpgmaps',
			'wpgmaps',
			array(
				'maps' => $this->getMapsData(),
			)
		);
		wp_enqueue_style(
			'wpgmaps-styles',
			self::maybeMinify( self::pathToUrl(
				dirname( __FILE__, 2 ) . '/includes/css/wpgmaps-styles.css'
			) ),
			array(),
			null,
			'all'
		);
	}

	/**
	 * @return array
	 */
	protected function getMapsData(): array {
		$data = array();
		foreach ( $this->getMaps() as $map ) {
			array_push( $data, $map->getOptions()->__toArray() );
		}

		return $data;
	}

	/**
	 * @param string $path
	 *
	 * @return string
	 * @noinspection PhpUnused
	 */
	protected static function pathToUrl( string $path ): string {
		return esc_url_raw(
			str_replace(
				wp_normalize_path( untrailingslashit( ABSPATH ) ),
				site_url(),
				wp_normalize_path( $path )
			),
			array( 'http', 'https' )
		);
	}

	/**
	 * @param $url
	 *
	 * @return string
	 * @noinspection PhpUnused
	 */
	protected static function maybeMinify( $url ) {
		$min = self::min();

		return empty( $min ) ?
			$url
			: preg_replace( '/(?<!\.min)(\.js|\.css)/', "{$min}$1", $url );
	}

	/**
	 * Returns .min if script debug is not enabled.
	 *
	 * @return string
	 */
	protected static function min() {
		return ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ? '' : '.min';
	}

}
