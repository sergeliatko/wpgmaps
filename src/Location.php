<?php


namespace SergeLiatko\WPGmaps;

/**
 * Class Location
 *
 * @package SergeLiatko\WPGmaps
 */
class Location {

	/**
	 * @var string
	 */
	protected string $latitude;

	/**
	 * @var string
	 */
	protected string $longitude;

	/**
	 * Location constructor.
	 *
	 * @param string $latitude
	 * @param string $longitude
	 */
	public function __construct( string $latitude, string $longitude ) {
		$this->setLatitude( $latitude );
		$this->setLongitude( $longitude );
	}

	/**
	 * @return string
	 */
	public function __toString(): string {
		return join(
			',',
			array( $this->getLatitude(), $this->getLongitude() )
		);
	}

	/**
	 * @return string[]
	 */
	public function __toArray(): array {
		return array(
			'latitude'  => $this->getLatitude(),
			'longitude' => $this->getLongitude(),
		);
	}

	/**
	 * @return string
	 */
	public function getLatitude(): string {
		return $this->latitude;
	}

	/**
	 * @param string $latitude
	 *
	 * @return Location
	 */
	public function setLatitude( string $latitude ): Location {
		$this->latitude = $this->sanitizeLatitude( $latitude );

		return $this;
	}

	/**
	 * @return string
	 */
	public function getLongitude(): string {
		return $this->longitude;
	}

	/**
	 * @param string $longitude
	 *
	 * @return Location
	 */
	public function setLongitude( string $longitude ): Location {
		$this->longitude = $this->sanitizeLongitude( $longitude );

		return $this;
	}

	/**
	 * @param string $latitude
	 *
	 * @return string
	 */
	protected function sanitizeLatitude( string $latitude ): string {
		return $this->formatCoordinate(
			$this->sanitizeCoordinate(
				(float) $latitude,
				- 90.0,
				90.0
			)
		);
	}

	/**
	 * @param string $longitude
	 *
	 * @return string
	 */
	protected function sanitizeLongitude( string $longitude ): string {
		return $this->formatCoordinate(
			$this->sanitizeCoordinate(
				(float) $longitude,
				- 180.0,
				180.0
			)
		);
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

	/**
	 * @param float $coordinate
	 *
	 * @return string
	 */
	protected function formatCoordinate( float $coordinate ): string {
		return number_format( $coordinate, 8, '.', '' );
	}

}
