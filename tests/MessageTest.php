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
        $fixture = [
            "data" => [
                "key1" => "val1",
                "key2" => "val2",
                "key3" => "val3",
                "key4" => "val4"
            ],
            "collapse_key" => "TestCollapseKey",
            "priority" => "high",
            "time_to_live" => 30,
            "restricted_package_name" => "net.coder966.fcm",
            "dry_run" => true,
        ];

        $this->object->put("key1", "val1");
        $this->object->put("key2", "val2");
        $this->object->put("key3", "val3");
        $this->object->put("key4", "val4");
        $this->object->setCollapseKey("TestCollapseKey");
        $this->object->setPriority(Message::PRIORITY_HIGH);
        $this->object->setTimeToLive(30);
        $this->object->setRestrictedPackageName("net.coder966.fcm");
        $this->object->setDryRun();

        $fields = $this->object->fields;

        $this->assertEquals($fixture, $fields);
    }

    /**
     * @covers Message::put
     */
    public function testTooBigMessage() {
        $this->expectException(\RuntimeException::class);

        $message = new Message();
        for($i=100; $i<999; $i++){
            // length is 3+3+3+3=12 chars => size is 12*8 = 96 bytes
            // 4096/96 = 42.xxx so must fail after 43 iterations
            $message->put("key".$i, "val".$i);
        }
    }

}
