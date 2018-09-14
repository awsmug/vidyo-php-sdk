<?php

namespace Vidyo_PHP_SDK\Helpers;

use Vidyo_PHP_SDK\Model\Vidyo_API_Object;

/**
 * Class Vidyo_Member_API_Object
 *
 * Just for not using stdClass
 *
 * @package Vidyo_PHP_SDK
 *
 * @since 1.0.0
 */
class Vidyo_Member_API_Object extends Vidyo_API_Object {
	/**
	 * Name
	 *
	 * @var string
	 *
	 * @since 1.0.0
	 */
	var $name;

	/**
	 * Password
	 *
	 * @var string
	 *
	 * @since 1.0.0
	 */
	var $password;

	/**
	 * Displayed name
	 *
	 * @var string
	 *
	 * @since 1.0.0
	 */
	var $displayName;

	/**
	 * Email Address
	 *
	 * @var string
	 *
	 * @since 1.0.0
	 */
	var $emailAddress;

	/**
	 * Extension
	 *
	 * @var string
	 *
	 * @since 1.0.0
	 */
	var $extension;

	/**
	 * Language
	 *
	 * @var string
	 *
	 * @since 1.0.0
	 */
	var $Language;

	/**
	 * Role name
	 *
	 * @var string
	 *
	 * @since 1.0.0
	 */
	var $RoleName;

	/**
	 * Group name
	 *
	 * @var string
	 *
	 * @since 1.0.0
	 */
	var $groupName;

	/**
	 * Location tag
	 *
	 * @var string
	 *
	 * @since 1.0.1
	 */
	var $locationTag = 'Default';

	/**
	 * Vidyo_Member_API_Object constructor.
	 *
	 * Setting up defaults
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		$this->defaults = array(
			'language' => 'en',
			'role_name' => 'Normal',
			'group_name' => 'Default'
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
		$this->set_property( 'emailAddress', 'email', $properties, $use_defaults );
		$this->set_property( 'password', 'password', $properties, $use_defaults );
		$this->set_property( 'displayName', 'display_name', $properties, $use_defaults );
		$this->set_property( 'extension', 'extension', $properties, $use_defaults );
		$this->set_property( 'Language', 'language', $properties, $use_defaults );
		$this->set_property( 'RoleName', 'role_name', $properties, $use_defaults );
		$this->set_property( 'groupName', 'group_name', $properties, $use_defaults );
	}
}