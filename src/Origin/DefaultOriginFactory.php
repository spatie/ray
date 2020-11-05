<?php

namespace Spatie\Timber\Origin;

class DefaultOriginFactory implements OriginFactory
{
    public function getOrigin(): Origin
    {
        return new Origin('file', 1);
    }
}
