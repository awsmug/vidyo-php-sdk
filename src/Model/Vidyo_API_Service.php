<?php

namespace Vidyo_PHP_SDK\Model;

use Vidyo_PHP_SDK\Vidyo_Connection;

/**
 * Vidyo API Service
 *
 * Abstract class for Object which are communicating with Vidyo API
 *
 * @author  awesome.ug <support@awesome.ug>
 * @package Vidyo_PHP_SDK
 * @version 1.0.0
 * @since   1.0.0
 * @license GPL 2
 */
abstract class Vidyo_API_Service {
	/**
	 * Debug mode
	 *
	 * @var bool True if debug mode is switched on, false if not.
	 *
	 * @since 1.0.0
	 */
	protected $debug;

	/**
	 * Vidyo connection object
	 *
	 * @var Vidyo_Connection
	 *
	 * @since 1.0.0
	 */
	protected $connection;

	/**
	 * Vidyo_API_Object constructor.
	 *
	 * @param Vidyo_Connection $connection
	 * @param bool $debug Debug mode
	 *
	 * @since 1.0.0
	 */
	public function __construct( Vidyo_Connection $connection, $debug ) {
		$this->connection = $connection;
		$this->debug = $debug;
	}
}