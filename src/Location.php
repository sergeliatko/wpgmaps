<?php


namespace SergeLiatko\WPGmaps;

/**
 * Class Location
 *
 * @package SergeLiatko\WPGmaps
 */
class Location {

	/**
	 * @var float
	 */
	protected $latitude;

	/**
	 * @var float
	 */
	protected $longitude;

	/**
	 * Location constructor.
	 *
	 * @param float $latitude
	 * @param float $longitude
	 */
	public function __construct( float $latitude, float $longitude ) {
		$this->setLatitude( $latitude );
		$this->setLongitude( $longitude );
	}

	/**
	 * @return string
	 */
	public function __toString(): string {
		return join(
			',',
			array_map(
				'strval',
				array( $this->getLatitude(), $this->getLongitude() )
			)
		);
	}


	/**
	 * @return float
	 */
	public function getLatitude(): float {
		return $this->latitude;
	}

	/**
	 * @param float $latitude
	 *
	 * @return Location
	 */
	public function setLatitude( float $latitude ): Location {
		$this->latitude = $this->sanitizeLatitude( $latitude );

		return $this;
	}

	/**
	 * @return float
	 */
	public function getLongitude(): float {
		return $this->longitude;
	}

	/**
	 * @param float $longitude
	 *
	 * @return Location
	 */
	public function setLongitude( float $longitude ): Location {
		$this->longitude = $this->sanitizeLongitude( $longitude );

		return $this;
	}

	/**
	 * @param float $latitude
	 *
	 * @return float
	 */
	protected function sanitizeLatitude( float $latitude ): float {
		return $this->sanitizeCoordinate( $latitude, - 90.0, 90.0 );
	}

	/**
	 * @param float $longitude
	 *
	 * @return float
	 */
	protected function sanitizeLongitude( float $longitude ): float {
		return $this->sanitizeCoordinate( $longitude, - 180.0, 180.0 );
	}

	/**
	 * @param float $coordinate
	 * @param float $lower
	 * @param float $upper
	 *
	 * @return float
	 */
	protected function sanitizeCoordinate( float $coordinate, float $lower, float $upper ): float {
		return ( ( $lower <= $coordinate ) && ( $upper >= $coordinate ) ) ? $coordinate : 0.0;
	}

}
