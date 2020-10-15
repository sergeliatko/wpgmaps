<?php


namespace SergeLiatko\WPGmaps;

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
	 * @var \SergeLiatko\WPGmaps\Markers\Size $size
	 */
	protected $size;

	/**
	 * @var \SergeLiatko\WPGmaps\Markers\Color $color
	 */
	protected $color;
	protected $label;

}
