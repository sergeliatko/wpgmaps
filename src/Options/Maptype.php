<?php


namespace SergeLiatko\WPGmaps\Options;


/**
 * Class Maptype
 *
 * @package SergeLiatko\WPGmaps\Options
 */
class Maptype {

	public const ROADMAP   = 'roadmap';
	public const SATELLITE = 'satellite';
	public const HYBRID    = 'hybrid';
	public const TERRAIN   = 'terrain';
	public const DEFAULT   = self::ROADMAP;

	/**
	 * @var string|null
	 */
	protected ?string $maptype;

	/**
	 * Maptype constructor.
	 *
	 * @param string|null $maptype
	 */
	public function __construct( ?string $maptype = null ) {
		$this->setMaptype( $maptype );
	}

	/**
	 * @return string
	 */
	public function __toString(): string {
		return $this->getMaptype();
	}


	/**
	 * @return string
	 */
	public function getMaptype(): string {
		return $this->maptype;
	}

	/**
	 * @param string|null $maptype
	 *
	 * @return Maptype
	 */
	public function setMaptype( ?string $maptype = null ): Maptype {
		$this->maptype = is_null( $maptype ) ?
			''
			: ( in_array( $maptype, $this->getAllowedMaptypes() ) ? $maptype : $this->getDefaultMaptype() );

		return $this;
	}

	/**
	 * @return string
	 */
	public function getDefaultMaptype(): string {
		return self::DEFAULT;
	}

	/**
	 * @return string[]
	 */
	protected function getAllowedMaptypes(): array {
		return array( self::ROADMAP, self::SATELLITE, self::HYBRID, self::TERRAIN );
	}

}
