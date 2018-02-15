<?php

namespace Vidyo_PHP_SDK;

/**
 * Vidyo API for VidyoPortalAdminService
 *
 * API to call vidyo.com Webservices
 *
 * @author  awesome.ug <support@awesome.ug>
 * @package Vidyo_PHP_SDK
 * @version 1.0.0
 * @since   1.0.0
 * @license GPL 2
 */
class Vidyo_Admin_API extends Vidyo_API
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

		return false;
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
		$member = new \stdClass();
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