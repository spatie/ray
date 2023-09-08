<?php

use Spatie\Ray\Support\PlainTextDumper;

it('can dump an array', function () {
    $test = [
        [
            'name' => 'Foo',
            'age' => 42,
        ],
        [
            'name' => 'Foo',
            'age' => 42,
        ],
    ];

    $result = PlainTextDumper::dump($test);

    assertMatchesOsSafeSnapshot($result);
});

it('can dump an object', function () {
    $object = new stdClass();
    $object->foo = 'bar';

    $result = PlainTextDumper::dump($object);
    assertMatchesOsSafeSnapshot($result);
});

it('can dump scalar types', function () {
    $result = PlainTextDumper::dump(123);
    assertMatchesOsSafeSnapshot($result);

    $result = PlainTextDumper::dump(123.55);
    assertMatchesOsSafeSnapshot($result);

    $result = PlainTextDumper::dump('foo');
    assertMatchesOsSafeSnapshot($result);

    $result = PlainTextDumper::dump(true);
    assertMatchesOsSafeSnapshot($result);
});

it('can handle circular references', function () {
    class A
    {
        public $b;
    }

    class B
    {
        public $a;
    }

    $a = new A();
    $b = new B();
    $a->b = $b;
    $b->a = $a;

    $result = PlainTextDumper::dump($a);
    assertMatchesOsSafeSnapshot($result);
});
