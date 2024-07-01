<?php


namespace SergeLiatko\WPGmaps\Options;

/**
 * Class Zoom
 *
 * @package SergeLiatko\WPGmaps\Options
 */
class Zoom {

	public const DEFAULT = 15;

	/**
	 * @var int|null $zoom
	 */
	protected ?int $zoom;

	/**
	 * Zoom constructor.
	 *
	 * @param int|null $zoom
	 */
	public function __construct( ?int $zoom = null ) {
		$this->setZoom( $zoom );
	}

	/**
	 * @return string
	 */
	public function __toString(): string {
		return strval( $this->getZoom() );
	}

	/**
	 * @return int|string
	 */
	public function getZoom(): int|string {
		return $this->zoom;
	}

	/**
	 * @param int|null $zoom
	 *
	 * @return Zoom
	 */
	public function setZoom( ?int $zoom = null ): Zoom {
		$this->zoom = is_null( $zoom ) ? ''
			: ( in_array( $zoom, $this->getAllowedRange() ) ? $zoom : $this->getDefaultZoom() );

		return $this;
	}

	/**
	 * @return int
	 */
	public function getDefaultZoom(): int {
		return self::DEFAULT;
	}

	/**
	 * @return array
	 */
	protected function getAllowedRange(): array {
		return range( 0, 21 );
	}

}
