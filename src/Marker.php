<?php


namespace SergeLiatko\WPGmaps;

use SergeLiatko\WPGmaps\Markers\Color;
use SergeLiatko\WPGmaps\Markers\Label;
use SergeLiatko\WPGmaps\Markers\Size;
use SergeLiatko\WPGmaps\Markers\Title;

/**
 * Class Marker
 *
 * @package SergeLiatko\WPGmaps
 */
class Marker {

	/**
	 * @var Location $location
	 */
	protected Location $location;

	/**
	 * @var Title|null
	 */
	protected ?Title $title;

	/**
	 * @var Size|null $size
	 */
	protected ?Size $size;

	/**
	 * @var Color|null $color
	 */
	protected ?Color $color;

	/**
	 * @var Label|null $label
	 */
	protected ?Label $label;

	/**
	 * Marker constructor.
	 *
	 * @param Location $location
	 * @param Title|null $title
	 * @param Size|null  $size
	 * @param Color|null $color
	 * @param Label|null $label
	 */
	public function __construct(
		Location $location,
		?Title $title = null,
		?Size $size = null,
		?Color $color = null,
		?Label $label = null
	) {
		$this->setLocation( $location );
		$this->setTitle( $title );
		$this->setSize( $size );
		$this->setColor( $color );
		$this->setLabel( $label );
	}

	/**
	 * @return Location
	 */
	public function getLocation(): Location {
		return $this->location;
	}

	/**
	 * @param Location $location
	 *
	 * @return Marker
	 */
	public function setLocation( Location $location ): Marker {
		$this->location = $location;

		return $this;
	}

	/**
	 * @return Title|null
	 */
	public function getTitle(): ?Title {
		return $this->title;
	}

	/**
	 * @param Title|null $title
	 *
	 * @return Marker
	 */
	public function setTitle( ?Title $title = null ): Marker {
		$this->title = $title;

		return $this;
	}

	/**
	 * @return Size|null
	 */
	public function getSize(): ?Size {
		return $this->size;
	}

	/**
	 * @param Size|null $size
	 *
	 * @return Marker
	 */
	public function setSize( ?Size $size = null ): Marker {
		$this->size = $size;

		return $this;
	}

	/**
	 * @return Color|null
	 */
	public function getColor(): ?Color {
		return $this->color;
	}

	/**
	 * @param Color|null $color
	 *
	 * @return Marker
	 */
	public function setColor( ?Color $color = null ): Marker {
		$this->color = $color;

		return $this;
	}

	/**
	 * @return Label|null
	 */
	public function getLabel(): ?Label {
		return $this->label;
	}

	/**
	 * @param Label|null $label
	 *
	 * @return Marker
	 */
	public function setLabel( ?Label $label = null ): Marker {
		$this->label = $label;

		return $this;
	}

	/**
	 * @return array
	 */
	public function __toArray(): array {
		return array(
			'location' => $this->getLocation()->__toArray(),
			'title'    => strval( $this->getTitle() ),
			'size'     => strval( $this->getSize() ),
			'color'    => strval( $this->getColor() ),
			'label'    => strval( $this->getLabel() ),
		);
	}

}
