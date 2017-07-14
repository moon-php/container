<?php

declare(strict_types=1);

namespace Moon\Container;

use Moon\Container\Exception\NotFoundException;
use Psr\Container\ContainerInterface;

class Container implements ContainerInterface
{
    /**
     * Contains all entries
     *
     * @var $container array
     */
    private $container = [];

    /**
     * Contains all instantiated entries
     *
     * @var $instances array
     */
    private $instances = [];

    /**
     * Container constructor accept an array.
     * It must be an associative array with a 'alias' key and a 'entry' value.
     * The value can be anything: an integer, a string, a closure or an instance.
     *
     * @param array $entries
     */
    public function __construct($entries = [])
    {
        foreach ($entries as $alias => $entry) {
            $this->container[$alias] = $entry;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function get($alias)
    {
        if (!isset($this->container[$alias])) {
            throw new NotFoundException("$alias doesn't exists in the container");
        }

        if (!is_callable($this->container[$alias])) {
            return $this->container[$alias];
        }

        if (!isset($this->instances[$alias])) {
            $this->instances[$alias] = $this->container[$alias]($this);
        }

        return $this->instances[$alias];
    }

    /**
     * {@inheritdoc}
     */
    public function has($alias): bool
    {
        if (!isset($this->container[$alias])) {
            return false;
        }

        return true;
    }
}