<?php

namespace App\Attribute;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS)]
class Suite
{
    public function __construct(
        public string $suite
    ) {}

    public function getSuite(): string
    {
        return $this->suite;
    }
}