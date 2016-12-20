<?php

namespace Moon\Container\Unit;

use Moon\Container\Container;
use Moon\Container\Exception\NotFoundException;

class ContainerTest extends \PHPUnit_Framework_TestCase
{
    public function testInteger()
    {
        $c = new Container(['integer' => 1]);
        $this->assertEquals(1, $c->get('integer'));
    }

    public function testString()
    {
        $c = new Container(['string' => 'This is a string']);
        $this->assertEquals('This is a string', $c->get('string'));
    }

    public function testArray()
    {
        $c = new Container(['array' => ['one' => 1, 'string' => 'A string']]);
        $this->assertArrayHasKey('one', $c->get('array'));
        $this->assertArrayHasKey('string', $c->get('array'));
    }

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

    public function testHasAssertTrue()
    {
        $c = new Container(['entry' => true]);
        $this->assertTrue($c->has('entry'));
    }

    public function testHasAssertFalse()
    {
        $c = new Container();
        $this->assertFalse($c->has('not-existing-key'));
    }

    public function testNotFoundException()
    {
        $this->expectException(NotFoundException::class);
        $c = new Container();
        $c->get('neverSetAlias');
    }
}