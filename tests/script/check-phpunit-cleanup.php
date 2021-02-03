#!/usr/bin/env php
<?php

use PhpParser\Node;
use PhpParser\NodeTraverser;
use PhpParser\NodeVisitorAbstract;
use PhpParser\ParserFactory;
use Symfony\Component\Finder\Finder;

require __DIR__ . '/../../vendor/autoload.php';

$properties = new class extends NodeVisitorAbstract
{
    public array $defined = [];

    public array $unset = [];

    public function beforeTraverse(array $nodes)
    {
        $this->defined = [];
        $this->unset = [];
        return null;
    }

    public function enterNode(Node $node)
    {
        # Get any defined properties
        if ($node instanceof Node\Stmt\PropertyProperty) {
            $this->defined[] = $node->name->name;
        }

        # Get any unset properties
        if ($node instanceof Node\Stmt\Unset_) {
            foreach ($node->vars as $parameter) {
                if ($parameter instanceof Node\Expr\PropertyFetch) {
                    $this->unset[] = $parameter->name;
                }
            }
        }

        return null;
    }
};

$traverser = new NodeTraverser();
$traverser->addVisitor($properties);

$parser = (new ParserFactory())->create(ParserFactory::PREFER_PHP7);

$path = __DIR__ . '/test';
$files = (new Finder())->in($path)->name('*Test.php');

$exitCode = 0;
foreach ($files as $file) {
    $filename = $file->getRelativePathname();

    $nodes = $parser->parse((string) file_get_contents("{$path}/{$filename}"));
    assert($nodes !== null);
    $traverser->traverse($nodes);

    $unclean = array_diff($properties->defined, $properties->unset);
    if (count($unclean) === 0) {
        continue;
    }

    echo "tests/{$filename} has properties not cleaned up during tearDown():\n";
    foreach ($unclean as $property) {
        echo "* {$property}\n";
    }
    echo "\n";

    $exitCode = 163;
}

exit($exitCode);
