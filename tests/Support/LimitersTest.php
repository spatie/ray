<?php

use function PHPUnit\Framework\assertEquals;
use function PHPUnit\Framework\assertFalse;
use function PHPUnit\Framework\assertTrue;

use Spatie\Ray\Origin\Origin;
use Spatie\Ray\Support\Limiters;

function createOrigin(string $filename, int $line, bool $initialize = true, int $limit = 5): Origin
{
    $result = new Origin($filename, $line);

    if ($initialize) {
        test()->limiters->initialize($result, $limit);
    }

    return $result;
}

beforeEach(function () {
    $this->limiters = new Limiters();
});

it('initializes a limiter for an origin', function () {
    $origins = [new Origin('test.php', 123), new Origin('test.php', 124)];

    $initResults = [
        $this->limiters->initialize($origins[0], 5),
        $this->limiters->initialize($origins[1], 8),
    ];

    assertEquals([0, 5], $initResults[0]);
    assertEquals([0, 8], $initResults[1]);
});

it('increments a limiter counter for an origin', function () {
    $origin = createOrigin('test.php', 123);

    $this->limiters->increment($origin);
    $this->limiters->increment($origin);
    [$counter, $limit] = $this->limiters->increment($origin);

    assertEquals(3, $counter);
});

it('does not increment a limiter counter for an uninitialized origin', function () {
    $origin = new Origin('test.php', 456);

    $incrementResult = $this->limiters->increment($origin);

    assertEquals([false, false], $incrementResult);
});

it('determines if a payload can be sent for a given origin', function () {
    $origin = createOrigin('test.php', 123, true, 2);

    $this->limiters->increment($origin);
    assertTrue($this->limiters->canSendPayload($origin));

    $this->limiters->increment($origin);
    assertFalse($this->limiters->canSendPayload($origin));
});
