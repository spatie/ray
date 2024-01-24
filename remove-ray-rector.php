<?php

use Rector\Config\RectorConfig;
use Spatie\Ray\Rector\RemoveRayCallRector;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->rule(RemoveRayCallRector::class);
};
