#!/usr/bin/env php
<?php

use phpDocumentor\Reflection\DocBlockFactory;
use Roave\BetterReflection\BetterReflection;
use Roave\BetterReflection\Identifier\IdentifierType;
use Roave\BetterReflection\Reflection\ReflectionClass;
use Roave\BetterReflection\SourceLocator\Type\DirectoriesSourceLocator;

require __DIR__ . '/../../vendor/autoload.php';

$reflection = new BetterReflection();
$locator = new DirectoriesSourceLocator([$_SERVER['argv'][2]], $reflection->astLocator());
$classes = $locator->locateIdentifiersByType($reflection->classReflector(), new IdentifierType(IdentifierType::IDENTIFIER_CLASS));

$factory = DocBlockFactory::createInstance();

foreach ($classes as $class) {
    assert($class instanceof ReflectionClass);

    $comment = $class->getDocComment();
    if ($comment === '') {
        continue;
    }

    $doc = $factory->create($comment);
    foreach ($doc->getTags() as $tag) {
        $suite = (string) $tag;
        if ($suite === $_SERVER['argv'][1]) {
            echo $class->getFileName() . "\n";
        }
    }
}
