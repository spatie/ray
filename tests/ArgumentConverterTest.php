<?php

use function PHPUnit\Framework\assertEquals;
use function PHPUnit\Framework\assertFalse;
use function PHPUnit\Framework\assertNull;
use function PHPUnit\Framework\assertTrue;

use Spatie\Ray\ArgumentConverter;

it('returns null for null values', function () {
    assertNull(ArgumentConverter::convertToPrimitive(null));
});

it('returns int values unchanged', function () {
    assertEquals(1, ArgumentConverter::convertToPrimitive(1));
    assertEquals(PHP_INT_MAX, ArgumentConverter::convertToPrimitive(PHP_INT_MAX));
});

it('returns string values unchanged', function () {
    assertEquals('test string', ArgumentConverter::convertToPrimitive('test string'));
    assertEquals('', ArgumentConverter::convertToPrimitive(''));
});

it('returns bool values unchanged', function () {
    assertTrue(ArgumentConverter::convertToPrimitive(true));
    assertFalse(ArgumentConverter::convertToPrimitive(false));
});
