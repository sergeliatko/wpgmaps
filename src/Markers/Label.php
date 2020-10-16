<?php


namespace SergeLiatko\WPGmaps\Markers;

/**
 * Class Label
 *
 * @package SergeLiatko\WPGmaps\Markers
 */
class Label {

	/**
	 * @var string $label
	 */
	protected $label;

	/**
	 * Label constructor.
	 *
	 * @param string $label
	 */
	public function __construct( string $label = '' ) {
		$this->label = $label;
	}

	/**
	 * @return string
	 */
	public function __toString(): string {
		return strval( $this->getLabel() );
	}

	/**
	 * @return string
	 */
	public function getLabel(): string {
		return $this->label;
	}

	/**
	 * @param string $label
	 *
	 * @return Label
	 */
	public function setLabel( string $label = '' ): Label {
		$this->label = $this->sanitizeLabel( $label );

		return $this;
	}

	/**
	 * @param string $label
	 *
	 * @return string
	 */
	protected function sanitizeLabel( string $label = '' ): string {
		if ( 1 === preg_match( '/([A-Z0-9])/', strtoupper( $label ), $matches ) ) {
			return $matches[0];
		}

		return '';
	}

}
