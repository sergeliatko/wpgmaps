<?php


namespace SergeLiatko\WPGmaps\Configurations;

/**
 * Class Region
 *
 * @package SergeLiatko\WPGmaps\Configurations
 */
class Region {

	/**
	 * @var string|null $region
	 */
	protected $region;

	/**
	 * Region constructor.
	 *
	 * @param string|null $region
	 */
	public function __construct( ?string $region = null ) {
		$this->setRegion( $region );
	}

	/**
	 * @return string
	 */
	public function __toString(): string {
		return strval( $this->getRegion() );
	}


	/**
	 * @return string|null
	 */
	public function getRegion(): ?string {
		return $this->region;
	}

	/**
	 * @param string|null $region
	 *
	 * @return Region
	 */
	public function setRegion( ?string $region = null ): Region {
		$this->region = ( false === ( $rg = substr( strtolower( strval( $region ) ), 0, 2 ) ) ) ?
			null
			: $rg;

		return $this;
	}

}
