<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;

require_once(dirname(__FILE__) . '/../src/Hello.php');

// Hello World
class HelloTest extends TestCase
{
  public function testHello()
  {
    new Hello();
    $this->expectOutputString('Hello World!');
  }
}
