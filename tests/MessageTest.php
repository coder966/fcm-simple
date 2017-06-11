<?php

namespace FCMSimple;

/**
 * Test case for Message class
 *
 * @license Apache License, Version 2.0
 * @author Khalid H. Alharisi <coder966@gmail.com>
 * @link coder966.net
 * @link github.com/coder966/FCMSimple
 */
class MessageTest extends \PHPUnit\Framework\TestCase {

	/**
	 * @var Message
	 */
	protected $object;

	/**
	 * Sets up the fixture, for example, opens a network connection.
	 * This method is called before a test is executed.
	 */
	protected function setUp() {
		require_once '../src/Message.php';
		$this->object = new Message();
	}

	/**
	 * Tears down the fixture, for example, closes a network connection.
	 * This method is called after a test is executed.
	 */
	protected function tearDown() {

	}

	/**
	 * @covers FCMSimple\Message
	 */
	public function test() {
		$fixture = array(
			"key1" => "val1",
			"key2" => "val2"
		);

		$this->object->add("key1", "val1");
		$this->object->add("key2", "val2");

		$map = $this->object->map;

		$this->assertEquals($fixture, $map);
	}

}
