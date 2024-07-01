<?php


namespace SergeLiatko\WPGmaps;

/**
 * Class Loader
 *
 * @package SergeLiatko\WPGmaps
 */
class Loader {

	/**
	 * @var Loader|null $instance
	 */
	protected static ?Loader $instance = null;

	/**
	 * @var Map[]
	 */
	protected array $maps;

	/**
	 * Loader constructor.
	 */
	protected function __construct() {
		$this->setMaps( [] );
		if ( is_admin() ) {
			add_action( 'admin_footer', array( $this, 'registerScripts' ), 10, 0 );
		} else {
			add_action( 'wp_footer', array( $this, 'registerScripts' ), 10, 0 );
		}
	}

	/**
	 * @return Loader
	 */
	public static function getInstance(): Loader {
		if ( ! self::$instance instanceof Loader ) {
			self::setInstance( new self() );
		}

		return self::$instance;
	}

	/**
	 * @param Loader $instance
	 */
	public static function setInstance( Loader $instance ): void {
		self::$instance = $instance;
	}

	/**
	 * @param Map $map
	 *
	 * @return int
	 */
	public static function registerMap( Map $map ): int {
		$loader = self::getInstance();

		return $loader->addMap( $map );
	}

	/**
	 * @return Map[]
	 */
	public function getMaps(): array {
		return $this->maps;
	}

	/**
	 * @param Map[] $maps
	 *
	 * @return Loader
	 */
	public function setMaps( array $maps ): Loader {
		$this->maps = $maps;

		return $this;
	}

	/**
	 * @param Map $map
	 *
	 * @return int
	 */
	public function addMap( Map $map ): int {
		$maps   = $this->getMaps();
		$maps[] = $map;
		$this->setMaps( $maps );

		return ( count( $maps ) - 1 );
	}

	/**
	 * Loads scripts in footer.
	 */
	public function registerScripts(): void {
		wp_enqueue_script(
			'wpgmaps',
			self::maybeMinify( self::pathToUrl(
				dirname( __FILE__, 2 ) . '/includes/js/wpgmaps.js'
			) ),
			[],
			null,
			true
		);
		wp_localize_script(
			'wpgmaps',
			'wpgmaps',
			[
				'loaderUrl' => 'https://unpkg.com/@googlemaps/js-api-loader@^1.16.6/dist/index.min.js',
				'maps'      => $this->getMapsData(),
			]
		);
		wp_enqueue_style(
			'wpgmaps-styles',
			self::maybeMinify( self::pathToUrl(
				dirname( __FILE__, 2 ) . '/includes/css/wpgmaps-styles.css'
			) ),
			array(),
			null,
		);
	}

	/**
	 * @return array
	 */
	protected function getMapsData(): array {
		$data = array();
		foreach ( $this->getMaps() as $map ) {
			$data[] = $map->getOptions()->__toArray();
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
	 * @param string $url
	 *
	 * @return string
	 * @noinspection PhpUnused
	 */
	protected static function maybeMinify( string $url = '' ): string {
		if ( empty( $url ) ) {
			return $url;
		}

		$min = self::min();

		return empty( $min ) ?
			$url
			: preg_replace( '/(?<!\.min)(\.js|\.css)/', "$min$1", $url );
	}

	/**
	 * Returns .min if script debug is not enabled.
	 *
	 * @return string
	 */
	protected static function min(): string {
		return ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ? '' : '.min';
	}

}
