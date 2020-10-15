<?php


namespace SergeLiatko\WPGmaps\Markers;

/**
 * Class Size
 *
 * @package SergeLiatko\WPGmaps\Markers
 */
class Size {

	public const DEFAULT = '';
	public const MID     = 'mid';
	public const SMALL   = 'small';
	public const TINY    = 'tiny';

	/**
	 * @var string $size
	 */
	protected $size;

	/**
	 * Size constructor.
	 *
	 * @param string $size
	 */
	public function __construct( string $size = '' ) {
		$this->size = $size;
	}

	/**
	 * @return string
	 */
	public function __toString(): string {
		return strval( $this->getSize() );
	}

	/**
	 * @return string
	 */
	public function getSize(): string {
		return $this->size;
	}

	/**
	 * @param string $size
	 *
	 * @return Size
	 */
	public function setSize( string $size = '' ): Size {
		$this->size = in_array( $size, $this->getAllowedSizes() ) ? $size : $this->getDefaultSize();

		return $this;
	}

	/**
	 * @return string
	 */
	public function getDefaultSize(): string {
		return self::DEFAULT;
	}

	/**
	 * @return string[]
	 */
	protected function getAllowedSizes(): array {
		return array( self::MID, self::SMALL, self::TINY );
	}

}
