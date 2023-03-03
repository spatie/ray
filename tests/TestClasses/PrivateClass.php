<?php

namespace Spatie\Ray\Tests\TestClasses;

class PrivateClass
{
    private $privateProperty = 'this is the value of the private property';

    private function privateMethod()
    {
        return 'this is the result of the private method';
    }
}
