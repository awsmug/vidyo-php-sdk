<?php

use PHPUnit\Framework\TestCase;

class VidyoTestCase extends TestCase {

	/**
	 * Vidyo API Host
	 * @var string
	 */
	var $vidyo_host;

	/**
	 * Vidyo API User
	 * @var $string
	 */
	var $vidyo_user;

	/**
	 * Vidyo API Password
	 * @var string
	 */
	var $vidyo_pass;

	/**
	 * Vidyo Extension ID
	 * @var string
	 */
	var $vidyo_extension;

	/**
	 * @var VidyoSuperAPI
	 */
	var $super_client;

	/**
	 * @var VidyoAdminAPI
	 */
	var $admin_client;

	/**
	 * @var VidyoUserAPI
	 */
	var $user_client;

	/**
	 * Setting up Objects and variables for tests
	 */
	public function setUp() {
		$this->vidyo_host = getenv( 'VIDYO_HOST' );
		$this->vidyo_user = getenv( 'VIDYO_USER' );
		$this->vidyo_pass = getenv( 'VIDYO_PASS' );
		$this->vidyo_extension = getenv( 'VIDYO_EXTENSION' );
		$this->vidyo_extension.= $this->get_random_id();

		$this->admin_client = new VidyoAdminAPI( $this->vidyo_host, $this->vidyo_user, $this->vidyo_pass, true );
		$this->user_client = new VidyoUserAPI( $this->vidyo_host, $this->vidyo_user, $this->vidyo_pass, true );
	}

	/**
	 * Returns a random string for use as ID
	 *
	 * @param int $length Length of id string returned
	 *
	 * @return string $random_id A Random ID
	 */
	public function get_random_id( $length = 8 ) {
		return substr( time() * rand(), 0, $length );
	}

	/**
	 * Getting random extension
	 *
	 * @return string
	 */
	public function get_random_extension() {
		return getenv( 'VIDYO_EXTENSION' ) . $this->get_random_id();
	}
}