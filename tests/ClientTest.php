<?php

namespace FCMSimple\Tests;

use FCMSimple\Message;
use FCMSimple\Response;
use FCMSimple\Client;

require_once 'src/Message.php';
require_once 'src/Response.php';
require_once 'src/Client.php';

/**
 * Test case for ClientTest class
 *
 * @license Apache License, Version 2.0
 * @author Khalid H. Alharisi <coder966@gmail.com>
 * @link coder966.net
 * @link github.com/coder966/FCMSimple
 */
class ClientTest extends \PHPUnit\Framework\TestCase {

	/**
	 * @var ClientTest
	 */
	protected $object;

	/**
	 * Sets up the fixture, for example, opens a network connection.
	 * This method is called before a test is executed.
	 */
	protected function setUp() {
		global $argv;
		$serverKey = $argv[2]; // from the command line
		$this->object = new Client($serverKey);
	}

	/**
	 * Tears down the fixture, for example, closes a network connection.
	 * This method is called after a test is executed.
	 */
	protected function tearDown() {

	}

	/**
	 * @covers Client::__construct
	 */
	public function testInvalidServerKey() {
		$this->expectException(\InvalidArgumentException::class);
		new Client("invalid-server-key");
	}

	/**
	 * Tests not setting any tokens
	 * @covers Client::setTokens
	 * @covers Client::send
	 */
	public function testTokens() {
		$this->expectException(\RuntimeException::class);

		$message = new Message();
		$message->add("key", "val");

		$this->object->send($message);
	}

	/**
	 * @covers Client::send
	 */
	public function testSend() {
		$message = new Message();
		$message->add("type", "NEW_POSTS");

		$tokens = array(
			"token1",
			"token2"
		);

		$this->object->send($message, $tokens);
	}

}
