# Moon - Container
A very simple Container

[ ![Codeship Status for damianopetrungaroteam/container](https://codeship.com/projects/7d59dcf0-7e7e-0134-6553-22f53c89765f/status?branch=master)](https://codeship.com/projects/181674)

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