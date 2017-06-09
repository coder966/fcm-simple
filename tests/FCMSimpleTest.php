<?php

/**
 * Test case for FCMSimple class
 *
 * @license Apache License, Version 2.0
 * @author Khalid H. Alharisi <coder966@gmail.com>
 * @link coder966.net
 * @link github.com/coder966/FCMSimple
 */
class FCMSimpleTest extends \PHPUnit\Framework\TestCase {

	/**
	 * @var FCMSimple
	 */
	protected $object;

	/**
	 * Sets up the fixture, for example, opens a network connection.
	 * This method is called before a test is executed.
	 */
	protected function setUp() {
		include_once '../src/FCMSimple.php';
		$this->object = new FCMSimple("");
	}

	/**
	 * Tears down the fixture, for example, closes a network connection.
	 * This method is called after a test is executed.
	 */
	protected function tearDown() {

	}

	/**
	 * @covers FCMSimple::setTokens
	 * @todo   Implement testSetTokens().
	 */
	public function testSetTokens() {
		// Remove the following lines when you implement this test.
		$this->markTestIncomplete(
				'This test has not been implemented yet.'
		);
	}

	/**
	 * @covers FCMSimple::send
	 * @todo   Implement testSend().
	 */
	public function testSend() {
		// Remove the following lines when you implement this test.
		$this->markTestIncomplete(
				'This test has not been implemented yet.'
		);
	}

	/**
	 * @covers FCMSimple::getBadTokens
	 * @todo   Implement testGetBadTokens().
	 */
	public function testGetBadTokens() {
		// Remove the following lines when you implement this test.
		$this->markTestIncomplete(
				'This test has not been implemented yet.'
		);
	}

	/**
	 * @covers FCMSimple::getUpdatedTokens
	 * @todo   Implement testGetUpdatedTokens().
	 */
	public function testGetUpdatedTokens() {
		// Remove the following lines when you implement this test.
		$this->markTestIncomplete(
				'This test has not been implemented yet.'
		);
	}

}
