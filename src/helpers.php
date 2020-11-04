<?php

use Spatie\Timber\Timber;

if (! function_exists('timber')) {
    function timber(...$args): Timber
    {
        return (new Timber())->send(...$args);
    }
}
