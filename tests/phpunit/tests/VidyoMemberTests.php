<?php

use PHPUnit\Framework\TestCase;
use Vidyo_PHP_SDK\Vidyo_Member;

class VidyoMemberTests extends VidyoTestCase {
	var $standard_user_properties;

	public function setUp() {
		parent::setUp();

		// We need that for multiple tests at once (Travis)
		$test_id = $this->get_random_id();

		$this->standard_user_properties = array(
			'name' => 'phpunittest' . $test_id,
			'email' => $test_id . 'unit@test.com',
			'password' => '123456',
			'display_name' => 'PHP Unittest ' . $test_id,
		);
	}

	public function createMember() {
		$member = new Vidyo_Member( $this->connection );
		$member_id = $member->set_properties( $this->standard_user_properties );
		return $member_id;
	}

	public function testCreateDeleteMember() {
		$member = new Vidyo_Member( $this->connection );
		$member_id = $member->set_properties( $this->standard_user_properties );

		$this->assertTrue( is_int( $member_id ) );
		$this->assertTrue( $member->delete() );
	}

	public function testUpdateMember() {
		$memberId = $this->createUser();


	}
}