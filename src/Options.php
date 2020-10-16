<?php


namespace SergeLiatko\WPGmaps;

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
	 * @var \SergeLiatko\WPGmaps\Options\Maptype $maptype
	 */
	protected $maptype;

	/**
	 * @var \SergeLiatko\WPGmaps\Options\Center $center
	 */
	protected $center;

	/**
	 * @var \SergeLiatko\WPGmaps\Options\Zoom $zoom
	 */
	protected $zoom;

	/**
	 * @var \SergeLiatko\WPGmaps\Options\Language $language
	 */
	protected $language;

	/**
	 * @var \SergeLiatko\WPGmaps\Options\Region $region
	 */
	protected $region;

	/**
	 * @var \SergeLiatko\WPGmaps\Options\Format $format
	 */
	protected $format;

}
