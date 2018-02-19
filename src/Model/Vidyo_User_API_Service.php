<?php

namespace Vidyo_PHP_SDK\Model;

use Vidyo_PHP_SDK\Vidyo_Connection;
use Vidyo_PHP_SDK\Vidyo_API;

/**
 * Vidyo User API Service
 *
 * Abstract class for Object which are communicating with Vidyo User API
 *
 * @author  awesome.ug <support@awesome.ug>
 * @package Vidyo_PHP_SDK
 * @version 1.0.0
 * @since   1.0.0
 * @license GPL 2
 */
abstract class Vidyo_User_API extends Vidyo_API_Service {
	/**
	 * User API object
	 *
	 * @var Vidyo_API
	 *
	 * @since 1.0.0
	 */
	var $user_api;

	/**
	 * Vidyo_User_API_Object constructor.
	 *
	 * @param Vidyo_Connection $connection Connection object
	 * @param bool $debug Debug mode
	 *
	 * @since 1.0.0
	 */
	public function __construct( Vidyo_Connection $connection, $debug = false ) {
		parent::__construct( $connection, $debug );
		$this->user_api = new Vidyo_API( $connection, 'VidyoPortalUserService', $debug );
	}
}