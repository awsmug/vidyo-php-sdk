<?php

require_once '../vidyo-api.php';

class VidyoAPITests extends PHPUnit_Framework_TestCase
{
	var $super_client;
	var $admin_client;
	var $user_client;

	public function setUp()
	{
		$this->super_client = new VidyoSuperAPI( 'hin.vidyo.com', 'sven_admin', 'lEm3Af9eDd' );
		$this->admin_client = new VidyoAdminAPI( 'hin.vidyo.com', 'sven_admin', 'lEm3Af9eDd' );
		$this->user_client = new VidyoUserAPI( 'hin.vidyo.com', 'sven_admin', 'lEm3Af9eDd' );
	}

	public function testTenants()
	{
		if( !$this->super_client->list_tenants() )
		{
			print_r( $this->super_client->get_errors() );
		}
	}

	public function testCreateDeleteRoom()
	{
		$room = $this->user_client->create_room( 'Vidyo Room 1', '2365555' );

		$this->assertTrue( is_object( $room ) );
		$this->assertTrue( property_exists( $room, 'entityID' ) );

		$response = $this->user_client->delete_room( $room->entityID );
		$this->assertTrue( $response );
	}

	public function testAddDeleteMember()
	{
		$response = $this->admin_client->add_member( 'awsmugaccount', '123456789', 'Awesome UG', 'very@awesome.ug', 263777 );
		$this->assertTrue( $response );

		$members = $this->admin_client->get_members( 263777 );

		$this->assertTrue( is_object( $members ) );
		$this->assertTrue( property_exists( $members, 'total' ) );
		$this->assertTrue( property_exists( $members, 'member' ) );

		$member_id = $members->member[ 0 ]->memberID;

		$response = $this->admin_client->delete_member( $member_id );
		$this->assertTrue( $response );
	}

	public function testCreateRemoveRoomUrl(){
		$room = $this->user_client->create_room( 'Vidyo Room 3', '2365555'  );
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

	public function testCreateRemoveRoomPIN(){
		$room = $this->user_client->create_room( 'Vidyo Room 4', '2365555'  );
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

	public function testCreateRemoveModeratorPin(){
		$room = $this->user_client->create_room( 'Vidyo Room 5', '2365555'  );

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

	public function testCreateRemoveRoomModeratorURL(){
		$room = $this->user_client->create_room( 'Vidyo Room 6', '2365555'  );
		$this->assertTrue( is_object( $room ) );
		$this->assertTrue( property_exists( $room, 'entityID' ) );

		$response = $this->user_client->create_moderator_url( $room->entityID );
		$this->assertTrue( $response );

		$moderator_url = $this->user_client->get_moderator_url( $room->entityID );
		$this->assertStringStartsWith( 'https://hin.vidyo.com/controlmeeting.html?key=', $moderator_url );

		$response = $this->user_client->remove_moderator_url( $room->entityID );
		$this->assertTrue( $response );

		$response = $this->user_client->delete_room( $room->entityID );
		$this->assertTrue( $response );
	}

	public function testGetInviteContent()
	{
		$room = $this->user_client->create_room( 'Vidyo Room 2', '2365555'  );
		$this->assertTrue( is_object( $room ) );
		$this->assertTrue( property_exists( $room, 'entityID' ) );

		$content = $this->user_client->get_invite_content( $room->entityID );
		$this->assertStringStartsWith( 'Let\'s meet via Vidyo!', $content );

		$response = $this->user_client->delete_room( $room->entityID );
		$this->assertTrue( $response );
	}
}