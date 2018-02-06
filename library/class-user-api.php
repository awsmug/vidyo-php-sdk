<?php

/**
 * Vidyo API for VidyoPortalUserService
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
class VidyoUserAPI extends VidyoAPI
{
	/**
	 * Vidyo PAK
	 *
	 * @var string
	 */
	protected $pak;

	/**
	 * Vidyo PAK2
	 *
	 * @var string
	 */
	protected $pak2;

	/**
	 * Vidyo VM
	 *
	 * @var string
	 */
	protected $vm;

	/**
	 * Vidyo Proxy
	 *
	 * @var string
	 */
	protected $proxy;

	/**
	 * Vidyo Auth Token
	 *
	 * @var string
	 */
	protected $auth_token;

	/**
	 * Constructor
	 *
	 * @param string $portal_host
	 * @param string $username
	 * @param string $password
	 */
	public function __construct( $portal_host, $username, $password, $debug = FALSE )
	{
		try
		{
			parent::__construct( $portal_host, 'v1_1/VidyoPortalUserService', $username, $password, $debug );

			$login = $this->logIn();

			$this->pak = $login->pak;
			$this->pak2 = $login->pak2;
			$this->vm = $login->vmaddress;
			$this->proxy = $login->proxyaddress;

			$params = array(
				'EID' => $username
			);

			$this->linkEndpoint( $params );

			$params = array(
				'validityTime' => 200,
				'endpointId' => $username
			);

			$this->auth_token = $this->generateAuthToken( $params );
		}
		catch ( Exception $e )
		{
			$this->error( $e->getMessage() );

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
	public function create_room( $name, $extension )
	{
		if( NULL == $extension )
		{
			$extension = substr( time() * rand(), 0, 6 );
		}

		$params = array(
			'name'      => $name,
			'extension' => $extension
		);

		$response = $this->request( 'createRoom', $params );

		if( is_object( $response ) && property_exists( $response, 'Entity' ) )
		{
			return $response->Entity;
		}
		else
		{
			$this->error( 'Could not create Room' );

			return FALSE;
		}

		return FALSE;
	}

	/**
	 * Deleting Room
	 *
	 * @param int $room_id
	 *
	 * @return mixed $response
	 */
	public function delete_room( $room_id )
	{
		$params = array(
			'roomID' => $room_id,
		);

		$response = $this->request( 'DeleteRoom', $params );

		if( is_object( $response ) && property_exists( $response, 'OK' ) && 'OK' == $response->OK )
		{
			return TRUE;
		}
		else
		{
			$this->error( 'Could not delete Room' );

			return FALSE;
		}
	}

	/**
	 * Create Room PIN
	 *
	 * @param $room_id
	 *
	 * @return bool
	 */
	public function create_room_pin( $room_id, $pin )
	{
		$params = array(
			'roomID' => $room_id,
			'PIN'    => $pin
		);

		$response = $this->request( 'CreateRoomPIN', $params );

		if( is_object( $response ) && property_exists( $response, 'OK' ) && 'OK' == $response->OK )
		{
			return TRUE;
		}
		else
		{
			$this->error( 'Could not create Room PIN' );

			return FALSE;
		}
	}

	/**
	 * Remove Room PIN
	 *
	 * @param $room_id
	 *
	 * @return bool
	 */
	public function remove_room_pin( $room_id )
	{
		$params = array(
			'roomID' => $room_id
		);

		$response = $this->request( 'RemoveRoomPIN', $params );

		if( is_object( $response ) && property_exists( $response, 'OK' ) && 'OK' == $response->OK )
		{
			return TRUE;
		}
		else
		{
			$this->error( 'Could not remove Room PIN' );

			return FALSE;
		}
	}

	/**
	 * Create Room URL
	 *
	 * @param $room_id
	 *
	 * @return bool
	 */
	public function create_room_url( $room_id )
	{
		$params = array(
			'roomID' => $room_id,
		);

		$response = $this->request( 'CreateRoomURL', $params );

		if( is_object( $response ) && property_exists( $response, 'OK' ) && 'OK' == $response->OK )
		{
			return TRUE;
		}
		else
		{
			$this->error( 'Could not create Room URL' );

			return FALSE;
		}

		return $response;
	}

	/**
	 * Remove Room URL
	 *
	 * @param $room_id
	 *
	 * @return bool
	 */
	public function remove_room_url( $room_id )
	{
		$params = array(
			'roomID' => $room_id,
		);

		$response = $this->request( 'RemoveRoomURL', $params );

		if( is_object( $response ) && property_exists( $response, 'OK' ) && 'OK' == $response->OK )
		{
			return TRUE;
		}
		else
		{
			$this->error( 'Could not remove Room URL' );

			return FALSE;
		}

		return $response;
	}

	/**
	 * Create Moderator PIN
	 *
	 * @param $room_id
	 * @param $pin
	 *
	 * @return bool
	 */
	public function create_moderator_pin( $room_id, $pin )
	{
		$params = array(
			'roomID' => $room_id,
			'PIN'    => $pin
		);

		$response = $this->request( 'createModeratorPIN', $params );

		if( is_object( $response ) && property_exists( $response, 'OK' ) && 'OK' == $response->OK )
		{
			return TRUE;
		}
		else
		{
			$this->error( 'Could not create Moderator PIN' );

			return FALSE;
		}

		return $response;
	}

	/**
	 * Remove Moderator PIN
	 *
	 * @param $room_id
	 *
	 * @return bool
	 */
	public function remove_moderator_pin( $room_id )
	{
		$params = array(
			'roomID' => $room_id
		);

		$response = $this->request( 'removeModeratorPIN', $params );

		if( is_object( $response ) && property_exists( $response, 'OK' ) && 'OK' == $response->OK )
		{
			return TRUE;
		}
		else
		{
			$this->error( 'Could not remove Moderator PIN' );

			return FALSE;
		}

		return $response;
	}

	/**
	 * Create Moderator URL
	 *
	 * @param $room_id
	 *
	 * @return bool
	 */
	public function create_moderator_url( $room_id )
	{
		$params = array(
			'roomID' => $room_id,
		);

		$response = $this->request( 'createModeratorURL', $params );

		if( is_object( $response ) && property_exists( $response, 'OK' ) && 'OK' == $response->OK )
		{
			return TRUE;
		}
		else
		{
			$this->error( 'Could not create Moderator URL' );

			return FALSE;
		}

		return $response;
	}

	/**
	 * Create Moderator URL
	 *
	 * @param $room_id
	 *
	 * @return bool
	 */
	public function remove_moderator_url( $room_id )
	{
		$params = array(
			'roomID' => $room_id,
		);

		$response = $this->request( 'removeModeratorURL', $params );

		if( is_object( $response ) && property_exists( $response, 'OK' ) && 'OK' == $response->OK )
		{
			return TRUE;
		}
		else
		{
			$this->error( 'Could not create Moderator URL' );

			return FALSE;
		}
	}

	/**
	 * Create Moderator URL
	 *
	 * @param $room_id
	 *
	 * @return bool
	 */
	public function get_moderator_url( $room_id )
	{
		$params = array(
			'roomID' => $room_id,
		);

		$response = $this->request( 'getModeratorURL', $params );

		if( is_object( $response ) && property_exists( $response, 'moderatorURL' )  )
		{
			return $response->moderatorURL;
		}
		else
		{
			$this->error( 'Could not get Moderator URL' );

			return FALSE;
		}
	}

	/**
	 * Get invitation mail content
	 *
	 * @param $room_id
	 *
	 * @return bool
	 */
	public function get_invite_content( $room_id )
	{
		$params = array(
			'roomID' => $room_id,
		);

		$response = $this->request( 'GetInviteContent', $params );

		if( is_object( $response ) && property_exists( $response, 'content' ) )
		{
			return $response->content;
		}

		return $response;
	}
}