<?php

namespace Spatie\Ray\Tests;

use Illuminate\Support\Arr;
use PHPUnit\Framework\TestCase;
use Spatie\Backtrace\Frame;
use Spatie\Ray\Ray;
use Spatie\Ray\Tests\TestClasses\FakeClient;
use Spatie\Snapshots\MatchesSnapshots;

class RayTest extends TestCase
{
    use MatchesSnapshots;

    private FakeClient $client;

    private Ray $ray;

    public function setUp(): void
    {
        parent::setUp();

        $this->client = new FakeClient();

        $this->ray = new Ray($this->client, 'fakeUuid');
    }

    /** @test */
    public function it_can_send_a_string_to_ray()
    {
        $this->ray->send('a');

        $this->assertMatchesSnapshot($this->client->sentPayloads());
    }

    /** @test */
    public function it_can_send_an_array_to_ray()
    {
        $this->ray->send(['a' => 1, 'b' => 2]);

        $this->assertMatchesSnapshot($this->client->sentPayloads());
    }

    /** @test */
    public function it_can_send_multiple_things_in_one_go_to_ray()
    {
        $this->ray->send('first', 'second', 'third');

        $this->assertMatchesSnapshot($this->client->sentPayloads());
    }

    /** @test */
    public function it_can_send_a_color_and_a_size()
    {
        $this->ray->send('test', 'test2')->color('green')->size('big');

        $this->assertMatchesSnapshot($this->client->sentPayloads());
    }

    public function it_has_a_helper_function()
    {
        $this->assertInstanceOf(Ray::class, ray());
    }

    /** @test */
    public function it_can_send_a_hide_payload_to_ray()
    {
        $this->ray->hide();

        $this->assertMatchesSnapshot($this->client->sentPayloads());
    }

    /** @test */
    public function it_can_send_a_remove_payload_to_ray()
    {
        $this->ray->remove();

        $this->assertMatchesSnapshot($this->client->sentPayloads());
    }

    /** @test */
    public function it_can_measure_time_and_memory()
    {
        $this->ray->measure();
        $this->assertCount(1, $this->client->sentPayloads());
        $this->assertTrue($this->getValueOfLastSentContent('is_new_timer'));
        $this->assertEquals(0, $this->getValueOfLastSentContent('total_time'));
        $this->assertEquals(0, $this->getValueOfLastSentContent('max_memory_usage_during_total_time'));
        $this->assertEquals(0, $this->getValueOfLastSentContent('time_since_last_call'));
        $this->assertEquals(0, $this->getValueOfLastSentContent('max_memory_usage_since_last_call'));

        usleep(1000);

        $this->ray->measure();
        $this->assertCount(2, $this->client->sentPayloads());
        $this->assertFalse($this->getValueOfLastSentContent('is_new_timer'));
        $this->assertNotEquals(0, $this->getValueOfLastSentContent('total_time'));
        $this->assertNotEquals(0, $this->getValueOfLastSentContent('max_memory_usage_during_total_time'));
        $this->assertNotEquals(0, $this->getValueOfLastSentContent('time_since_last_call'));
        $this->assertNotEquals(0, $this->getValueOfLastSentContent('max_memory_usage_since_last_call'));

        usleep(1000);
        $this->ray->measure();
        $this->assertCount(3, $this->client->sentPayloads());
        $this->assertGreaterThan(
            $this->getValueOfLastSentContent('time_since_last_call'),
            $this->getValueOfLastSentContent('total_time'),
        );

        $this->ray->stopTime();

        $this->ray->measure();
        $this->assertTrue($this->getValueOfLastSentContent('is_new_timer'));
        $this->assertEquals(0, $this->getValueOfLastSentContent('total_time'));
        $this->assertEquals(0, $this->getValueOfLastSentContent('max_memory_usage_during_total_time'));
        $this->assertEquals(0, $this->getValueOfLastSentContent('time_since_last_call'));
        $this->assertEquals(0, $this->getValueOfLastSentContent('max_memory_usage_since_last_call'));
    }

    /** @test */
    public function it_can_measure_using_multiple_timers()
    {
        $this->ray->measure('my-timer');
        $this->assertEquals('my-timer', $this->getValueOfLastSentContent('name'));
    }

    /** @test */
    public function it_can_measure_a_closure()
    {
        $closure = function () {
            sleep(1);
        };

        $this->ray->measure($closure);

        $this->assertCount(1, $this->client->sentPayloads());
        $this->assertNotEquals(0, $this->getValueOfLastSentContent('total_time'));
        $this->assertNotEquals(0, $this->getValueOfLastSentContent('max_memory_usage_during_total_time'));
        $this->assertNotEquals(0, $this->getValueOfLastSentContent('time_since_last_call'));
        $this->assertNotEquals(0, $this->getValueOfLastSentContent('max_memory_usage_since_last_call'));
    }

    /** @test */
    public function it_can_send_backtrace_to_ray()
    {
        $this->ray->trace();

        $frames = $this->getValueOfLastSentContent('frames');

        $this->assertGreaterThanOrEqual(10, count($frames));

        $firstFrame = $frames[0];

        $this->assertEquals('PHPUnit\Framework\TestCase', $firstFrame['class']);
        $this->assertEquals('runTest', $firstFrame['method']);
    }

    /** @test */
    public function it_can_send_backtrace_frames_starting_from_a_specific_frame()
    {
        $this->ray->trace(fn(Frame $frame) => $frame->class === 'PHPUnit\TextUI\TestRunner');

        $frames = $this->getValueOfLastSentContent('frames');

        $firstFrame = $frames[0];

        $this->assertEquals('PHPUnit\TextUI\TestRunner', $firstFrame['class']);
        $this->assertEquals('run', $firstFrame['method']);
    }

    protected function getValueOfLastSentContent(string $contentKey)
    {
        $payload = $this->client->sentPayloads();

        if (! count($payload)) {
            return null;
        }

        $lastPayload = end($payload);

        return Arr::get($lastPayload, "payloads.0.content.{$contentKey}");
    }
}
