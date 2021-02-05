<?php

namespace App\Collection;

class SuiteCollection implements \IteratorAggregate, \Countable
{
    /** @var Suite[] */
    private array $suites = [];

    public function __clone()
    {
        foreach ($this->suites as $name => $suite) {
            $this->suites[$name] = clone $suite;
        }
    }

    public function getIterator()
    {
        return new \ArrayIterator($this->all());
    }

    public function count()
    {
        return \count($this->routes);
    }

    public function add(Suite $suite)
    {
       $this->suites[] = $suite;
    }

    public function all()
    {
        return $this->routes;
    }

    public function get(string $name)
    {
        return $this->routes[$name] ?? null;
    }
}
