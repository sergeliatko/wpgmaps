<?php


namespace SergeLiatko\WPGmaps;

/**
 * Class Pin
 *
 * @package SergeLiatko\WPGmaps
 */
class Pin {

	/**
	 * @var Marker $marker
	 */
	protected Marker $marker;

	/**
	 * @var string $window
	 */
	protected string $window;

	/**
	 * Pin constructor.
	 *
	 * @param Marker $marker
	 * @param string                      $window
	 */
	public function __construct( Marker $marker, string $window = '' ) {
		$this->setMarker( $marker );
		$this->setWindow( $window );
	}

	/**
	 * @return array
	 */
	public function __toArray(): array {
		return array(
			'marker' => $this->getMarker()->__toArray(),
			'window' => $this->getWindow(),
		);
	}

	/**
	 * @return Marker
	 */
	public function getMarker(): Marker {
		return $this->marker;
	}

	/**
	 * @param Marker $marker
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
