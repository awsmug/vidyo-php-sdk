<?php

use PHPUnit\Framework\TestCase;

class VidyoAPITests extends VidyoTestCase {

	public function testCreateDeleteRoom() {
		$room_name = 'My room ' . $this->get_random_id();
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

	public function testFind() {
		$room_name = 'My room ' . $this->get_random_id();
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
		$response = $this->admin_client->add_member( 'awsmugaccount'  . $this->vidyo_extension, '123456789', 'Awesome UG', 'very@awesome.ug', $this->vidyo_extension );
		$this->assertTrue( $response );

		$members = $this->admin_client->get_members( $this->vidyo_extension );

		$this->assertTrue( is_object( $members ) );
		$this->assertTrue( property_exists( $members, 'total' ) );
		$this->assertTrue( property_exists( $members, 'member' ) );

		$member_id = $members->member[0]->memberID;

		$response = $this->admin_client->delete_member( $member_id );
		$this->assertTrue( $response );
	}

	public function testCreateRemoveRoomUrl() {
		$this->admin_client = new VidyoAdminAPI( $this->vidyo_host, $this->vidyo_user, $this->vidyo_pass, true );
		$this->user_client  = new VidyoUserAPI( $this->vidyo_host, $this->vidyo_user, $this->vidyo_pass, true );

		$room = $this->user_client->create_room( 'Vidyo Room 3 '  . $this->vidyo_extension, $this->vidyo_extension );
		$this->assertTrue( is_object( $room ) );
		$this->assertTrue( property_exists( $room, 'entityID' ) );

		$old_room_url = $this->admin_client->get_room( $room->entityID )->RoomMode->roomURL;

		$this->assertEquals( $old_room_url, $room->RoomMode->roomURL );

		$this->assertTrue( $this->user_client->create_room_url( $room->entityID ) );
		$this->assertNotEquals( $old_room_url, $this->admin_client->get_room( $room->entityID )->RoomMode->roomURL );

		$this->assertTrue( property_exists( $this->admin_client->get_room( $room->entityID )->RoomMode, 'roomURL' ) );
		$this->assertTrue( $this->user_client->remove_room_url( $room->entityID ) );
		$this->assertFalse( property_exists( $this->admin_client->get_room( $room->entityID )->RoomMode, 'roomURL' ) );

		$response = $this->user_client->delete_room( $room->entityID );
		$this->assertTrue( $response );
	}

	public function testCreateRemoveRoomPIN() {
		$this->admin_client = new VidyoAdminAPI( $this->vidyo_host, $this->vidyo_user, $this->vidyo_pass, true );
		$this->user_client  = new VidyoUserAPI( $this->vidyo_host, $this->vidyo_user, $this->vidyo_pass, true );

		$room = $this->user_client->create_room( 'Vidyo Room 4 '  . $this->vidyo_extension, $this->vidyo_extension );
		$this->assertTrue( is_object( $room ) );
		$this->assertTrue( property_exists( $room, 'entityID' ) );

		$pin = substr( time(), 0, 10 );

		$this->assertTrue( $this->user_client->create_room_pin( $room->entityID, $pin ) );
		$this->assertEquals( $pin, $this->admin_client->get_room( $room->entityID )->RoomMode->roomPIN );

		$this->assertTrue( $this->admin_client->get_room( $room->entityID )->RoomMode->hasPin );
		$this->assertTrue( $this->user_client->remove_room_pin( $room->entityID ) );
		$this->assertFalse( $this->admin_client->get_room( $room->entityID )->RoomMode->hasPin );

		$response = $this->user_client->delete_room( $room->entityID );
		$this->assertTrue( $response );
	}

	public function testCreateRemoveModeratorPin() {
		$this->user_client = new VidyoUserAPI( $this->vidyo_host, $this->vidyo_user, $this->vidyo_pass, true );

		$room = $this->user_client->create_room( 'Vidyo Room 5 ', $this->vidyo_extension, $this->vidyo_extension );

		$this->assertTrue( is_object( $room ) );
		$this->assertTrue( property_exists( $room, 'entityID' ) );

		$pin = substr( time(), 0, 10 );

		$response = $this->user_client->create_moderator_pin( $room->entityID, $pin );
		$this->assertTrue( $response );

		$response = $this->user_client->remove_moderator_pin( $room->entityID );
		$this->assertTrue( $response );

		$response = $this->user_client->delete_room( $room->entityID );
		$this->assertTrue( $response );
	}

	public function testCreateRemoveRoomModeratorURL() {
		$this->user_client = new VidyoUserAPI( $this->vidyo_host, $this->vidyo_user, $this->vidyo_pass, true );

		$room = $this->user_client->create_room( 'Vidyo Room 6 ', $this->vidyo_extension, $this->vidyo_extension );
		$this->assertTrue( is_object( $room ) );
		$this->assertTrue( property_exists( $room, 'entityID' ) );

		$response = $this->user_client->create_moderator_url( $room->entityID );
		$this->assertTrue( $response );

		$moderator_url = $this->user_client->get_moderator_url( $room->entityID );
		$this->assertStringStartsWith( 'https://' . $this->vidyo_host . '/controlmeeting.html?key=', $moderator_url );

		$response = $this->user_client->remove_moderator_url( $room->entityID );
		$this->assertTrue( $response );

		$response = $this->user_client->delete_room( $room->entityID );
		$this->assertTrue( $response );
	}

	public function testGetInviteContent() {
		$this->user_client = new VidyoUserAPI( $this->vidyo_host, $this->vidyo_user, $this->vidyo_pass, true );

		$room = $this->user_client->create_room( 'Vidyo Room 2 ' . $this->vidyo_extension, $this->vidyo_extension );
		$this->assertTrue( is_object( $room ) );
		$this->assertTrue( property_exists( $room, 'entityID' ) );

		$content = $this->user_client->get_invite_content( $room->entityID );
		$this->assertStringStartsWith( 'Join', $content );

		$response = $this->user_client->delete_room( $room->entityID );
		$this->assertTrue( $response );
	}
}