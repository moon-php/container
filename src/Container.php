<?php

namespace Moon\Container;

use Interop\Container\ContainerInterface;
use Interop\Container\Exception\ContainerException;
use Moon\Container\Exception\NotFoundException;

class Container implements ContainerInterface
{
    private $container = [];

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
     * Finds an entry of the container by its identifier and returns it.
     *
     * @param string $alias Identifier of the entry to look for.
     *
     * @throws NotFoundException  No entry was found for this identifier.
     * @throws ContainerException Error while retrieving the entry.
     *
     * @return mixed Entry.
     */
    public function get($alias)
    {
        if (isset($this->container[$alias])) {
            if (is_callable($this->container[$alias])) {
                return $this->container[$alias]($this);
            }

            return $this->container[$alias];
        }
        throw new NotFoundException("$alias doesn't exists in the container");
    }

    /**
     * Returns true if the container can return an entry for the given identifier.
     * Returns false otherwise.
     *
     * @param string $alias Identifier of the entry to look for.
     *
     * @return boolean
     */
    public function has($alias)
    {
        if (!isset($this->container[$alias])) {
            return false;
        }

        return true;
    }
}