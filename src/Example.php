<?php

namespace App;

use Nauni\Bundle\NauniTestSuiteBundle\Attribute\Suite;

#[Suite('example')]
class Example
{
    public function simple(int $a, int $b): int
    {
        return $a + $b;
    }

    public function complicated(int $a, int $b, bool $c): int
    {
        return $c ? $a + $b : $a * $b;
    }
}
