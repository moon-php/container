<?php

namespace Moon\Container\Unit;

use Moon\Container\Container;
use Moon\Container\Exception\NotFoundException;

class ContainerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test that integer is added to container
     */
    public function testInteger()
    {
        $c = new Container(['integer' => 1]);
        $this->assertEquals(1, $c->get('integer'));
    }

    /**
     * Test that string is added to container
     */
    public function testString()
    {
        $c = new Container(['string' => 'This is a string']);
        $this->assertEquals('This is a string', $c->get('string'));
    }

    /**
     * Test that array is added to container
     */
    public function testArray()
    {
        $c = new Container(['array' => ['one' => 1, 'string' => 'A string']]);
        $this->assertArrayHasKey('one', $c->get('array'));
        $this->assertArrayHasKey('string', $c->get('array'));
    }

    /**
     * Test that object is added to container
     */
    public function testObjects()
    {
        $entries = [
            'SplObjectStorageInstance' => function () {
                return new \SplObjectStorage();
            }
        ];

        $c = new Container($entries);
        $this->assertInstanceOf(\SplObjectStorage::class, $c->get('SplObjectStorageInstance'));
    }

    /**
     * Test that closure is added to container
     */
    public function testClosure()
    {
        $entries = [
            'closure' => function () {
                return function ($var) {
                    return $var;
                };
            }
        ];

        $c = new Container($entries);
        $closure = $c->get('closure');
        $this->assertEquals('example', $closure('example'));
    }

    /**
     * Test that a dependency is reachable into the container
     */
    public function testDepends()
    {
        $entries = [];
        $entries['numberOne'] = 10;
        $entries['numberTwo'] = 2;
        $entries['multiply'] = function ($c) {
            /** @var Container $c */
            return $c->get('numberOne') * $c->get('numberTwo');
        };

        $c = new Container($entries);
        $this->assertEquals(20, $c->get('multiply'));
    }

    /**
     * Test that has entry
     */
    public function testHasEntry()
    {
        $c = new Container(['entry' => true]);
        $this->assertTrue($c->has('entry'));
    }

    /**
     * Test that has not an entry
     */
    public function testHasNotEntry()
    {
        $c = new Container();
        $this->assertFalse($c->has('not-existing-key'));
    }

    /**
     * Expect a not found exception
     */
    public function testNotFoundException()
    {
        $this->expectException(NotFoundException::class);
        $c = new Container();
        $c->get('neverSetAlias');
    }
}