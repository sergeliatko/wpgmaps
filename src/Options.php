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
	protected string $key;

	/**
	 * @var Pin[] $pins
	 */
	protected array $pins;

	/**
	 * @var Maptype|null $maptype
	 */
	protected ?Maptype $maptype;

	/**
	 * @var Center|null $center
	 */
	protected ?Center $center;

	/**
	 * @var Zoom|null $zoom
	 */
	protected ?Zoom $zoom;

	/**
	 * @var Language|null $language
	 */
	protected ?Language $language;

	/**
	 * @var Region|null $region
	 */
	protected ?Region $region;

	/**
	 * @var Format|null $format
	 */
	protected ?Format $format;

	/**
	 * @var string|null $loadevent
	 */
	protected ?string $loadevent;

	/**
	 * Options constructor.
	 *
	 * @param string                                     $key
	 * @param Pin[] $pins
	 * @param Maptype|null  $maptype
	 * @param Center|null   $center
	 * @param Zoom|null     $zoom
	 * @param Language|null $language
	 * @param Region|null   $region
	 * @param Format|null   $format
	 * @param string|null                                $loadevent
	 */
	public function __construct(
		string    $key,
		array     $pins = [],
		?Maptype  $maptype = null,
		?Center   $center = null,
		?Zoom     $zoom = null,
		?Language $language = null,
		?Region   $region = null,
		?Format   $format = null,
		?string   $loadevent = null
	) {
		$this->setKey( $key );
		$this->setPins( $pins );
		$this->setMaptype( $maptype );
		$this->setCenter( $center );
		$this->setZoom( $zoom );
		$this->setLanguage( $language );
		$this->setRegion( $region );
		$this->setFormat( $format );
		$this->setLoadevent( $loadevent );
	}

	/**
	 * @return array
	 */
	public function __toArray(): array {
		return array(
			'key'       => $this->getKey(),
			'pins'      => array_map(
				function ( $item ) {
					return $item->__toArray();
				},
				$this->getPins()
			),
			'maptype'   => strval( $this->getMaptype() ),
			'center'    => $this->getCenter()->__toArray(),
			'zoom'      => strval( $this->getZoom() ),
			'language'  => strval( $this->getLanguage() ),
			'region'    => strval( $this->getRegion() ),
			'format'    => strval( $this->getFormat() ),
			'loadevent' => $this->getLoadevent(),
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
	 * @return Pin[]
	 */
	public function getPins(): array {
		return $this->pins;
	}

	/**
	 * @param Pin[] $pins
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
	 * @return Maptype
	 */
	public function getMaptype(): Maptype {
		return $this->maptype;
	}

	/**
	 * @param Maptype|null $maptype
	 *
	 * @return Options
	 */
	public function setMaptype( ?Maptype $maptype = null ): Options {
		$this->maptype = ( $maptype instanceof Maptype ) ? $maptype : new Maptype();

		return $this;
	}

	/**
	 * @return Center
	 */
	public function getCenter(): Center {
		return $this->center;
	}

	/**
	 * @param Center|null $center
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
	 * @return Zoom
	 */
	public function getZoom(): Zoom {
		return $this->zoom;
	}

	/**
	 * @param Zoom|null $zoom
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
	 * @return Language
	 */
	public function getLanguage(): Language {
		return $this->language;
	}

	/**
	 * @param Language|null $language
	 *
	 * @return Options
	 */
	public function setLanguage( ?Language $language = null ): Options {
		$this->language = ( $language instanceof Language ) ? $language : new Language();

		return $this;
	}

	/**
	 * @return Region
	 */
	public function getRegion(): Region {
		return $this->region;
	}

	/**
	 * @param Region|null $region
	 *
	 * @return Options
	 */
	public function setRegion( ?Region $region = null ): Options {
		$this->region = ( $region instanceof Region ) ? $region : new Region();

		return $this;
	}

	/**
	 * @return Format
	 */
	public function getFormat(): Format {
		return $this->format;
	}

	/**
	 * @param Format|null $format
	 *
	 * @return Options
	 */
	public function setFormat( ?Format $format = null ): Options {
		$this->format = ( $format instanceof Format ) ? $format : new Format();

		return $this;
	}

	/**
	 * @return string
	 */
	public function getLoadevent(): string {
		if ( !is_string( $this->loadevent ) ) {
			$this->setLoadevent( '' );
		}

		return $this->loadevent;
	}

	/**
	 * @param string|null $loadevent
	 *
	 * @return Options
	 */
	public function setLoadevent( ?string $loadevent = null ): Options {
		$this->loadevent = sanitize_text_field( strval( $loadevent ) );

		return $this;
	}

	/**
	 * @return Location|null
	 */
	public function getFirstPinLocation(): ?Location {
		$pins = array_filter( $this->getPins(), function ( $maybe_pin ) {
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
		$key = preg_replace( '/([^a-z\d])/i', '', $key );

		return strval( $key );
	}

	/**
	 * @return bool
	 */
	protected function isMultiple(): bool {
		return ( 1 < count( $this->getPins() ) );
	}

}
