<?php

namespace Vidyo_PHP_SDK;

/**
 * Class Vidyo_Connection
 *
 * Class which contains the connection data to Vidyo Service
 *
 * @package Vidyo_PHP_SDK
 *
 * @since 1.0.0
 */
class Vidyo_Connection {
	/**
	 * Host
	 *
	 * @var string Vidyo Host
	 *
	 * @since 1.0.0
	 */
	private $host;

	/**
	 * Username
	 *
	 * @var string Vidyo Username
	 *
	 * @since 1.0.0
	 */
	private $username;

	/**
	 * Password
	 *
	 * @var string Vidyo Password
	 *
	 * @since 1.0.0
	 */
	private $password;

	/**
	 * Extra options for SOAP config
	 *
	 * @var string Extra options
	 *
	 * @since 1.0.0
	 */
	private $extra_options = array();

	/**
	 * Vidyo_Connection constructor.
	 *
	 * @param $config array(
	 *      @type string "host" Vidyo Host
	 *      @type string "username" Vidyo Username
	 *      @type string "password" Vidyo Password
	 * )
	 * @param array $extra_options Extra Options for SOAP config
	 *
	 * @throws Vidyo_Exception
	 *
	 * @since 1.0.0
	 */
	public function __construct( $config, $extra_options = array() ) {
		if ( ! array_key_exists( 'host', $config ) || ! array_key_exists( 'username', $config ) || ! array_key_exists( 'password', $config ) ) {
			throw new Vidyo_Exception( 'Missing Configuration data in Vidyo connection class.', 1 );
		}

		$this->host     = $config['host'];
		$this->username = $config['username'];
		$this->password = $config['password'];

		$this->extra_options = $extra_options;
	}

	/**
	 * Returns the host
	 *
	 * @return mixed|string
	 *
	 * @since 1.0.0
	 */
	public function get_host() {
		return $this->host;
	}

	/**
	 * Returns the username
	 *
	 * @return mixed|string
	 *
	 * @since 1.0.0
	 */
	public function get_username() {
		return $this->username;
	}

	/**
	 * Returns the password
	 *
	 * @return mixed|string
	 *
	 * @since 1.0.0
	 */
	public function get_password() {
		return $this->password;
	}

	/**
	 * Returns the extra options
	 *
	 * @return array
	 *
	 * @since 1.0.0
	 */
	public function get_extra_options() {
		return $this->extra_options;
	}
}