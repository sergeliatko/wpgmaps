<?php


namespace SergeLiatko\WPGmaps\Options;


/**
 * Class Format
 *
 * @package SergeLiatko\WPGmaps\Options
 */
class Format {

	public const GIF     = 'GIF';
	public const JPEG    = 'JPEG';
	public const PNG     = 'PNG';
	public const DEFAULT = self::PNG;

	/**
	 * @var string $format
	 */
	protected $format;

	/**
	 * Format constructor.
	 *
	 * @param string|null $format
	 */
	public function __construct( ?string $format = self::DEFAULT ) {
		$this->setFormat( $format );
	}

	/**
	 * @return string
	 */
	public function __toString(): string {
		return strval( $this->getFormat() );
	}


	/**
	 * @return string|null
	 */
	public function getFormat(): ?string {
		return $this->format;
	}

	/**
	 * @param string|null $format
	 *
	 * @return Format
	 */
	public function setFormat( ?string $format ): Format {
		$this->format = is_null( $format ) ? $format
			: ( in_array( $format, $this->getAllowedFormats() ) ? $format : $this->getDefaultFormat() );

		return $this;
	}

	/**
	 * @return string
	 */
	public function getDefaultFormat(): string {
		return self::DEFAULT;
	}

	/**
	 * @return string[]
	 */
	protected function getAllowedFormats(): array {
		return array( self::GIF, self::JPEG, self::PNG );
	}

}
