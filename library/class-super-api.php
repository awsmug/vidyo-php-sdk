<?php

/**
 * Vidyo API for VidyoPortalSuperService
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

class VidyoSuperAPI extends VidyoAPI{

	/**
	 * Constructor
	 *
	 * @param string $portal_host
	 * @param string $username
	 * @param string $password
	 */
	public function __construct( $portal_host, $username, $password, $debug = FALSE  )
	{
		try
		{
			parent::__construct( $portal_host, 'VidyoPortalSuperService', $username, $password, $debug );
		}
		catch ( Exception $e )
		{
			$this->error( $e->faultstring );

			return FALSE;
		}

		return $client;
	}

	/**
	 * Create Room
	 *
	 * @param string $name
	 * @param int    $extenstion
	 *
	 * @return mixed $response
	 */
	public function list_tenants()
	{
		$params = array(
		);

		$response = $this->request( 'ListTenants', $params );

		if( is_object( $response ) && property_exists( $response, 'Entity' ) )
		{
			return $response->Entity;
		}
		else
		{
			$this->error( 'Could not list Tenants' );

			return FALSE;
		}

		return FALSE;
	}
}