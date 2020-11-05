<?php

namespace Spatie\Timber\Origin;

interface OriginFactory
{
    public function getOrigin(): Origin;
}
