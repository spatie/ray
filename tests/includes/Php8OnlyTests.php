<?php

if (!function_exists('it_handles_exceptions_using_catch_with_a_callback_and_a_union_type_parameter_on_php8_and_higher')) {
    function it_handles_exceptions_using_catch_with_a_callback_and_a_union_type_parameter_on_php8_and_higher($test, $newRay, $client)
    {
        $newRay->send(function () {
            throw new \Exception('test');
        })->catch(function (\InvalidArgumentException|\Exception $e, $ray) {
            return $ray->text($e->getMessage());
        });

        $test->assertCount(1, $client->sentPayloads());
        $test->assertMatchesOsSafeSnapshot($client->sentPayloads());
    }
}
