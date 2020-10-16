<?php


namespace SergeLiatko\WPGmaps\Options;

/**
 * Class Region
 *
 * @package SergeLiatko\WPGmaps\Options
 */
class Region {

	/**
	 * @var string $region
	 */
	protected $region;

	/**
	 * Region constructor.
	 *
	 * @param string $region
	 */
	public function __construct( string $region = '' ) {
		$this->setRegion( $region );
	}

	/**
	 * @return string
	 */
	public function __toString(): string {
		return strval( $this->getRegion() );
	}


	/**
	 * @return string
	 */
	public function getRegion(): string {
		return $this->region;
	}

	/**
	 * @param string $region
	 *
	 * @return Region
	 */
	public function setRegion( string $region = '' ): Region {
		$this->region = ( false === ( $rg = substr( strtolower( strval( $region ) ), 0, 2 ) ) ) ?
			null
			: $rg;

		return $this;
	}

}
