<?php

namespace Vidyo_PHP_SDK;

/**
 * Vidyo API
 *
 * API to call vidyo.com Webservices
 *
 * @author  awesome.ug <support@awesome.ug>
 * @package Aspen/Vidyo
 * @version 1.0.0
 * @since   1.0.0
 * @license GPL 2
 *
 * Copyright 2015 Awesome UG (support@awesome.ug)
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License, version 2, as
 * published by the Free Software Foundation.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 */

ini_set( 'soap.wsdl_cache_enabled', '0' );

class Vidyo_API extends \SoapClient
{
	/**
	 * Endpoint name
	 *
	 * @var string
	 */
	protected $endpoint;

	/**
	 * Version endpoint
	 *
	 * @param boolean $debug
	 */
	protected $version_endpoint = 'v1_1';

	/**
	 * Errors
	 *
	 * @var array
	 */
	protected $errors = array();

	/**
	 * Debug
	 *
	 * @param boolean $debug
	 */
	protected $debug;

	/**
	 * Constructor
	 *
	 * @param string $portal_host
	 * @param string $endpoint
	 * @param string $username
	 * @param string $password
	 */
	public function __construct( $portal_host, $endpoint, $username, $password, $debug = FALSE )
	{
		$start = microtime( TRUE );
		$this->debug = $debug;

		$this->endpoint = $endpoint;

		$api_url = "https://{$portal_host}/services/{$this->endpoint}?wsdl";

		$options = array(
			'login'    => $username,
			'password' => $password,
			'trace' => 1,
			'exceptions' => 1,
			'soap_version' => SOAP_1_2,
			'features' => SOAP_SINGLE_ELEMENT_ARRAYS
		);

		try
		{
			$client = parent::__construct( $api_url, $options );
		}
		catch ( Exception $e )
		{
			$this->error( $e->getMessage() );

			return FALSE;
		}

		$time_total =  microtime( TRUE ) - $start;

		$time_log = 'Time for connecting Vidyo Service: ' . $time_total . ' s';
		$this->log( $time_log );

		return $client;
	}

	/**
	 * Doing a Request
	 *
	 * @param $function
	 * @param $params
	 *
	 * @return bool
	 */
	public function request( $function, $params )
	{
		$start =  microtime( TRUE );

		try
		{
			$response = $this->$function( $params );
		}
		catch ( \Exception $e )
		{
			$this->error( $e->getMessage() );

			return FALSE;
		}

		$time_total =  microtime( TRUE ) - $start;

		$time_log = 'Time for requesting Vidyo Service (function "' . $function . '""): ' . $time_total . ' s';
		$this->log( $time_log );

		return $response;
	}

	/**
	 * SDK Logging function
	 * @param $message
	 */
	public function log( $message )
	{
		if( TRUE  == $this->debug )
		{
			$date = date( 'Y-m-d H:i:s', time() );
			$message = $date . ' - ' . $message . chr( 13 );

			$file = fopen( 'vidyo.log', 'a' );
			fputs( $file, $message );
			fclose( $file );
		}
	}

	/**
	 * Adding Notice
	 *
	 * @param string $type    Can be error|notice
	 * @param string $message Notice text
	 */
	public function error( $message )
	{
		$this->log( 'API Error: ' .  $message );
		$this->errors[] = $message;
	}

	/**
	 * Retrieving Notices
	 */
	public function get_errors()
	{
		return $this->errors;
	}
}