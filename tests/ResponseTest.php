<?php

namespace FCMSimple\Tests;

use FCMSimple\Response;

require_once 'src/Response.php';

/**
 * Test case for Response class
 *
 * @license Apache License, Version 2.0
 * @author Khalid H. Alharisi <coder966@gmail.com>
 * @link coder966.net
 * @link github.com/coder966/FCMSimple
 */
class ResponseTest extends \PHPUnit\Framework\TestCase {

	/**
	 * @var Response
	 */
	protected $object;

	/**
	 * @var array
	 */
	private $fixtureTokens;

	/**
	 * @var string HTTP response code
	 */
	private $fixtureResponseCode;

	/**
	 * @var string HTTP response body
	 */
	private $fixtureResponseBody;

	/**
	 * Sets up the fixture, for example, opens a network connection.
	 * This method is called before a test is executed.
	 */
	protected function setUp() {
		$this->fixtureTokens = array(
			"token_number_1",
			"token_number_2",
			"token_number_3"
		);

		$this->fixtureResponseCode = 200;

		$this->fixtureResponseBody = array(
			"multicast_id" => 123456789,
			"success" => 1,
			"failure" => 2,
			"canonical_ids" => 1,
			"results" => array(
				array("message_id" => "0:123456789abcdef"),
				array("message_id" => "0:123456789abcdef", "registration_id" => "updated_token"),
				array("error" => "InvalidRegistration")
			)
		);
		$this->fixtureResponseBody = json_encode($this->fixtureResponseBody);

		$this->object = new Response($this->fixtureResponseCode, $this->fixtureResponseBody, $this->fixtureTokens);
	}

	/**
	 * Tears down the fixture, for example, closes a network connection.
	 * This method is called after a test is executed.
	 */
	protected function tearDown() {

	}

	/**
	 * @covers Response::isSuccessful
	 */
	public function testIsSuccessful() {
		$expectedIsSuccessful = true;

		$isSuccessful = $this->object->isSuccessful();

		$this->assertEquals($expectedIsSuccessful, $isSuccessful);
	}

	/**
	 * @covers Response::getBadTokens
	 */
	public function testGetBadTokens() {
		$expectedBadTokens = array(
			"token_number_3"
		);

		$badTokens = $this->object->getBadTokens();

		$this->assertEquals($expectedBadTokens, $badTokens);
	}

	/**
	 * @covers Response::getUpdatedTokens
	 */
	public function testGetUpdatedTokens() {
		$expectedUpdatedTokens = array(
			array("old" => "token_number_2", "new" => "updated_token")
		);

		$updatedTokens = $this->object->getUpdatedTokens();

		$this->assertEquals($expectedUpdatedTokens, $updatedTokens);
	}

}
