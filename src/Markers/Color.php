<?php


namespace SergeLiatko\WPGmaps\Markers;

/**
 * Class Color
 *
 * @package SergeLiatko\WPGmaps\Markers
 */
class Color {

	public const BLACK   = 'black';
	public const BROWN   = 'brown';
	public const GREEN   = 'green';
	public const PURPLE  = 'purple';
	public const YELLOW  = 'yellow';
	public const BLUE    = 'blue';
	public const GRAY    = 'gray';
	public const ORANGE  = 'orange';
	public const RED     = 'red';
	public const WHITE   = 'white';

	public const DEFAULT = '';

	protected const PATTERN = '/#[A-F0-9]{6}/';

	/**
	 * @var string $color
	 */
	protected $color;

	/**
	 * Color constructor.
	 *
	 * @param string $color
	 */
	public function __construct( string $color = '' ) {
		$this->setColor( $color );
	}

	/**
	 * @return string
	 */
	public function __toString(): string {
		return strval( $this->getColor() );
	}


	/**
	 * @return string
	 */
	public function getColor(): string {
		return $this->color;
	}

	/**
	 * @param string $color
	 *
	 * @return Color
	 */
	public function setColor( string $color = '' ): Color {
		$this->color = in_array( $color, $this->getAllowedColors() ) ?
			$color
			: $this->sanitize_hex_color( $color );

		return $this;
	}

	/**
	 * @return string
	 */
	public function getDefaultColor(): string {
		return self::DEFAULT;
	}

	/**
	 * @return string[]
	 */
	protected function getAllowedColors(): array {
		return array(
			self::BLACK,
			self::BROWN,
			self::GREEN,
			self::PURPLE,
			self::YELLOW,
			self::BLUE,
			self::GRAY,
			self::ORANGE,
			self::RED,
			self::WHITE,
		);
	}

	/**
	 * @param string $color
	 *
	 * @return string
	 */
	protected function sanitize_hex_color( string $color ): string {
		return ( 1 === preg_match(
				self::PATTERN,
				$color = strtoupper( substr( $color, 0, 7 ) ) )
		) ? $color : '';
	}

}
