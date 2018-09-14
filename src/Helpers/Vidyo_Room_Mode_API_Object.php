<?php

namespace Vidyo_PHP_SDK\Helpers;

use Vidyo_PHP_SDK\Model\Vidyo_API_Object;

/**
 * Vidyo API Room Mode
 *
 * Vidyo API for managing a room.
 *
 * @author  awesome.ug <support@awesome.ug>
 * @package Vidyo_PHP_SDK
 * @version 1.0.0
 * @since   1.0.0
 * @license GPL 2
 */
class Vidyo_Room_Mode_API_Object extends Vidyo_API_Object{
	/**
	 * Is locked
	 *
	 * @var string
	 *
	 * @since 1.0.0
	 */
	var $isLocked;

	/**
	 * Has PIN
	 *
	 * @var string
	 *
	 * @since 1.0.1
	 */
	var $hasPIN;

	/**
	 * Has Moderator PIN
	 *
	 * @var string
	 *
	 * @since 1.0.1
	 */
	var $hasModeratorPIN;

	/**
	 * Room URL
	 *
	 * @var string
	 *
	 * @since 1.0.0
	 */
	var $roomURL;

	/**
	 * Vidyo_Room_Mode_API_Object constructor.
	 *
	 * Setting up defaults
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		$this->defaults = array(
			'is_locked' => false,
			'has_pin' => false,
		);
	}

	/**
	 * Setting properties by array
	 *
	 * We need that because of Vidyo Camel Case and inconsistence we do not want to have on our SDK
	 *
	 * @param array $properties
	 * @param bool $use_defaults Using defaults or not
	 *
	 * @since 1.0.0
	 */
	public function set_properties_by_array( array $properties = array(), $use_defaults = false ) {
		$this->set_property( 'isLocked', 'is_locked', $properties, $use_defaults );
		$this->set_property( 'hasPin', 'has_pin', $properties, $use_defaults );
	}

}