<?php

namespace App\Tests\Unit;

use App\Example;
use PHPUnit\Framework\TestCase;
use WeDoCode\Bundle\WeDoCodeTestSuiteBundle\Attribute\Suite;

#[Suite('example')]
class ExampleTest extends TestCase
{
    public function testSimple()
    {
        $this->assertEquals(5, (new Example())->simple(2, 3));
    }

    public function testComplicated()
    {
        $this->assertEquals(6, (new Example())->complicated(2, 3, false));
    }
}
