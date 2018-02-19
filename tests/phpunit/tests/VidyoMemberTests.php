<?php

use PHPUnit\Framework\TestCase;
use Vidyo_PHP_SDK\Vidyo_Member;

class VidyoMemberTests extends VidyoTestCase {
	var $test_id;
	var $standard_user_properties;

	public function setUp() {
		parent::setUp();

		// We need that for multiple tests at once (Travis)
		$this->test_id = $this->get_random_id();

		$this->standard_user_properties = array(
			'name' => 'phpunittest' . $this->test_id,
			'email' => $this->test_id . 'unit@test.com',
			'password' => '123456',
			'display_name' => 'PHP Unittest ' . $this->test_id,
		);
	}

	public function createMember() {
		$member = new Vidyo_Member( $this->connection );
		$member_id = $member->set_properties( $this->standard_user_properties );
		return $member_id;
	}

	public function deleteMember( $member_id ) {
		$member = new Vidyo_Member( $this->connection, $member_id );
		$member->delete();
	}

	public function testCreateDeleteMember() {
		$member = new Vidyo_Member( $this->connection );
		$member_id = $member->set_properties( $this->standard_user_properties );

		$this->assertTrue( is_int( $member_id ) );
		$this->assertTrue( $member->delete() );
	}

	public function testUpdateMember() {
		$member_id = $this->createMember();
		$test_email =  $this->test_id . 'unitchanged@test.com';
		$properties[ 'email' ] = $test_email;

		$member = new Vidyo_Member( $this->connection, $member_id );
		$response = $member->update( $properties );

		$this->assertTrue( $response );

		$member = new Vidyo_Member( $this->connection, $member_id );
		$properties = $member->get_properties();
		$this->assertEquals( $properties->emailAddress, $test_email );

		$this->deleteMember( $member_id );
	}
}