<?php


namespace SergeLiatko\WPGmaps;

/**
 * Class Pin
 *
 * @package SergeLiatko\WPGmaps
 */
class Pin {

	/**
	 * @var \SergeLiatko\WPGmaps\Marker $marker
	 */
	protected $marker;

	/**
	 * @var string $window
	 */
	protected $window;

	/**
	 * Pin constructor.
	 *
	 * @param \SergeLiatko\WPGmaps\Marker $marker
	 * @param string                      $window
	 */
	public function __construct( Marker $marker, string $window = '' ) {
		$this->setMarker( $marker );
		$this->setWindow( $window );
	}

	/**
	 * @return \SergeLiatko\WPGmaps\Marker
	 */
	public function getMarker(): Marker {
		return $this->marker;
	}

	/**
	 * @param \SergeLiatko\WPGmaps\Marker $marker
	 *
	 * @return Pin
	 */
	public function setMarker( Marker $marker ): Pin {
		$this->marker = $marker;

		return $this;
	}

	/**
	 * @return string
	 */
	public function getWindow(): string {
		return $this->window;
	}

	/**
	 * @param string $window
	 *
	 * @return Pin
	 */
	public function setWindow( string $window ): Pin {
		$this->window = $window;

		return $this;
	}

}
