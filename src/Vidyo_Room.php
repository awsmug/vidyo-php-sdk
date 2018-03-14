<?php

namespace Vidyo_PHP_SDK;

use Vidyo_PHP_SDK\Model\Vidyo_API_Service;
use Vidyo_PHP_SDK\Model\Vidyo_Admin_API_Service;
use Vidyo_PHP_SDK\Model\Vidyo_User_API_Service;
use Vidyo_PHP_SDK\Helpers\Vidyo_Room_API_Object;

/**
 * Vidyo API Room
 *
 * Vidyo API for managing a room.
 *
 * @author  awesome.ug <support@awesome.ug>
 * @package Vidyo_PHP_SDK
 * @version 1.0.0
 * @since   1.0.0
 * @license GPL 2
 */
class Vidyo_Room extends Vidyo_API_Service {
	use Vidyo_Admin_API_Service;
	use Vidyo_User_API_Service;

	/**
	 * Room ID
	 *
	 * @var string
	 *
	 * @since 1.0.0
	 */
	private $room_id;

	/**
	 * Room Properties
	 *
	 * @var Vidyo_Room_API_Object
	 *
	 * @since 1.0.0
	 */
	private $properties;

	/**
	 * Vidyo_Member constructor.
	 *
	 * @param Vidyo_Connection $connection Connection object
	 * @param null|string $room_id Room ID for room to load
	 * @param bool $debug Turns debug mode and logging on/off
	 *
	 * @throws Vidyo_Exception
	 *
	 * @since 1.0.0
	 */
	public function __construct( Vidyo_Connection $connection, $room_id = null, $debug = false ) {
		parent::__construct( $connection, $debug );
		$this->room_id = $room_id;

		$this->init_admin_api( $connection, $debug );
		$this->init_user_api( $connection, $debug );
	}

	/**
	 * Setting properties of member
	 *
	 * @param array $properties Room Properties {
	 *      @type string 'name'
	 *      @type string 'owner_name'
	 *      @type string 'extension' Extension number
	 *      @type string 'room_type' (Optional) Standard is 'Public'
	 *      @type string 'group_name' (Optional) Standard is 'Default'
	 *      @type \Vidyo_PHP_SDK\Helpers\Vidyo_Room_Mode_API_Object 'room_mode' (Optional)
	 * }
	 *
	 * @return bool|int True if updated, false if not. On new room, the ID of the member or false on failure
	 *
	 * @throws Vidyo_Exception
	 *
	 * @since 1.0.0
	 */
	public function set_properties( array $properties = array() ) {

		try {
			// Add new Room
			if ( null === $this->room_id && count( $properties ) > 0 ) {
				return $this->add( $properties );
			}

			// Updating Room
			if ( null === $this->room_id && count( $properties ) > 0 ) {
				return $this->update( $properties );
			}
		} catch ( Vidyo_Exception $e ) {
			throw $e;
		}

		return false;
	}

	/**
	 * Get properties of member
	 *
	 * @return bool|Vidyo_Room_API_Object
	 *
	 * @throws Vidyo_Exception
	 *
	 * @since 1.0.0
	 */
	public function get_properties() {
		if( null === $this->room_id ) {
			return false;
		}

		$params = array(
			'roomID' => $this->room_id
		);

		$response = $this->admin_api->request( 'GetRoom', $params );

		$this->properties = new Vidyo_Room_API_Object();
		$this->properties->set_properties_by_api_object( $response->room );

		return $this->properties;
	}

	/**
	 * Add room
	 *
	 * @param array $properties Room Properties {
	 *      @type string 'name'
	 *      @type string 'owner_name'
	 *      @type string 'extension' Extension number
	 *      @type string 'room_type' (Optional) Standard is 'Public'
	 *      @type string 'group_name' (Optional) Standard is 'Default'
	 *      @type \Vidyo_PHP_SDK\Helpers\Vidyo_Room_Mode_API_Object 'room_mode' (Optional)
	 * }
	 *
	 * @return int|bool Room ID if added, false if not
	 *
	 * @throws Vidyo_Exception
	 *
	 * @since 1.0.0
	 */
	private function add( array $properties ) {
		$this->properties = new Vidyo_Room_API_Object();
		$this->properties->set_properties_by_array( $properties, true );

		$params = array(
			'room' => $this->properties
		);

		$this->admin_api->request( 'AddRoom', $params );

		$rooms = new Vidyo_Rooms( $this->connection, $this->debug );
		$response = $rooms->search( $this->properties->name );

		$this->room_id = $response->room[ 0 ]->roomID;

		return $this->room_id;
	}

	/**
	 * Update member
	 *
	 * @param array $properties Room Properties {
	 *      @type string 'name'
	 *      @type string 'owner_name'
	 *      @type string 'extension' Extension number
	 *      @type string 'room_type' (Optional) Standard is 'Public'
	 *      @type string 'group_name' (Optional) Standard is 'Default'
	 *      @type \Vidyo_PHP_SDK\Helpers\Vidyo_Room_Mode_API_Object 'room_mode' (Optional)
	 * }
	 *
	 * @return bool True if updated, false if not
	 *
	 * @throws Vidyo_Exception
	 *
	 * @since 1.0.0
	 */
	public function update( array $properties ) {
		if( empty( $this->room_id ) ) {
			throw new Vidyo_Exception( 'No room id given' );
		}
		$this->properties->set_properties_by_array( $properties );

		$params = array(
			'roomID' => $this->room_id,
			'room' => $this->properties
		);

		$this->admin_api->request( 'UpdateRoom', $params );

		return true;
	}

	/**
	 * Delete room
	 *
	 * @return bool True if user was deleted, false if not.
	 *
	 * @throws Vidyo_Exception
	 *
	 * @since 1.0.0
	 */
	public function delete() {
		if( empty( $this->room_id ) ) {
			throw new Vidyo_Exception( 'No room id given' );
		}

		$params = array(
			'roomID' => $this->room_id
		);

		$response = $this->admin_api->request( 'DeleteRoom', $params );

		if( false !== $response ) {
			return true;
		}

		return false;
	}

	/**
	 * Creating Room URL
	 *
	 * @return bool
	 *
	 * @throws Vidyo_Exception
	 *
	 * @since 1.0.0
	 */
	public function create_room_url(){
		if( empty( $this->room_id ) ) {
			throw new Vidyo_Exception( 'No room id given' );
		}

		$params = array(
			'roomID' => $this->room_id
		);

		$response = $this->admin_api->request( 'CreateRoomURL', $params );

		if( false !== $response ) {
			return true;
		}

		return false;
	}

	/**
	 * Remove Room URL
	 *
	 * @return bool
	 *
	 * @throws Vidyo_Exception
	 *
	 * @since 1.0.0
	 */
	public function remove_room_url(){
		if( empty( $this->room_id ) ) {
			throw new Vidyo_Exception( 'No room id given' );
		}

		$params = array(
			'roomID' => $this->room_id
		);

		$response = $this->admin_api->request( 'RemoveRoomURL', $params );

		if( false !== $response ) {
			return true;
		}

		return false;
	}

	/**
	 * Getting Room URL
	 *
	 * @return bool|string
	 *
	 * @since 1.0.0
	 */
	public function get_room_url() {
		if( ! property_exists( $this->properties, 'RoomMode' ) || ! property_exists( $this->properties->RoomMode, 'roomURL' )  ) {
			return false;
		}

		return $this->properties->RoomMode->roomURL;
	}

	/**
	 * Getting a temporary moderator url
	 *
	 * @throws Vidyo_Exception
	 *
	 * @since 1.0.0
	 */
	public function create_moderator_url() {
		if( empty( $this->room_id ) ) {
			throw new Vidyo_Exception( 'No room id given' );
		}

		$params = array(
			'roomID' => $this->room_id
		);

		$this->user_api->request( 'createModeratorURL', $params );

		return true;
	}

	/**
	 * Getting moderator URL
	 *
	 * @return string
	 *
	 * @throws Vidyo_Exception
	 *
	 * @since 1.0.0
	 */
	public function get_moderator_url() {
		if( empty( $this->room_id ) ) {
			throw new Vidyo_Exception( 'No room id given' );
		}

		$params = array(
			'roomID' => $this->room_id
		);

		$response = $this->user_api->request( 'getModeratorURL', $params );

		return $response->moderatorURL;
	}

	/**
	 * Getting moderator URL with token (not needing a password)
	 *
	 * @return string
	 *
	 * @throws Vidyo_Exception
	 *
	 * @since 1.0.0
	 */
	public function get_moderator_url_with_token() {
		if( empty( $this->room_id ) ) {
			throw new Vidyo_Exception( 'No room id given' );
		}

		$params = array(
			'roomID' => $this->room_id
		);

		$response = $this->user_api->request( 'getModeratorURLWithToken', $params );

		return $response->moderatorURL;
	}

	/**
	 * Creating Room PIN
	 *
	 * @param string $pin Room PIN
	 * @return bool
	 *
	 * @throws Vidyo_Exception
	 *
	 * @since 1.0.0
	 */
	public function create_room_pin( $pin = null ){
		if( empty( $this->room_id ) ) {
			return false;
		}

		$params = array(
			'roomID' => $this->room_id,
			'PIN' => $pin
		);

		$response = $this->admin_api->request( 'CreateRoomPIN', $params );

		if( false !== $response ) {
			return true;
		}

		return false;
	}

	/**
	 * Delete Room PIN
	 *
	 * @return bool
	 *
	 * @throws Vidyo_Exception
	 *
	 * @since 1.0.0
	 */
	public function remove_room_pin(){
		if( empty( $this->room_id ) ) {
			return false;
		}

		$params = array(
			'roomID' => $this->room_id,
		);

		$response = $this->admin_api->request( 'RemoveRoomPIN', $params );

		if( false !== $response ) {
			return true;
		}

		return false;
	}

}