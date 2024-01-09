<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Spatie\Ray\Rector\RemoveRayCallRector;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->rule(RemoveRayCallRector::class);
};
