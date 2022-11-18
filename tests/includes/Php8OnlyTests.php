<?php

use function PHPUnit\Framework\assertCount;

it('handles exceptions using catch with a callback and a union type parameter on php8 and higher', function () {
    $newRay = getNewRay();

    $newRay->send(function () {
        throw new \Exception('test');
    })->catch(function (\InvalidArgumentException | \Exception $e, $ray) {
        return $ray->text($e->getMessage());
    });

    expect($this->client->sentPayloads())->toHaveCount(1);
    assertMatchesOsSafeSnapshot($this->client->sentPayloads());
});
