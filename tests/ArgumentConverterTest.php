<?php

use function PHPUnit\Framework\assertEquals;
use function PHPUnit\Framework\assertFalse;
use function PHPUnit\Framework\assertNull;
use function PHPUnit\Framework\assertTrue;

use Spatie\Ray\ArgumentConverter;

it('returns null for null values', function () {
    expect(ArgumentConverter::convertToPrimitive(null))->toBeNull();
});

it('returns int values unchanged', function () {
    expect(ArgumentConverter::convertToPrimitive(1))->toEqual(1);
    expect(ArgumentConverter::convertToPrimitive(PHP_INT_MAX))->toEqual(PHP_INT_MAX);
});

it('returns string values unchanged', function () {
    expect(ArgumentConverter::convertToPrimitive('test string'))->toEqual('test string');
    expect(ArgumentConverter::convertToPrimitive(''))->toEqual('');
});

it('returns bool values unchanged', function () {
    expect(ArgumentConverter::convertToPrimitive(true))->toBeTrue();
    expect(ArgumentConverter::convertToPrimitive(false))->toBeFalse();
});
