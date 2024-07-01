<?php


namespace SergeLiatko\WPGmaps\Markers;


class Title {

	/**
	 * @var string $title
	 */
	protected string $title;

	/**
	 * Title constructor.
	 *
	 * @param string $title
	 */
	public function __construct( string $title = '' ) {
		$this->setTitle( $title );
	}

	/**
	 * @return string
	 */
	public function __toString(): string {
		return $this->getTitle();
	}

	/**
	 * @return string
	 */
	public function getTitle(): string {
		return $this->title;
	}

	/**
	 * @param string $title
	 *
	 * @return Title
	 */
	public function setTitle( string $title = '' ): Title {
		$this->title = sanitize_text_field( $title );

		return $this;
	}

}
