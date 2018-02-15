<?php

use PHPUnit\Framework\TestCase;

use Vidyo_PHP_SDK\Vidyo_Connection;
use Vidyo_PHP_SDK\Vidyo_Exception;

class VidyoTestCase extends TestCase {

	/**
	 * Vidyo API Host
	 * @var string
	 */
	protected $vidyo_host;

	/**
	 * Vidyo API User
	 * @var $string
	 */
	protected $vidyo_user;

	/**
	 * Vidyo API Password
	 * @var string
	 */
	protected $vidyo_pass;

	/**
	 *
	 */
	protected $connection;

	/**
	 * Vidyo Extension ID
	 * @var string
	 */
	protected $vidyo_extension;


	/**
	 * Setting up Objects and variables for tests
	 */
	public function setUp() {
		$this->vidyo_host = getenv( 'VIDYO_HOST' );
		$this->vidyo_user = getenv( 'VIDYO_USER' );
		$this->vidyo_pass = getenv( 'VIDYO_PASS' );
		$this->vidyo_extension = getenv( 'VIDYO_EXTENSION' );

		$config = array (
			'host'      => $this->vidyo_host,
			'username'  => $this->vidyo_user,
			'password'  => $this->vidyo_pass
		);

		try {
			$this->connection = new Vidyo_Connection( $config );
		} catch ( Vidyo_Exception $e ) {
			echo $e->getMessage();
		}
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