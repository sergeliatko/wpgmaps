<?php


namespace SergeLiatko\WPGmaps;

/**
 * Class Parameters
 *
 * @package SergeLiatko\WPGmaps
 */
class Configuration {

	/**
	 * @var string $key
	 */
	protected $key;

	/**
	 * @var \SergeLiatko\WPGmaps\Pin[] $pins
	 */
	protected $pins;

	/**
	 * @var \SergeLiatko\WPGmaps\Configurations\Maptype $maptype
	 */
	protected $maptype;

	/**
	 * @var \SergeLiatko\WPGmaps\Configurations\Center $center
	 */
	protected $center;

	/**
	 * @var \SergeLiatko\WPGmaps\Configurations\Zoom $zoom
	 */
	protected $zoom;

	/**
	 * @var \SergeLiatko\WPGmaps\Configurations\Language $language
	 */
	protected $language;

	/**
	 * @var \SergeLiatko\WPGmaps\Configurations\Region $region
	 */
	protected $region;

	/**
	 * @var \SergeLiatko\WPGmaps\Configurations\Format $format
	 */
	protected $format;

}
