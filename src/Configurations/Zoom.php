<?php


namespace SergeLiatko\WPGmaps\Configurations;

/**
 * Class Zoom
 *
 * @package SergeLiatko\WPGmaps\Configurations
 */
class Zoom {

	public const DEFAULT = 14;

	/**
	 * @var int|null $zoom
	 */
	protected $zoom;

	/**
	 * Zoom constructor.
	 *
	 * @param int|null $zoom
	 */
	public function __construct( ?int $zoom = self::DEFAULT ) {
		$this->zoom = $zoom;
	}

	/**
	 * @return string
	 */
	public function __toString(): string {
		return strval( $this->getZoom() );
	}

	/**
	 * @return int|null
	 */
	public function getZoom(): ?int {
		return $this->zoom;
	}

	/**
	 * @param int|null $zoom
	 *
	 * @return Zoom
	 */
	public function setZoom( ?int $zoom = self::DEFAULT ): Zoom {
		$this->zoom = is_null( $zoom ) ? $zoom
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
		return range( 0, 21, 1 );
	}

}
