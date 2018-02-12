<?php

/**
 * Vidyo API for VidyoPortalAdminService
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
class VidyoAdminAPI extends VidyoAPI
{
	/**
	 * Constructor
	 *
	 * @param string $portal_host
	 * @param string $username
	 * @param string $password
	 */
	public function __construct( $portal_host, $username, $password, $debug = FALSE )
	{
		parent::__construct( $portal_host, 'VidyoPortalAdminService', $username, $password, $debug );
	}

	/**
	 * Get Members
	 *
	 * @return bool
	 */
	public function get_members( $query = '' )
	{
		$params = array(
			'Filter' => array(
				'query' => $query
			)
		);

		$response = $this->request( 'getMembers', $params );

		if( is_object( $response ) && property_exists( $response, 'total' )  && property_exists( $response, 'member' )  )
		{
			return $response;
		}

		$this->error( 'Could not get Members' );

		return FALSE;
	}

	/**
	 * Adding a member
	 *
	 * @param string $name
	 * @param string $display_name
	 * @param string $email
	 * @param int $extension
	 * @param string $language
	 * @param string $role_name
	 * @param string $group_name
	 *
	 * @return bool
	 */
	public function add_member( $name, $password, $display_name, $email, $extension, $language  = 'en', $role_name = 'Normal', $group_name = 'Default'  )
	{
		$member = new stdClass();
		$member->name = $name;
		$member->password = $password;
		$member->displayName = $display_name;
		$member->emailAddress = $email;
		$member->extension = $extension;
		$member->Language = $language;
		$member->RoleName = $role_name;
		$member->groupName = $group_name;

		$params = array(
			'member' => $member
		);

		$response = $this->request( 'AddMember', $params );

		if( is_object( $response ) && property_exists( $response, 'OK' ) && 'OK' == $response->OK )
		{
			return TRUE;
		}
		else
		{
			$this->error( 'Could not add Member' );

			return FALSE;
		}
	}

	/**
	 * Delete Member
	 *
	 * @param $member_id
	 *
	 * @return bool
	 */
	public function delete_member( $member_id )
	{
		$params = array(
			'memberID' => $member_id
		);

		$response = $this->request( 'DeleteMember', $params );

		if( is_object( $response ) && property_exists( $response, 'OK' ) && 'OK' == $response->OK )
		{
			return TRUE;
		}
		else
		{
			$this->error( 'Could not delete Member' );

			return FALSE;
		}
	}

	/**
	 * @param $room_id
	 *
	 * @return bool
	 */
	public function get_room( $room_id )
	{
		$params = array(
			'roomID' => $room_id
		);

		$response = $this->request( 'GetRoom', $params );

		if( is_object( $response ) && property_exists( $response, 'room' ) )
		{
			return $response->room;
		}

		return FALSE;
	}


	public function get_rooms( $filter ) {
		$params = array(
			'Filter' => array(
				'query' => $filter
			)
		);

		$response = $this->request( 'GetRooms', $params );

		if( is_object( $response ) && property_exists( $response, 'EntityType' ) && 'Room' === $response->EntityType )
		{
			return $response;
		}

		return $response;
	}
}