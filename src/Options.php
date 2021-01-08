<?php


namespace SergeLiatko\WPGmaps;

use SergeLiatko\WPGmaps\Options\Center;
use SergeLiatko\WPGmaps\Options\Format;
use SergeLiatko\WPGmaps\Options\Language;
use SergeLiatko\WPGmaps\Options\Maptype;
use SergeLiatko\WPGmaps\Options\Region;
use SergeLiatko\WPGmaps\Options\Zoom;

/**
 * Class Options
 *
 * @package SergeLiatko\WPGmaps
 */
class Options {

	/**
	 * @var string $key
	 */
	protected $key;

	/**
	 * @var \SergeLiatko\WPGmaps\Pin[] $pins
	 */
	protected $pins;

	/**
	 * @var \SergeLiatko\WPGmaps\Options\Maptype|null $maptype
	 */
	protected $maptype;

	/**
	 * @var \SergeLiatko\WPGmaps\Options\Center|null $center
	 */
	protected $center;

	/**
	 * @var \SergeLiatko\WPGmaps\Options\Zoom|null $zoom
	 */
	protected $zoom;

	/**
	 * @var \SergeLiatko\WPGmaps\Options\Language|null $language
	 */
	protected $language;

	/**
	 * @var \SergeLiatko\WPGmaps\Options\Region|null $region
	 */
	protected $region;

	/**
	 * @var \SergeLiatko\WPGmaps\Options\Format|null $format
	 */
	protected $format;

	/**
	 * Options constructor.
	 *
	 * @param string                                     $key
	 * @param \SergeLiatko\WPGmaps\Pin[]                 $pins
	 * @param \SergeLiatko\WPGmaps\Options\Maptype|null  $maptype
	 * @param \SergeLiatko\WPGmaps\Options\Center|null   $center
	 * @param \SergeLiatko\WPGmaps\Options\Zoom|null     $zoom
	 * @param \SergeLiatko\WPGmaps\Options\Language|null $language
	 * @param \SergeLiatko\WPGmaps\Options\Region|null   $region
	 * @param \SergeLiatko\WPGmaps\Options\Format|null   $format
	 */
	public function __construct(
		string $key,
		array $pins = array(),
		?Maptype $maptype = null,
		?Center $center = null,
		?Zoom $zoom = null,
		?Language $language = null,
		?Region $region = null,
		?Format $format = null
	) {
		$this->setKey( $key );
		$this->setPins( $pins );
		$this->setMaptype( $maptype );
		$this->setCenter( $center );
		$this->setZoom( $zoom );
		$this->setLanguage( $language );
		$this->setRegion( $region );
		$this->setFormat( $format );
	}

	/**
	 * @return array
	 */
	public function __toArray(): array {
		return array(
			'key'      => strval( $this->getKey() ),
			'pins'     => array_map(
				function ( $item ) {
					return $item->__toArray();
				},
				$this->getPins()
			),
			'maptype'  => strval( $this->getMaptype() ),
			'center'   => $this->getCenter()->__toArray(),
			'zoom'     => strval( $this->getZoom() ),
			'language' => strval( $this->getLanguage() ),
			'region'   => strval( $this->getRegion() ),
			'format'   => strval( $this->getFormat() ),
		);
	}

	/**
	 * @return string
	 */
	public function getKey(): string {
		return $this->key;
	}

	/**
	 * @param string $key
	 *
	 * @return Options
	 */
	public function setKey( string $key ): Options {
		$this->key = $this->sanitizeKey( $key );

		return $this;
	}

	/**
	 * @return \SergeLiatko\WPGmaps\Pin[]
	 */
	public function getPins(): array {
		return $this->pins;
	}

	/**
	 * @param \SergeLiatko\WPGmaps\Pin[] $pins
	 *
	 * @return Options
	 */
	public function setPins( array $pins = array() ): Options {
		$pins       = array_filter( $pins, function ( $item ) {
			return ( $item instanceof Pin );
		} );
		$this->pins = $pins;

		return $this;
	}

	/**
	 * @return \SergeLiatko\WPGmaps\Options\Maptype
	 */
	public function getMaptype(): ?Maptype {
		return $this->maptype;
	}

	/**
	 * @param \SergeLiatko\WPGmaps\Options\Maptype|null $maptype
	 *
	 * @return Options
	 */
	public function setMaptype( ?Maptype $maptype = null ): Options {
		$this->maptype = ( $maptype instanceof Maptype ) ? $maptype : new Maptype();

		return $this;
	}

	/**
	 * @return \SergeLiatko\WPGmaps\Options\Center
	 */
	public function getCenter(): ?Center {
		return $this->center;
	}

	/**
	 * @param \SergeLiatko\WPGmaps\Options\Center|null $center
	 *
	 * @return Options
	 */
	public function setCenter( ?Center $center = null ): Options {
		$this->center = $this->isMultiple() ?
			new Center()
			: ( ( $center instanceof Center ) ? $center : new Center( $this->getFirstPinLocation() ) );

		return $this;
	}

	/**
	 * @return \SergeLiatko\WPGmaps\Options\Zoom
	 */
	public function getZoom(): ?Zoom {
		return $this->zoom;
	}

	/**
	 * @param \SergeLiatko\WPGmaps\Options\Zoom|null $zoom
	 *
	 * @return Options
	 */
	public function setZoom( ?Zoom $zoom = null ): Options {
		$this->zoom = $this->isMultiple() ?
			new Zoom()
			: ( ( $zoom instanceof Zoom ) ? $zoom : new Zoom( Zoom::DEFAULT ) );

		return $this;
	}

	/**
	 * @return \SergeLiatko\WPGmaps\Options\Language
	 */
	public function getLanguage(): ?Language {
		return $this->language;
	}

	/**
	 * @param \SergeLiatko\WPGmaps\Options\Language|null $language
	 *
	 * @return Options
	 */
	public function setLanguage( ?Language $language = null ): Options {
		$this->language = ( $language instanceof Language ) ? $language : new Language();

		return $this;
	}

	/**
	 * @return \SergeLiatko\WPGmaps\Options\Region
	 */
	public function getRegion(): ?Region {
		return $this->region;
	}

	/**
	 * @param \SergeLiatko\WPGmaps\Options\Region|null $region
	 *
	 * @return Options
	 */
	public function setRegion( ?Region $region = null ): Options {
		$this->region = ( $region instanceof Region ) ? $region : new Region();

		return $this;
	}

	/**
	 * @return \SergeLiatko\WPGmaps\Options\Format
	 */
	public function getFormat(): ?Format {
		return $this->format;
	}

	/**
	 * @param \SergeLiatko\WPGmaps\Options\Format|null $format
	 *
	 * @return Options
	 */
	public function setFormat( ?Format $format = null ): Options {
		$this->format = ( $format instanceof Format ) ? $format : new Format();

		return $this;
	}

	/**
	 * @return \SergeLiatko\WPGmaps\Location|null
	 */
	public function getFirstPinLocation(): ?Location {
		$pins = array_filter( (array) $this->getPins(), function ( $maybe_pin ) {
			return ( $maybe_pin instanceof Pin );
		} );

		return empty( $pins ) ? null : $pins[0]->getMarker()->getLocation();
	}

	/**
	 * @param string $key
	 *
	 * @return string
	 */
	protected function sanitizeKey( string $key ): string {
		$key = preg_replace( '/([^a-z0-9])/i', '', $key );

		return strval( $key );
	}

	/**
	 * @return bool
	 */
	protected function isMultiple(): bool {
		return ( 1 < count( $this->getPins() ) );
	}

}
