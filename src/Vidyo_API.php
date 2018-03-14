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
	 * @param boolean $debug
	 *
	 * @since 1.0.0
	 */
	protected $version_endpoint = 'v1_1';

	/**
	 * Errors
	 *
	 * @var array
	 *
	 * @since 1.0.0
	 */
	protected $errors = array();

	/**
	 * Debug
	 *
	 * @param boolean $debug
	 *
	 * @since 1.0.0
	 */
	protected $debug;

	/**
	 * Vidyo_API constructor.
	 *
	 * @param Vidyo_Connection $connection
	 * @param string $endpoint
	 * @param bool $debug
	 *
	 * @throws Vidyo_Exception
	 *
	 * @since 1.0.0
	 */
	public function __construct( Vidyo_Connection $connection, $endpoint, $debug = false ) {
		$start       = microtime( true );
		$this->debug = $debug;

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

		$time_total = microtime( true ) - $start;

		$time_log = 'Time for connecting Vidyo Service: ' . $time_total . ' s';
		$this->log( $time_log );
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
		$start = microtime( true );

		try {
			$response = $this->$function( $params );
		} catch ( \Exception $e ) {
			$this->error( $e->getMessage() );
			throw new Vidyo_Exception( 'Vidyo Request Error', 0, $e );
		}

		$time_total = microtime( true ) - $start;

		$time_log = 'Time for requesting Vidyo Service (function "' . $function . '""): ' . $time_total . ' s';
		$this->log( $time_log );

		return $response;
	}

	/**
	 * SDK Logging function
	 *
	 * @param $message
	 *
	 * @since 1.0.0
	 */
	public static function log( $message ) {
		if( defined( 'VIDYO_LOG_PATH' ) ) {
			$path = VIDYO_LOG_PATH;
		} else {
			$path = __DIR__;
		}

		if( defined( 'VIDYO_LOG_FILENAME' ) ) {
			$filename = VIDYO_LOG_FILE;
		} else {
			$filename = 'vidyo-api.log';
		}

		$date    = date( 'Y-m-d H:i:s', time() );
		$message = $date . ' - ' . $message . chr( 13 );

		$file = fopen( $path . '/' . $filename , 'a' );
		fputs( $file, $message );
		fclose( $file );
	}

	/**
	 * Adding Notice
	 *
	 * @param string $message Notice text
	 *
	 * @since 1.0.0
	 */
	public function error( $message ) {
		$this->log( 'API Error: ' . $message );
		$this->errors[] = $message;
	}

	/**
	 * Retrieving Notices
	 *
	 * @since 1.0.0
	 */
	public function get_errors() {
		return $this->errors;
	}
}