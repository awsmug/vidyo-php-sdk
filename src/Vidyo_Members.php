<?php

namespace Vidyo_PHP_SDK;

use Vidyo_PHP_SDK\Model\Vidyo_Admin_API_Service;

/**
 * Vidyo API for Members
 *
 * Vidyo API for managing members.
 *
 * @author  awesome.ug <support@awesome.ug>
 * @package Vidyo_PHP_SDK
 * @version 1.0.0
 * @since   1.0.0
 * @license GPL 2
 */
class Vidyo_Members extends Vidyo_Admin_API_Service {
	/**
	 * Vidyo_Members constructor.
	 *
	 * @param Vidyo_Connection $connection
	 * @param bool $debug
	 *
	 * @since 1.0.0
	 */
	public function __construct( Vidyo_Connection $connection, $debug = false ) {
		parent::__construct( $connection, $debug );
	}

	/**
	 * Search for user
	 *
	 * Searches in 'name', 'Display Name' and 'extension' fields of API
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

		$response = $this->admin_api->request( 'GetMembers', $params );

		if ( is_object( $response ) && property_exists( $response, 'total' ) && property_exists( $response, 'member' ) ) {
			return $response;
		}

		return false;
	}
}