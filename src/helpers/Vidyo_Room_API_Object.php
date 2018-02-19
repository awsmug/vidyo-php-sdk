<?php

namespace Vidyo_PHP_SDK\Helpers;
use Vidyo_PHP_SDK\Model\Vidyo_API_Object;

/**
 * Class Vidyo_Public_Room_API_Object
 *
 * Just for not using stdClass
 *
 * @package Vidyo_PHP_SDK
 *
 * @since 1.0.0
 */
class Vidyo_Room_API_Object extends Vidyo_API_Object {
	/**
	 * Name
	 *
	 * @var string
	 *
	 * @since 1.0.0
	 */
	var $name;

	/**
	 * Owner Name
	 *
	 * @var string
	 *
	 * @since 1.0.0
	 */
	var $ownerName;

	/**
	 * Room Type
	 *
	 * @var string
	 *
	 * @since 1.0.0
	 */
	var $RoomType;

	/**
	 * Room Mode
	 *
	 * @var string
	 *
	 * @since 1.0.0
	 */
	var $RoomMode;


	/**
	 * Extension
	 *
	 * @var int
	 *
	 * @since 1.0.0
	 */
	var $extension;

	/**
	 * Group name
	 *
	 * @var string
	 *
	 * @since 1.0.0
	 */
	var $groupName;

	/**
	 * Vidyo_Member_API_Object constructor.
	 *
	 * Setting up defaults
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		$room_mode = new Vidyo_Room_Mode_API_Object();
		$room_mode->set_properties_by_array( array(), true );

		$this->defaults = array(
			'room_type' => 'Public',
			'group_name' => 'Default',
			'room_mode' => $room_mode
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
		$this->set_property( 'name', 'name', $properties, $use_defaults );
		$this->set_property( 'RoomType', 'room_type', $properties, $use_defaults );
		$this->set_property( 'ownerName', 'owner_name', $properties, $use_defaults );
		$this->set_property( 'extension', 'extension', $properties, $use_defaults );
		$this->set_property( 'groupName', 'group_name', $properties, $use_defaults );
		$this->set_property( 'RoomMode', 'room_mode', $properties, $use_defaults );
	}
}