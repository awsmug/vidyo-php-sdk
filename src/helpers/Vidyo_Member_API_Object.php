<?php

namespace Vidyo_PHP_SDK\Helpers;

/**
 * Class Vidyo_Member_Object
 *
 * Just for not using stdClass
 *
 * @package Vidyo_PHP_SDK
 *
 * @since 1.0.0
 */
class Vidyo_Member_API_Object {
	/**
	 * Object property defaults
	 *
	 * @var array Default values {
	 *      @type string 'language'
	 *      @type string 'role_name'
	 *      @type string 'group_name'
	 * }
	 *
	 * @since 1.0.0
	 */
	private $defaults = array();

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
	 * Setting properties
	 *
	 * We need that because of Vidyo Camel Case and inconsistence we do not want to have on our SDK
	 *
	 * @param array $properties
	 *
	 * @since 1.0.0
	 */
	public function set_properties( array $properties = array() ) {
		$this->set_property( 'name', 'name', $properties );
		$this->set_property( 'emailAddress', 'email', $properties );
		$this->set_property( 'password', 'password', $properties );
		$this->set_property( 'displayName', 'display_name', $properties );
		$this->set_property( 'extension', 'extension', $properties );
		$this->set_property( 'Language', 'language', $properties );
		$this->set_property( 'RoleName', 'role_name', $properties );
		$this->set_property( 'groupName', 'group_name', $properties );
	}

	/**
	 * Setting a property
	 *
	 * @param string $api_name API Name of the property
	 * @param string $name Array Name of the property
	 * @param array $properties All properties in an array
	 *
	 * @since 1.0.0
	 */
	private function set_property( $api_name, $name, $properties ) {
		if ( array_key_exists( $name, $properties ) ) {
			$this->$api_name = $properties[ $name ];
		} else {
			if( array_key_exists( $name, $this->defaults ) ) {
				$this->$api_name = $this->defaults[ $name ];
			}
		}
	}
}