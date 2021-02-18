<?php

namespace App;

use WeDoCode\Bundle\WeDoCodeTestSuiteBundle\Attribute\Suite;

#[Suite('example')]
class Example
{
    public function simple(int $a, int $b): int
    {
        return $a + $b;
    }

    public function complicated(string $a, string $b, bool $c)
    {
        return $c ? $a + $b : $a * $b;
    }
}