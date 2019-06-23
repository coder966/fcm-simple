<?php

use FCMSimple\Response;

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
        $this->fixtureTokens = [
            "token_number_1",
            "token_number_2",
            "token_number_3"
        ];

        $this->fixtureResponseCode = 200;

        $this->fixtureResponseBody = [
            "multicast_id" => 123456789,
            "success" => 1,
            "failure" => 2,
            "canonical_ids" => 1,
            "results" => [
                ["message_id" => "0:123456789abcdef"],
                ["message_id" => "0:123456789abcdef", "registration_id" => "updated_token"],
                ["error" => "InvalidRegistration"]
            ]
        ];
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
     * @covers Response::getInvalidTokens
     */
    public function testGetInvalidTokens() {
        $expectedInvalidTokens = [
            "token_number_3"
        ];

        $invalidTokens = $this->object->getInvalidTokens();

        $this->assertEquals($expectedInvalidTokens, $invalidTokens);
    }

    /**
     * @covers Response::getUpdatedTokens
     */
    public function testGetUpdatedTokens() {
        $expectedUpdatedTokens = [
            ["old" => "token_number_2", "new" => "updated_token"]
        ];

        $updatedTokens = $this->object->getUpdatedTokens();

        $this->assertEquals($expectedUpdatedTokens, $updatedTokens);
    }

}
