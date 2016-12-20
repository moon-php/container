# Moon - Container
A very simple Container

[![Code Climate](https://codeclimate.com/github/moon-php/container/badges/gpa.svg)](https://codeclimate.com/github/moon-php/container) [![Build Status](https://travis-ci.org/moon-php/container.svg?branch=master)](https://travis-ci.org/moon-php/container)

## Introduction

Container is a standalone component incredibly easy.
It's a container (yes, really) that implements only the container-interop interface (waiting the PSR-11) without other method.

## Usage
The container accept as  constructor argument, an associative array.
The key (a.k.a alias) always has an entry.

#### Init Container

    $entries = [
        'alias' => function () {
            return new App\Acme\Class();
        }
    ];
    $container = new Container($entries);
        
The entry can be anything: an integer, a string, a closure or an instance.

#### Check if entry exists by alias

    $entries = [...];
    $container = new Moon\Container($entries);
    $container->has('alias'); // Return true or false

#### Getting an entry

    $entries = [...];
    $c = new Moon\Container($entries);
    $container->get('alias'); // Return the instance or throw a Moon\Container\Exception\NotFoundException
    

#### Entry with container resolution

An entry may require an instance of the container for other entries.
In this case, just use an argument in the function where the container instance will be bound.
 
         $entries = [];
         $entries['ten'] = 10;
         $entries['two'] = 2;
         $entries['multiply'] = function ($c) {
             return $c->get('ten') * $c->get('two');
         };
         $c = new Moon\Container($entries);
         $c->get('multiply'); // Return 20
