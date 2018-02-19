<?php

namespace Vidyo_PHP_SDK\Model;

/**
 * Abstract Class Vidyo_API_Object
 *
 * @package Vidyo_PHP_SDK
 *
 * @since 1.0.0
 */
abstract class Vidyo_API_Object {
	/**
	 * Default Values for new object
	 *
	 * @var array $defaults
	 *
	 * @since 1.0.0
	 */
	protected $defaults = array();

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
	abstract function set_properties_by_array( array $properties = array(), $use_defaults = false );

	/**
	 * Setting a property
	 *
	 * @param string $api_name API Name of the property
	 * @param string $name Array Name of the property
	 * @param array $properties All properties in an array
	 * @param bool $use_defaults Using defaults or not
	 *
	 * @since 1.0.0
	 */
	protected function set_property( $api_name, $name, $properties, $use_defaults = false ) {
		if ( array_key_exists( $name, $properties ) ) {
			$this->$api_name = $properties[ $name ];
		} elseif( $use_defaults ) {
			if( array_key_exists( $name, $this->defaults ) ) {
				$this->$api_name = $this->defaults[ $name ];
			}
		}
	}

	/**
	 * Setting properties by object
	 *
	 * @param \stdClass $object properties by object, given from API
	 *
	 * @since 1.0.0
	 */
	public function set_properties_by_api_object( \stdClass $object ) {
		foreach( get_object_vars( $object) AS $property => $value ) {
			$this->$property = $value;
		}
	}
}