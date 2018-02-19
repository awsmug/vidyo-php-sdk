<?php

namespace Vidyo_PHP_SDK\Model;

use Vidyo_PHP_SDK\Vidyo_Connection;
use Vidyo_PHP_SDK\Vidyo_API;

/**
 * Vidyo Admin API Service
 *
 * Abstract class for Object which are communicating with Vidyo Admin API
 *
 * @author  awesome.ug <support@awesome.ug>
 * @package Vidyo_PHP_SDK
 * @version 1.0.0
 * @since   1.0.0
 * @license GPL 2
 */
abstract class Vidyo_Admin_API_Service extends Vidyo_API_Service {
	/**
	 * Admin API object
	 *
	 * @var Vidyo_API
	 *
	 * @since 1.0.0
	 */
	var $admin_api;

	/**
	 * Vidyo_Members constructor.
	 *
	 * @param Vidyo_Connection $connection Vidyo Connection Object
	 * @param bool $debug Debug Mode
	 *
	 * @since 1.0.0
	 */
	public function __construct( Vidyo_Connection $connection, $debug = false ) {
		parent::__construct( $connection, $debug );
		$this->admin_api = new Vidyo_API( $connection, 'VidyoPortalAdminService', $debug );
	}
}