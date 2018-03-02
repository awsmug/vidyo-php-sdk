<?php

namespace Vidyo_PHP_SDK;

use Vidyo_PHP_SDK\Helpers\Vidyo_Member_API_Object;
use Vidyo_PHP_SDK\Model\Vidyo_Admin_API_Service;
use Vidyo_PHP_SDK\Model\Vidyo_API_Service;
use Vidyo_PHP_SDK\Model\Vidyo_User_API_Service;

/**
 * Vidyo API Member
 *
 * Vidyo API for managing a member.
 *
 * @author  awesome.ug <support@awesome.ug>
 * @package Vidyo_PHP_SDK
 * @version 1.0.0
 * @since   1.0.0
 * @license GPL 2
 */
class Vidyo_Member extends Vidyo_API_Service {
	use Vidyo_Admin_API_Service;
	use Vidyo_User_API_Service;

	/**
	 * Member ID
	 *
	 * @var string
	 *
	 * @since 1.0.0
	 */
	private $member_id;

	/**
	 * Member Properties
	 *
	 * @var Vidyo_Member_API_Object
	 *
	 * @since 1.0.0
	 */
	private $properties;

	/**
	 * Vidyo_Member constructor.
	 *
	 * @param Vidyo_Connection $connection Connection object
	 * @param null|string $member_id Member ID for member to load
	 * @param bool $debug Turns debug mode and logging on/off
	 *
	 * @throws Vidyo_Exception
	 *
	 * @since 1.0.0
	 */
	public function __construct( Vidyo_Connection $connection, $member_id = null, $debug = false ) {
		parent::__construct( $connection, $debug );
		$this->member_id = $member_id;

		$this->init_admin_api( $this->connection, $debug );
		$this->init_user_api( $this->connection, $debug );
	}

	/**
	 * Setting properties of member
	 *
	 * @param array $properties Member Properties {
	 *      @type string 'username'
	 *      @type string 'password'
	 *      @type string 'display_name'
	 *      @type string 'email'
	 *      @type string 'extension' (Optional)
	 *      @type string 'language' (Optional)
	 *      @type string 'role_name' (Optional)
	 *      @type string 'group_name' (Optional)
	 * }
	 *
	 * @return bool|int True if updated, false if not. On new member, the ID of the member or false on failure
	 *
	 * @throws Vidyo_Exception
	 *
	 * @since 1.0.0
	 */
	public function set_properties( array $properties = array() ) {
		// Add new Member
		if( null === $this->member_id && count( $properties ) > 0 ) {
			return $this->add( $properties );
		}

		// Updating Member
		if( null === $this->member_id && count( $properties ) > 0 ) {
			return $this->update( $properties );
		}

		return false;
	}

	/**
	 * Get properties of member
	 *
	 * @return bool|Vidyo_Member_API_Object
	 *
	 * @throws Vidyo_Exception
	 *
	 * @since 1.0.0
	 */
	public function get_properties() {
		if( null === $this->member_id ) {
			return false;
		}

		$params = array(
			'memberID' => $this->member_id
		);

		try {
			$response = $this->admin_api->request( 'GetMember', $params );
		} catch ( Vidyo_Exception $e ) {
			throw new Vidyo_Exception( 'Could not get properties of member with ID ' . $this->member_id, 0 , $e );
		}

		$this->properties = new Vidyo_Member_API_Object();
		$this->properties->set_properties_by_api_object( $response->member );

		return $this->properties;
	}

	/**
	 * Add member
	 *
	 * @param array $properties Member Properties {
	 *      @type string 'username'
	 *      @type string 'password'
	 *      @type string 'display_name'
	 *      @type string 'email'
	 *      @type string 'extension' (Optional)
	 *      @type string 'language' (Optional)
	 *      @type string 'role_name' (Optional)
	 *      @type string 'group_name' (Optional)
	 * }
	 *
	 * @return int|bool Member ID if added, false if not
	 *
	 * @throws Vidyo_Exception
	 *
	 * @since 1.0.0
	 */
	private function add( array $properties ) {
		$this->properties = new Vidyo_Member_API_Object();
		$this->properties->set_properties_by_array( $properties, true );

		$params = array(
			'member' => $this->properties
		);

		try {
			$response = $this->admin_api->request( 'AddMember', $params );
		} catch ( Vidyo_Exception $e ) {
			throw new Vidyo_Exception( 'Could not add member', 0 , $e );
		}

		if( false === $response ) {
			return false;
		}

		$members = new Vidyo_Members( $this->connection, $this->debug );
		$response = $members->search( $this->properties->name );

		$this->member_id = $response->member[ 0 ]->memberID;

		return $this->member_id;
	}

	/**
	 * Update member
	 *
	 * @param array $properties Member Properties {
	 *      @type string 'username'
	 *      @type string 'password'
	 *      @type string 'display_name'
	 *      @type string 'email'
	 *      @type string 'extension' (Optional)
	 *      @type string 'language' (Optional)
	 *      @type string 'role_name' (Optional)
	 *      @type string 'group_name' (Optional)
	 * }
	 *
	 * @return bool True if updated, false if not
	 *
	 * @throws Vidyo_Exception
	 *
	 * @since 1.0.0
	 */
	public function update( array $properties ) {
		if( empty( $this->member_id ) ) {
			return false;
		}
		$this->properties->set_properties_by_array( $properties );

		$params = array(
			'memberID' => $this->member_id,
			'member' => $this->properties
		);

		$response = $this->admin_api->request( 'UpdateMember', $params );

		if( false !== $response ) {
			return true;
		}

		return false;
	}

	/**
	 * Delete member
	 *
	 * @return bool True if user was deleted, false if not.
	 *
	 * @throws Vidyo_Exception
	 *
	 * @since 1.0.0
	 */
	public function delete() {
		if( empty( $this->member_id ) ) {
			return false;
		}

		$params = array(
			'memberID' => $this->member_id
		);

		$response = $this->admin_api->request( 'DeleteMember', $params );

		if( false !== $response ) {
			return true;
		}

		return false;
	}
}