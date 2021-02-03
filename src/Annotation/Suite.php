<?php

namespace App\Annotations;

use Attribute;

/**
 * Annotation class for @Suite().
 *
 * @Annotation
 * @Target({"CLASS", "METHOD"})
 *
 * @author Tamas Dobo <tom@wedocode.co.uk>
 */
#[Attribute(Attribute::IS_REPEATABLE | Attribute::TARGET_CLASS | Attribute::TARGET_METHOD)]
class Suite
{

    public function __construct()
    {

    }
}
