<?php


namespace SergeLiatko\WPGmaps;

use SergeLiatko\WPGmaps\Markers\Color;
use SergeLiatko\WPGmaps\Markers\Label;
use SergeLiatko\WPGmaps\Markers\Size;

/**
 * Class Marker
 *
 * @package SergeLiatko\WPGmaps
 */
class Marker {

	/**
	 * @var \SergeLiatko\WPGmaps\Location $location
	 */
	protected $location;

	/**
	 * @var \SergeLiatko\WPGmaps\Markers\Size|null $size
	 */
	protected $size;

	/**
	 * @var \SergeLiatko\WPGmaps\Markers\Color|null $color
	 */
	protected $color;

	/**
	 * @var \SergeLiatko\WPGmaps\Markers\Label|null $label
	 */
	protected $label;

	/**
	 * Marker constructor.
	 *
	 * @param \SergeLiatko\WPGmaps\Location           $location
	 * @param \SergeLiatko\WPGmaps\Markers\Size|null  $size
	 * @param \SergeLiatko\WPGmaps\Markers\Color|null $color
	 * @param \SergeLiatko\WPGmaps\Markers\Label|null $label
	 */
	public function __construct(
		Location $location,
		?Size $size = null,
		?Color $color = null,
		?Label $label = null
	) {
		$this->location = $location;
		$this->size     = $size;
		$this->color    = $color;
		$this->label    = $label;
	}

	/**
	 * @return \SergeLiatko\WPGmaps\Location
	 */
	public function getLocation(): Location {
		return $this->location;
	}

	/**
	 * @param \SergeLiatko\WPGmaps\Location $location
	 *
	 * @return Marker
	 */
	public function setLocation( Location $location ): Marker {
		$this->location = $location;

		return $this;
	}

	/**
	 * @return \SergeLiatko\WPGmaps\Markers\Size|null
	 */
	public function getSize(): ?Size {
		return $this->size;
	}

	/**
	 * @param \SergeLiatko\WPGmaps\Markers\Size|null $size
	 *
	 * @return Marker
	 */
	public function setSize( ?Size $size = null ): Marker {
		$this->size = $size;

		return $this;
	}

	/**
	 * @return \SergeLiatko\WPGmaps\Markers\Color|null
	 */
	public function getColor(): ?Color {
		return $this->color;
	}

	/**
	 * @param \SergeLiatko\WPGmaps\Markers\Color|null $color
	 *
	 * @return Marker
	 */
	public function setColor( ?Color $color = null ): Marker {
		$this->color = $color;

		return $this;
	}

	/**
	 * @return \SergeLiatko\WPGmaps\Markers\Label|null
	 */
	public function getLabel(): ?Label {
		return $this->label;
	}

	/**
	 * @param \SergeLiatko\WPGmaps\Markers\Label|null $label
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
			'size'     => strval( $this->getSize() ),
			'color'    => strval( $this->getColor() ),
			'label'    => strval( $this->getLabel() ),
		);
	}

}
