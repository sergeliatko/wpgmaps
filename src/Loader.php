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

}
