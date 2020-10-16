<?php


namespace SergeLiatko\WPGmaps\Options;

use SergeLiatko\WPGmaps\Location;

/**
 * Class Center
 *
 * @package SergeLiatko\WPGmaps\Options
 */
class Center {

	/**
	 * @var \SergeLiatko\WPGmaps\Location|null
	 */
	protected $location;

	/**
	 * Center constructor.
	 *
	 * @param \SergeLiatko\WPGmaps\Location|null $location
	 */
	public function __construct( ?Location $location = null ) {
		$this->setLocation( $location );
	}

	/**
	 * @return string
	 */
	public function __toString(): string {
		$location = $this->getLocation();

		return ( $location instanceof Location ) ? $location->__toString() : '';
	}

	/**
	 * @return string[]
	 */
	public function __toArray(): array {
		$location = $this->getLocation();

		return ( $location instanceof Location ) ?
			$location->__toArray()
			: array(
				'latitude'  => '',
				'longitude' => '',
			);
	}

	/**
	 * @return \SergeLiatko\WPGmaps\Location|null
	 */
	public function getLocation(): ?Location {
		return $this->location;
	}

	/**
	 * @param \SergeLiatko\WPGmaps\Location|null $location
	 *
	 * @return Center
	 */
	public function setLocation( ?Location $location = null ): Center {
		$this->location = $location;

		return $this;
	}

}
