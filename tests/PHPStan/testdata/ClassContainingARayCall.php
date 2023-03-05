<?php

namespace Spatie\Ray\Tests\PHPStan\testdata;

class ClassContainingARayCall
{
    public function __construct()
    {
        ray('I am being constructed, therefore I am');
    }
}
