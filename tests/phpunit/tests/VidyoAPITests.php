<?php

use PHPUnit\Framework\TestCase;

class VidyoAPITests extends VidyoTestCase {
	public function testCreateDeleteRoom() {
		$room_name = 'Room ' . $this->get_random_id();
		$extension = $this->get_random_extension();

		$room = $this->user_client->create_room( $room_name, $extension );
		$this->assertTrue( is_object( $room ) );

		$same_room = $this->user_client->create_room( $room_name, $extension . '0' );
		$this->assertFalse( $same_room );

		$same_room = $this->user_client->create_room( $room_name . 'x', $extension );
		$this->assertFalse( $same_room );

		$response = $this->user_client->delete_room( $room->entityID );
		$this->assertTrue( $response );
	}

	public function testGetRoomBy() {
		$room_name = 'Room ' . $this->get_random_id();
		$extension = $this->get_random_extension();

		$room = $this->user_client->create_room( $room_name, $extension );

		$response = $this->user_client->get_room_by_extension( $extension );
		$this->assertTrue( is_object( $response ) );

		$response = $this->user_client->get_room_by_extension( $extension . '0' );
		$this->assertFalse( $response );

		$response = $this->user_client->get_room_by_display_name( $room_name );
		$this->assertTrue( is_object( $response ) );

		$response = $this->user_client->get_room_by_display_name( $room_name . 'x' );
		$this->assertFalse( $response );

		$response = $this->user_client->get_room_by_entity_id( $room->entityID );
		$this->assertTrue( is_object( $response ) );

		$response = $this->user_client->get_room_by_entity_id( $room->entityID . 'x' );
		$this->assertFalse( $response );

		$this->user_client->delete_room( $room->entityID );
	}

	public function testFind() {
		$room_name = 'Room ' . $this->get_random_id();
		$extension = $this->get_random_extension();
		$room = $this->user_client->create_room( $room_name, $extension );

		// Find by room name
		$response = $this->user_client->find( $room_name );
		$this->assertTrue( is_object( $response ) );
		$this->assertEquals(1, $response->total );

		// Find by extension name
		$response = $this->user_client->find( $extension );
		$this->assertTrue( is_object( $response ) );
		$this->assertEquals(1, $response->total );

		$this->user_client->delete_room( $room->entityID );
	}

	public function testAddDeleteMember() {
		$member_name = 'SvenWagener';
		$extension = $this->get_random_extension();
		
		$response = $this->admin_client->add_member( $member_name, '123456789', 'Sven Wagener', 'very@awesome.ug', $extension );
		$this->assertTrue( $response );

		$members = $this->admin_client->get_members( $extension );
		$this->admin_client->log( print_r( $members, true ) );

		$this->assertTrue( is_object( $members ) );
		$this->assertTrue( property_exists( $members, 'total' ) );
		$this->assertTrue( property_exists( $members, 'member' ) );

		$member_id = $members->member[0]->memberID;

		$response = $this->admin_client->delete_member( $member_id );
		$this->assertTrue( $response );
	}

	public function testCreateRemoveRoomUrl() {
		$room_name = 'Room ' . $this->get_random_id();
		$extension = $this->get_random_extension();

		$room = $this->user_client->create_room( $room_name, $extension );
		$first_room_url = $this->user_client->get_room_url( $room->entityID );

		$this->assertTrue( $this->user_client->create_room_url( $room->entityID ) );
		$this->assertNotEquals( $first_room_url, $this->user_client->get_room_url( $room->entityID ) );

		$this->assertTrue( $this->user_client->remove_room_url( $room->entityID ) );

		$this->user_client->log( print_r( $room, true ) );

		$this->user_client->delete_room( $room->entityID );
	}

	public function testCreateRemoveRoomPIN() {
		$room_name = 'Room ' . $this->get_random_id();
		$extension = $this->get_random_extension();

		$room = $this->user_client->create_room( $room_name, $extension );
		$pin = substr( time(), 0, 10 );

		$this->assertTrue( $this->user_client->create_room_pin( $room->entityID, $pin ) );
		$this->assertEquals( $pin, $this->admin_client->get_room( $room->entityID )->RoomMode->roomPIN );

		$this->assertTrue( $this->admin_client->get_room( $room->entityID )->RoomMode->hasPin );
		$this->assertTrue( $this->user_client->remove_room_pin( $room->entityID ) );
		$this->assertFalse( $this->admin_client->get_room( $room->entityID )->RoomMode->hasPin );

		$this->user_client->delete_room( $room->entityID );
	}

	public function testCreateRemoveModeratorPin() {
		$room_name = 'Room ' . $this->get_random_id();
		$extension = $this->get_random_extension();

		$room = $this->user_client->create_room( $room_name, $extension );
		$pin = substr( time(), 0, 10 );

		$response = $this->user_client->create_moderator_pin( $room->entityID, $pin );
		$this->assertTrue( $response );

		$response = $this->user_client->remove_moderator_pin( $room->entityID );
		$this->assertTrue( $response );

		$this->user_client->delete_room( $room->entityID );
	}

	public function testCreateRemoveRoomModeratorURL() {
		$room_name = 'Room ' . $this->get_random_id();
		$extension = $this->get_random_extension();

		$room = $this->user_client->create_room( $room_name, $extension );

		$response = $this->user_client->create_moderator_url( $room->entityID );
		$this->assertTrue( $response );

		$moderator_url = $this->user_client->get_moderator_url( $room->entityID );
		$this->assertStringStartsWith( 'https://' . $this->vidyo_host . '/controlmeeting.html?key=', $moderator_url );

		$response = $this->user_client->remove_moderator_url( $room->entityID );
		$this->assertTrue( $response );

		$this->user_client->delete_room( $room->entityID );
	}

	public function testGetInviteContent() {
		$room_name = 'Room ' . $this->get_random_id();
		$extension = $this->get_random_extension();

		$room = $this->user_client->create_room( $room_name, $extension );

		$content = $this->user_client->get_invite_content( $room->entityID );
		$this->assertStringStartsWith( 'Join', $content );

		$this->user_client->delete_room( $room->entityID );
	}
}