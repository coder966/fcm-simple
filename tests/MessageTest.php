<?php

namespace FCMSimple\Tests;

use FCMSimple\Message;

require_once 'src/Message.php';

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
		$this->object = new Message();
	}

	/**
	 * Tears down the fixture, for example, closes a network connection.
	 * This method is called after a test is executed.
	 */
	protected function tearDown() {

	}

	/**
	 * @covers Message
	 */
	public function test() {
		$fixture = array(
			"data" => array(
				"key1" => "val1",
				"key2" => "val2"
			),
			"collapse_key" => "TestCollapseKey",
			"priority" => "high",
			"time_to_live" => 30,
			"dry_run" => true,
		);

		$this->object->add("key1", "val1");
		$this->object->add("key2", "val2");
		$this->object->setCollapseKey("TestCollapseKey");
		$this->object->setPriority(Message::PRIORITY_HIGH);
		$this->object->setTimeToLive(30);
		$this->object->setDryRun();

		$fields = $this->object->fields;

		$this->assertEquals($fixture, $fields);
	}

}
