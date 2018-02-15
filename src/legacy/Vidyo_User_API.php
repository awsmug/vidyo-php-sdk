<?php

namespace Vidyo_PHP_SDK;

/**
 * Vidyo API for VidyoPortalUserService
 *
 * API to call vidyo.com Webservices
 *
 * @author  awesome.ug <support@awesome.ug>
 * @package Vidyo_PHP_SDK
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
class Vidyo_User_API extends Vidyo_API
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

		return TRUE;
	}

	/**
	 * Create Room
	 *
	 * @param string $name
	 * @param int    $extension
	 *
	 * @return bool|stdClass $response
	 */
	public function create_room( $name, $extension )
	{
		$params = array(
			'name'      => $name,
			'extension' => $extension
		);

		$response = $this->request( 'createRoom', $params );

		if( is_object( $response ) && property_exists( $response, 'Entity' ) )
		{
			return $response->Entity;
		}

		$this->error( 'Could not create room' );

		return false;
	}

	/**
	 * Deleting Room
	 *
	 * @param int $room_id
	 *
	 * @return bool $response
	 */
	public function delete_room( $room_id )
	{
		$params = array(
			'roomID' => $room_id,
		);

		$response = $this->request( 'DeleteRoom', $params );

		if( is_object( $response ) && property_exists( $response, 'OK' ) && 'OK' == $response->OK )
		{
			return true;
		}

		$this->error( 'Could not delete Room' );

		return false;
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
			return true;
		}
		else
		{
			$this->error( 'Could not create Room URL' );

			return false;
		}
	}

	public function get_room_url( $entity_id ) {
		$response = $this->get_room_by_entity_id( $entity_id );

		if( property_exists( $response, 'RoomMode' ) && is_object( $response->RoomMode ) && property_exists( $response->RoomMode, 'roomURL' ) ) {
			return $response->RoomMode->roomURL;
		}

		return false;
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
			return true;
		}
		else
		{
			$this->error( 'Could not create Moderator PIN' );
			return false;
		}
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

	/**
	 * General search for an entity
	 *
	 * @param string $filter Name, Display name or extension to search for
	 *
	 * @return stdClass|bool $response
	 */
	public function find( $filter ) {
		$params = array(
			'Filter' => array(
				'query' => $filter
			)
		);

		$response = $this->request( 'search', $params );

		if( is_object( $response ) && $response->total > 0 )
		{
			return $response;
		}

		return false;
	}

	/**
	 * Getting Room by extension
	 *
	 * @param int $extension
	 *
	 * @return stdClass|bool
	 */
	public function get_room_by_extension( $extension ) {
		$entity = $this->get_entity_by( 'extension', $extension );

		if( $this->is_room( $entity ) ) {
			return $entity;
		}

		return false;
	}

	/**
	 * Getting room by display name
	 *
	 * @param string $name
	 *
	 * @return stdClass|bool
	 */
	public function get_room_by_display_name( $name ) {
		$entity = $this->get_entity_by( 'displayName', $name );

		if( $this->is_room( $entity ) ) {
			return $entity;
		}

		return false;
	}

	/**
	 * Gets room by entity id
	 *
	 * @param $entity_id
	 *
	 * @return bool|stdClass
	 */
	public function get_room_by_entity_id( $entity_id ) {
		$entity = $this->get_entity_by_entity_id( $entity_id );

		if( $this->is_room( $entity ) ) {
			return $entity;
		}

		return false;
	}

	/**
	 * Checks if entity is room
	 *
	 * @param stdClass $entity
	 *
	 * @return bool $is_room
	 */
	public function is_room( $entity ) {
		if( ! is_object( $entity ) ) {
			return false;
		}

		if( ! property_exists( $entity, 'EntityType' ) ) {
			return false;
		}

		if( 'Room' === $entity->EntityType ) {
			return true;
		}

		return false;
	}

	public function get_entity_by_entity_id( $entity_id ) {
		$params = array(
			'entityID' => $entity_id
		);

		$response = $this->request( 'GetEntityByEntityID', $params );

		if( is_object( $response ) && $response->total > 0 ) {
			return $response->Entity[0];
		}

		return $response;
	}

	/**
	 * Getting entity by
	 *
	 * @param string $property
	 * @param string $search
	 *
	 * @return stdClass|bool
	 */
	public function get_entity_by( $property, $search ) {
		$entities = $this->find( $search );

		if( ! $entities ) {
			return false;
		}

		foreach ( $entities->Entity AS $entity ) {
			if( $entity->$property === $search ) {
				return $entity;
			}
		}

		return false;
	}

}