<?php

namespace Harvyde\Tests;

use Harvyde\Multisite;
use Harvyde\Tests\MultisiteTestCase;

class MultisiteTest extends MultisiteTestCase {

  public function testProdEnv() {
    $_SERVER['HTTP_HOST'] = 'www.yourdomain.com';
    Multisite::setup(
      [
        'site1' => 'yourdomain.com',
        'site2' => 'anotherdomain.com',
      ]
    );
    $this->assertEquals('prod', Multisite::env());
    $this->assertEquals(true, Multisite::is_env('prod'));
    $this->assertEquals(true, Multisite::is_env(['prod', 'new']));
    $this->assertEquals(0, Multisite::debug_level());
  }

  public function testProd2Env() {
    $_SERVER['HTTP_HOST'] = 'www.yourdomain.com';
    Multisite::setup(
      [
        'site1' => 'yourdomain.com',
        'site2' => 'anotherdomain.com',
      ]
    );
    $this->assertEquals('prod', Multisite::env());
    $this->assertEquals(true, Multisite::is_env('prod'));
    $this->assertEquals(true, Multisite::is_env(['prod']));
    $this->assertEquals(true, Multisite::is_env(['prod', 'dev']));
    $this->assertEquals(0, Multisite::debug_level());
  }

  public function testDevEnv() {
    $_SERVER['HTTP_HOST'] = 'dev.yourdomain.com';
    Multisite::setup(
      [
        'site1' => 'yourdomain.com',
        'site2' => 'anotherdomain.com',
      ]
    );
    $this->assertEquals('dev', Multisite::env());
    $this->assertEquals(true, Multisite::is_env('dev'));
    $this->assertEquals(true, Multisite::is_env(['dev']));
    $this->assertEquals(true, Multisite::is_env(['dev', 'prod']));
    $this->assertEquals(0, Multisite::debug_level());
  }

  public function testLocEnv() {
    $_SERVER['HTTP_HOST'] = 'loc.yourdomain.com';
    Multisite::setup(
      [
        'site1' => 'yourdomain.com',
        'site2' => 'anotherdomain.com',
      ]
    );
    $this->assertEquals('loc', Multisite::env());
    $this->assertEquals(true, Multisite::is_env('loc'));
    $this->assertEquals(true, Multisite::is_env(['loc']));
    $this->assertEquals(true, Multisite::is_env(['loc', 'dev']));
    $this->assertEquals(2, Multisite::debug_level());
  }

  public function testLoc2Env() {
    $_SERVER['HTTP_HOST'] = 'loc.yourdomain.com';
    Multisite::setup(
      [
        'site1' => 'yourdomain.com',
        'site2' => 'anotherdomain.com',
      ]
    );
    $this->assertEquals('loc', Multisite::env());
    $this->assertEquals(true, Multisite::is_env('loc'));
    $this->assertEquals(true, Multisite::is_env(['loc']));
    $this->assertEquals(true, Multisite::is_env(['loc', 'dev']));
    $this->assertEquals(2, Multisite::debug_level());
    $this->assertContains(Multisite::debug_level(), [0, 1, 2]);
  }

  public function testLocDomain() {

    $_SERVER['HTTP_HOST'] = 'loc.yourdomain.com';
    Multisite::setup(
      [
        'site1' => 'yourdomain.com',
        'site2' => 'anotherdomain.com',
      ]
    );
    $this->assertEquals('loc.yourdomain.com', Multisite::domain('site1'));
  }

  public function testLoc2Domain() {
    $_SERVER['HTTP_HOST'] = 'croogo.yourdomain.com';
    Multisite::setup(
      [
        'site1' => 'yourdomain.com',
        'site2' => 'anotherdomain.com',
      ]
    );
    $this->assertEquals('croogo.yourdomain.com', Multisite::domain('site1'));
  }

  public function testProdDomain() {
    $_SERVER['HTTP_HOST'] = 'www.yourdomain.com';
    Multisite::setup(
      [
        'site1' => 'yourdomain.com',
        'site2' => 'anotherdomain.com',
      ]
    );
    $this->assertEquals('www.yourdomain.com', Multisite::domain('site1'));
  }

  public function testProd2Domain() {
    $_SERVER['HTTP_HOST'] = 'yourdomain.com';
    Multisite::setup(
      [
        'site1' => 'yourdomain.com',
        'site2' => 'anotherdomain.com',
      ]
    );
    $this->assertEquals('yourdomain.com', Multisite::domain('site1'));
  }

  public function testIsDomain() {
    $_SERVER['HTTP_HOST'] = 'www.yourdomain.com';
    Multisite::setup(
      [
        'site1' => 'yourdomain.com',
        'site2' => 'anotherdomain.com',
      ]
    );
    $this->assertEquals(true, Multisite::isDomain('site1'));
    $this->assertEquals(false, Multisite::isDomain('site2'));
  }


}
