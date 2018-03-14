<?php

use PHPUnit\Framework\TestCase;
use Vidyo_PHP_SDK\Vidyo_Room;
use Vidyo_PHP_SDK\Vidyo_Connection;

class VidyoRoomTests extends VidyoTestCase {
	var $test_id;
	var $standard_room_properties;

	public function setUp() {
		parent::setUp();

		// We need that for multiple tests at once (Travis)
		$this->test_id = $this->get_random_id();

		$this->standard_room_properties = array(
			'name' => 'phpunittest' . $this->test_id,
			'owner_name' => $this->vidyo_user,
			'extension' => $this->vidyo_extension . $this->get_random_id()
		);
	}

	public function createRoom() {
		$room = new Vidyo_Room( $this->connection );
		$room_id = $room->set_properties( $this->standard_room_properties );
		return $room_id;
	}

	public function deleteRoom( $member_id ) {
		$room = new Vidyo_Room( $this->connection, $member_id );
		$room->delete();
	}

	/**
	 * @expectedException \Vidyo_PHP_SDK\Vidyo_Exception
	 */
	public function xtestConnectionFail() {
		$conf = array(
			'host' => 'phantasyhost',
			'username' => 'phantasyuser',
			'password' => 'phantasypassword'
		);

		$connection = new Vidyo_Connection( $conf );
		new Vidyo_Room( $connection );
	}

	public function testCreateDeleteRoom() {
		$room = new Vidyo_Room( $this->connection );
		$room_id = $room->set_properties( $this->standard_room_properties );

		$this->assertTrue( is_int( $room_id ) );
		$this->assertTrue( $room->delete() );
	}

	public function testUpdateRoom() {
		$room_id = $this->createRoom();

		$new_name = 'phpunittestchanged' . $this->test_id;
		$properties[ 'name' ] = $new_name;

		$room = new Vidyo_Room( $this->connection, $room_id );
		$room->get_properties();
		$response = $room->update( $properties );

		$this->assertTrue( $response );

		$room = new Vidyo_Room( $this->connection, $room_id );
		$properties = $room->get_properties();
		$this->assertEquals( $properties->name, $new_name );

		$this->deleteRoom( $room_id );
	}

	public function testRoomUrl() {
		$room_id = $this->createRoom();

		$room = new Vidyo_Room( $this->connection, $room_id );
		$room->get_properties();
		$this->assertTrue( $room->create_room_url() );
		$this->assertStringStartsWith( 'http', $room->get_room_url() );
		$this->assertTrue( $room->remove_room_url() );

		$this->deleteRoom( $room_id );
	}

	public function testModeratorURL() {
		$room_id = $this->createRoom();

		$room = new Vidyo_Room( $this->connection, $room_id );
		$this->assertTrue( $room->create_moderator_url() );
		$this->assertStringStartsWith( 'http', $room->get_moderator_url() );

		$this->deleteRoom( $room_id );
	}

	public function testRoomPin() {
		$room_id = $this->createRoom();

		$room = new Vidyo_Room( $this->connection, $room_id );
		$this->assertTrue( $room->create_room_pin( 12345 ) );
		$this->assertTrue( $room->remove_room_pin() );

		$this->deleteRoom( $room_id );
	}
}