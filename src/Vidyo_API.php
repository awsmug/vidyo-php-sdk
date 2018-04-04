<?php

namespace Vidyo_PHP_SDK;
use SoapFault;

/**
 * Vidyo API
 *
 * API to call vidyo.com Webservices
 *
 * @author  awesome.ug <support@awesome.ug>
 * @package Vidyo_PHP_SDK
 * @version 1.0.0
 * @since   1.0.0
 * @license GPL 2
 */
class Vidyo_API extends \SoapClient {
	/**
	 * Endpoint name
	 *
	 * @var string
	 *
	 * @since 1.0.0
	 */
	protected $endpoint;

	/**
	 * Version endpoint
	 *
	 * @param boolean
	 *
	 * @since 1.0.0
	 */
	protected $version_endpoint = 'v1_1';

	/**
	 * Vidyo_API constructor.
	 *
	 * @param Vidyo_Connection $connection
	 * @param string $endpoint
	 *
	 * @throws Vidyo_Exception
	 *
	 * @since 1.0.0
	 */
	public function __construct( Vidyo_Connection $connection, $endpoint ) {
		$this->endpoint = $endpoint;

		$api_url = "https://{$connection->get_host()}/services/{$this->endpoint}?wsdl";

		$options = array(
			'login'        => $connection->get_username(),
			'password'     => $connection->get_password(),
			'trace'        => 1,
			'exceptions'   => 1,
			'soap_version' => SOAP_1_2,
			'features'     => SOAP_SINGLE_ELEMENT_ARRAYS
		);

		try {
			parent::__construct( $api_url, $options );
		} catch ( \Exception $e ) {
			throw new Vidyo_Exception( 'Can´t connect to SOAP Service', 0, $e );
		}
	}

	/**
	 * Doing a Request
	 *
	 * @param $function
	 * @param $params
	 *
	 * @return mixed Depends on API
	 *
	 * @throws Vidyo_Exception
	 *
	 * @since 1.0.0
	 */
	public function request( $function, $params ) {
		try {
			$response = $this->$function( $params );
		} catch ( \Exception $e ) {
			$this->error( $e->getMessage() );
			throw new Vidyo_Exception( 'Vidyo Request Error', 0, $e );
		}

		return $response;
	}
}