<?php

namespace Vidyo_PHP_SDK;

use Vidyo_PHP_SDK\Model\Vidyo_API_Service;
use Vidyo_PHP_SDK\Model\Vidyo_Admin_API_Service;
use Vidyo_PHP_SDK\Model\Vidyo_User_API_Service;

/**
 * Vidyo API for Rooms
 *
 * Vidyo API for managing rooms.
 *
 * @author  awesome.ug <support@awesome.ug>
 * @package Vidyo_PHP_SDK
 * @version 1.0.0
 * @since   1.0.0
 * @license GPL 2
 */
class Vidyo_Rooms extends Vidyo_API_Service {
	use Vidyo_Admin_API_Service;
	use Vidyo_User_API_Service;

	/**
	 * Vidyo_Rooms constructor.
	 *
	 * @param Vidyo_Connection $connection
	 * @param bool $debug
	 *
	 * @since 1.0.0
	 */
	public function __construct( Vidyo_Connection $connection, $debug = false ) {
		parent::__construct( $connection, $debug );

		$this->init_admin_api( $this->connection, $debug );
		$this->init_user_api( $this->connection, $debug );
	}

	/**
	 * Search for room
	 *
	 * Searches in 'name' and 'extension' fields of API
	 *
	 * @param $string String to search for
	 *
	 * @return bool|\stdClass
	 *
	 * @throws Vidyo_Exception
	 *
	 * @since 1.0.0
	 */
	public function search( $string ) {
		$params = array(
			'Filter' => array(
				'query' => $string
			)
		);

		$response = $this->admin_api->request( 'GetRooms', $params );

		if ( is_object( $response ) && property_exists( $response, 'total' ) && property_exists( $response, 'room' ) ) {
			return $response;
		}

		return false;
	}
}