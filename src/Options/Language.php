<?php


namespace SergeLiatko\WPGmaps\Options;

/**
 * Class Language
 *
 * @package SergeLiatko\WPGmaps\Options
 */
class Language {

	/**
	 * @var string $language
	 */
	protected $language;

	/**
	 * Language constructor.
	 *
	 * @param string $language
	 */
	public function __construct( string $language = '' ) {
		$this->setLanguage( $language );
	}

	/**
	 * @return string
	 */
	public function __toString(): string {
		return strval( $this->getLanguage() );
	}

	/**
	 * @return string
	 */
	public function getLanguage(): string {
		return $this->language;
	}

	/**
	 * @param string $language
	 *
	 * @return Language
	 */
	public function setLanguage( string $language = '' ): Language {
		$this->language = ( false === ( $lg = substr( strtolower( strval( $language ) ), 0, 2 ) ) ) ?
			null
			: $lg;

		return $this;
	}

}
