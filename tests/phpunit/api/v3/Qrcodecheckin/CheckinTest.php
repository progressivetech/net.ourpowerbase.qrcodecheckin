<?php

use Civi\Test\HeadlessInterface;
use Civi\Test\HookInterface;
use Civi\Test\TransactionalInterface;

/**
 * Qrcodecheckin.Checkin API Test Case
 * This is a generic test class implemented with PHPUnit.
 * @group headless
 */
class api_v3_Qrcodecheckin_CheckinTest extends \CivixPhar\PHPUnit\Framework\TestCase implements HeadlessInterface, HookInterface, TransactionalInterface {

  /**
   * Civi\Test has many helpers, like install(), uninstall(), sql(), and sqlFile().
   * See: https://docs.civicrm.org/dev/en/latest/testing/phpunit/#civitest
   */
  public function setUpHeadless() {
    return \Civi\Test::headless()
      ->installMe(__DIR__)
      ->apply();
  }

  /**
   * The setup() method is executed before the test is executed (optional).
   */
  public function setUp() {
    parent::setUp();
  }

  /**
   * The tearDown() method is executed after the test was executed (optional)
   * This can be used for cleanup.
   */
  public function tearDown() {
    parent::tearDown();
  }

  /**
   * Simple example test case.
   *
   * Note how the function name begins with the word "test".
   */
  public function testApiExample() {
    $result = civicrm_api3('Qrcodecheckin', 'Checkin', array('magicword' => 'sesame'));
    $this->assertEquals('Twelve', $result['values'][12]['name']);
  }

}
