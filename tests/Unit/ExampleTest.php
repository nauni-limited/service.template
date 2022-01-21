<?php

namespace App\Tests\Unit;

use App\Example;
use PHPUnit\Framework\TestCase;
use Nauni\Bundle\NauniTestSuiteBundle\Attribute\Suite;

#[Suite('example')]
class ExampleTest extends TestCase
{
    public function testSimple()
    {
        $example = (new Example());
        $result = $example->simple(2, 3);

        $this->assertEquals(5, $result);
    }

    public function testComplicated()
    {
        $this->assertEquals(6, (new Example())->complicated(2, 3, false));
    }
}
