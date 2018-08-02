<?php

declare(strict_types=1);

namespace Moon\Container;

use Moon\Container\Exception\NotFoundException;
use PHPUnit\Framework\TestCase;

class ContainerTest extends TestCase
{
    /**
     * Test that integer is added to container.
     */
    public function testInteger()
    {
        $c = new Container(['integer' => 1]);
        $this->assertEquals(1, $c->get('integer'));
    }

    /**
     * Test that string is added to container.
     */
    public function testString()
    {
        $c = new Container(['string' => 'This is a string']);
        $this->assertSame('This is a string', $c->get('string'));
    }

    /**
     * Test that array is added to container.
     */
    public function testArray()
    {
        $c = new Container(['array' => ['one' => 1, 'string' => 'A string']]);
        $this->assertSame(['one' => 1, 'string' => 'A string'], $c->get('array'));
    }

    /**
     * Test that object is added to container.
     */
    public function testObjects()
    {
        $entries = [
            'SplObjectStorageInstance' => function () {
                return new \SplObjectStorage();
            },
        ];

        $c = new Container($entries);
        $this->assertInstanceOf(\SplObjectStorage::class, $c->get('SplObjectStorageInstance'));
    }

    /**
     * Test that closure is added to container.
     */
    public function testClosure()
    {
        $entries = [
            'closure' => function () {
                return function ($var) {
                    return $var;
                };
            },
        ];

        $c = new Container($entries);
        $closure = $c->get('closure');
        $this->assertSame('example', $closure('example'));
    }

    /**
     * Test that a dependency is reachable into the container.
     */
    public function testDepends()
    {
        $entries = [];
        $entries['numberOne'] = 10;
        $entries['numberTwo'] = 2;
        $entries['multiply'] = function ($c) {
            /* @var Container $c */
            return $c->get('numberOne') * $c->get('numberTwo');
        };

        $c = new Container($entries);
        $this->assertSame(20, $c->get('multiply'));
    }

    /**
     * Test that has entry.
     */
    public function testHasEntry()
    {
        $c = new Container(['entry' => true]);
        $this->assertTrue($c->has('entry'));
    }

    /**
     * Test that is always returned the same object.
     */
    public function testReturnAlwaysSameInstances()
    {
        $entries = [
            'instance' => function () {
                return new \SplObjectStorage();
            },
        ];
        $c = new Container($entries);
        $this->assertSame($c->get('instance'), $c->get('instance'));
    }

    /**
     * Test that has not an entry.
     */
    public function testHasNotEntry()
    {
        $c = new Container([]);
        $this->assertFalse($c->has('not-existing-key'));
    }

    /**
     * Expect a not found exception.
     */
    public function testNotFoundException()
    {
        $this->expectException(NotFoundException::class);
        $c = new Container([]);
        $c->get('neverSetAlias');
    }
}
