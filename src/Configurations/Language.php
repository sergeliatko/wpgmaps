<?php


namespace SergeLiatko\WPGmaps\Configurations;

/**
 * Class Language
 *
 * @package SergeLiatko\WPGmaps\Configurations
 */
class Language {

	/**
	 * @var string|null $language
	 */
	protected $language;

	/**
	 * Language constructor.
	 *
	 * @param string|null $language
	 */
	public function __construct( ?string $language = null ) {
		$this->setLanguage( $language );
	}

	/**
	 * @return string
	 */
	public function __toString(): string {
		return strval( $this->getLanguage() );
	}

	/**
	 * @return string|null
	 */
	public function getLanguage(): ?string {
		return $this->language;
	}

	/**
	 * @param string|null $language
	 *
	 * @return Language
	 */
	public function setLanguage( ?string $language = null ): Language {
		$this->language = ( false === ( $lg = substr( strtolower( strval( $language ) ), 0, 2 ) ) ) ?
			null
			: $lg;

		return $this;
	}

}
