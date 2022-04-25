<?php


namespace SergeLiatko\WPGmaps;

/**
 * Class Map
 *
 * @package SergeLiatko\WPGmaps
 */
class Map {

	public const URL_BASE = 'https://maps.googleapis.com/maps/api/staticmap';

	/**
	 * @var \SergeLiatko\WPGmaps\Options $options
	 */
	protected $options;

	/**
	 * @var int $id
	 */
	protected $id;

	/**
	 * @var int $printed
	 */
	protected $printed;

	/**
	 * @var string $url
	 */
	protected $url;

	/**
	 * Map constructor.
	 *
	 * @param \SergeLiatko\WPGmaps\Options $options
	 */
	public function __construct( Options $options ) {
		$this->setOptions( $options );
		$this->setPrinted( 0 );
	}

	/**
	 * @return string
	 */
	public function __toString(): string {
		return $this->__toHTML();
	}

	/**
	 * @return \SergeLiatko\WPGmaps\Options
	 */
	public function getOptions(): Options {
		return $this->options;
	}

	/**
	 * @param \SergeLiatko\WPGmaps\Options $options
	 *
	 * @return Map
	 */
	public function setOptions( Options $options ): Map {
		$this->options = $options;

		return $this;
	}

	/**
	 * @return int|null
	 */
	public function getId(): ?int {
		return $this->id;
	}

	/**
	 * @param int $id
	 *
	 * @return Map
	 */
	public function setId( int $id ): Map {
		$this->id = $id;

		return $this;
	}

	/**
	 * @return int
	 */
	public function getPrinted(): int {
		return $this->printed;
	}

	/**
	 * @param int $printed
	 *
	 * @return Map
	 */
	public function setPrinted( int $printed ): Map {
		$this->printed = $printed;

		return $this;
	}

	/**
	 * @return string
	 */
	public function getUrl(): string {
		if ( empty( $this->url ) ) {
			$this->setUrl( $this->buildURL() );
		}

		return $this->url;
	}

	/**
	 * @param string $url
	 *
	 * @return Map
	 */
	public function setUrl( string $url ): Map {
		$this->url = $url;

		return $this;
	}

	/**
	 * @return string
	 */
	public function buildURL(): string {
		$parameters = array(
			'key'      => $this->getOptions()->getKey(),
			'maptype'  => $this->getOptions()->getMaptype()->__toString(),
			'center'   => $this->getOptions()->getCenter()->__toString(),
			'zoom'     => $this->getOptions()->getZoom()->__toString(),
			'language' => $this->getOptions()->getLanguage()->__toString(),
			'region'   => $this->getOptions()->getRegion()->__toString(),
			'format'   => $this->getOptions()->getFormat()->__toString(),
			'markers'  => $this->getMarkersParameter(),
		);

		return add_query_arg(
			array_filter( $parameters ),
			self::URL_BASE
		);
	}

	/**
	 * @return string
	 */
	public function __toHTML(): string {
		if ( is_null( $this->getId() ) ) {
			//register map in loader as not done yet
			$this->setId( Loader::registerMap( $this ) );
		}

		return sprintf(
			'<div id="wpgmap-%1$d-%2$d" class="wpgmap" data-key="%1$d" data-event="%4$s"><div id="wpgmap-%1$d-%2$d-static" class="wpgmap-static" data-url="%3$s"></div></div>',
			$this->getId(),
			$this->getPrintIndex(),
			esc_url( $this->getUrl() ),
			$this->getOptions()->getLoadevent()
		);
	}

	/**
	 * @return string
	 */
	protected function getMarkersParameter(): string {
		$markers = array();
		foreach ( $this->getOptions()->getPins() as $pin ) {
			//only marker locations are returned for each marker in static map url
			$markers[] = $pin->getMarker()->getLocation()->__toString();
		}

		return join( '|', $markers );
	}

	/**
	 * @return int
	 */
	protected function getPrintIndex(): int {
		$printed = $this->getPrinted();
		$printed ++;
		$this->setPrinted( $printed );

		return $printed;
	}

}
