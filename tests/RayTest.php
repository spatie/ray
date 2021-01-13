<?php

namespace Spatie\Ray\Tests;

use Illuminate\Support\Arr;
use PHPUnit\Framework\TestCase;
use Spatie\Backtrace\Frame;
use Spatie\Ray\Payloads\LogPayload;
use Spatie\Ray\Ray;
use Spatie\Ray\Settings\SettingsFactory;
use Spatie\Ray\Tests\TestClasses\FakeClient;
use Spatie\Snapshots\MatchesSnapshots;

class RayTest extends TestCase
{
    use MatchesSnapshots;

    protected FakeClient $client;

    protected Ray $ray;

    public function setUp(): void
    {
        parent::setUp();

        $this->client = new FakeClient();

        $settings = SettingsFactory::createFromConfigFile();

        $this->ray = new Ray($settings, $this->client, 'fakeUuid');
    }

    /** @test */
    public function it_can_send_a_string_to_ray()
    {
        $this->ray->send('a');

        $this->assertMatchesOsSafeSnapshot($this->client->sentPayloads());
    }

    /** @test */
    public function the_ray_function_also_works()
    {
        Ray::$fakeUuid = 'fakeUuid';

        ray('a');

        $this->assertMatchesOsSafeSnapshot($this->client->sentPayloads());
    }

    /** @test */
    public function it_can_send_an_array_to_ray()
    {
        $this->ray->send(['a' => 1, 'b' => 2]);

        $dumpedValue = $this->getValueOfLastSentContent('values')[0];

        $this->assertStringContainsString('<span class=sf-dump-key>a</span>', $dumpedValue);
        $this->assertStringContainsString('<span class=sf-dump-key>b</span>', $dumpedValue);
    }

    /** @test */
    public function it_can_send_multiple_things_in_one_go_to_ray()
    {
        $this->ray->send('first', 'second', 'third');

        $this->assertMatchesOsSafeSnapshot($this->client->sentPayloads());
    }

    /** @test */
    public function it_can_send_a_color_and_a_size()
    {
        $this->ray->send('test', 'test2')->color('green')->size('big');

        $this->assertMatchesOsSafeSnapshot($this->client->sentPayloads());
    }

    public function it_has_a_helper_function()
    {
        $this->assertInstanceOf(Ray::class, ray());
    }

    /** @test */
    public function it_can_send_a_hide_payload_to_ray()
    {
        $this->ray->hide();

        $this->assertMatchesOsSafeSnapshot($this->client->sentPayloads());
    }

    /** @test */
    public function it_can_send_a_remove_payload_to_ray()
    {
        $this->ray->remove();

        $this->assertMatchesOsSafeSnapshot($this->client->sentPayloads());
    }

    /** @test */
    public function it_can_conditionally_show_something_using_a_boolean()
    {
        $this->ray->send('hey')->showIf(true);
        $this->assertCount(1, $this->client->sentPayloads());

        $this->client->reset();
        $this->ray->send('hey')->showIf(false);
        $this->assertCount(2, $this->client->sentPayloads());
    }

    /** @test */
    public function it_can_conditionally_show_something_using_a_callable()
    {
        $this->ray->send('hey')->showIf(fn () => true);
        $this->assertCount(1, $this->client->sentPayloads());

        $this->client->reset();
        $this->ray->send('hey')->showIf(fn () => false);
        $this->assertCount(2, $this->client->sentPayloads());
    }

    /** @test */
    public function it_can_conditionally_remove_something_using_a_boolean()
    {
        $this->ray->send('hey')->removeWhen(true);
        $this->assertCount(2, $this->client->sentPayloads());

        $this->client->reset();
        $this->ray->send('hey')->removeWhen(false);
        $this->assertCount(1, $this->client->sentPayloads());

        $this->client->reset();
        $this->ray->send('hey')->removeIf(true);
        $this->assertCount(2, $this->client->sentPayloads());
    }

    /** @test */
    public function it_can_conditionally_remove_something_using_a_callable()
    {
        $this->ray->send('hey')->removeWhen(fn () => true);
        $this->assertCount(2, $this->client->sentPayloads());

        $this->client->reset();
        $this->ray->send('hey')->removeWhen(fn () => false);
        $this->assertCount(1, $this->client->sentPayloads());

        $this->client->reset();
        $this->ray->send('hey')->removeIf(fn () => true);
        $this->assertCount(2, $this->client->sentPayloads());
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
    public function it_removes_a_named_stopwatch_when_stopping_time()
    {
        $this->ray->measure('test-timer');
        $this->assertTrue(isset($this->ray::$stopWatches['test-timer']));

        $this->ray->stopTime('test-timer');
        $this->assertFalse(isset($this->ray::$stopWatches['test-timer']));
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
        $this->ray->trace(fn (Frame $frame) => $frame->class === 'PHPUnit\TextUI\TestRunner');

        $frames = $this->getValueOfLastSentContent('frames');

        $firstFrame = $frames[0];

        $this->assertEquals('PHPUnit\TextUI\TestRunner', $firstFrame['class']);
        $this->assertEquals('run', $firstFrame['method']);
    }

    /** @test */
    public function it_has_a_backtrace_alias_for_trace()
    {
        $this->ray->backtrace();
        $frames = $this->getValueOfLastSentContent('frames');

        $this->assertGreaterThanOrEqual(10, count($frames));

        $firstFrame = $frames[0];

        $this->assertEquals('PHPUnit\Framework\TestCase', $firstFrame['class']);
        $this->assertEquals('runTest', $firstFrame['method']);
    }

    /** @test */
    public function it_can_send_the_caller_to_ray()
    {
        $this->ray->caller();

        $frame = $this->getValueOfLastSentContent('frame');

        $this->assertEquals('runBare', $frame['method']);
        $this->assertEquals(TestCase::class, $frame['class']);
    }

    /** @test */
    public function it_can_send_the_ban_payload()
    {
        $this->ray->ban();

        $this->assertMatchesOsSafeSnapshot($this->client->sentPayloads());
    }

    /** @test */
    public function it_can_send_the_charles_payload()
    {
        $this->ray->charles();

        $this->assertMatchesOsSafeSnapshot($this->client->sentPayloads());
    }

    /** @test */
    public function it_can_send_the_notify_payload()
    {
        $this->ray->notify('notification text');

        $this->assertMatchesOsSafeSnapshot($this->client->sentPayloads());
    }

    /** @test */
    public function it_can_send_the_json_payload()
    {
        $this->ray->json('{"message": "message text 2"}');

        $dumpedValue = $this->client->sentPayloads()[0]['payloads'][0]['content']['content'];

        $this->assertStringContainsString('<span class=sf-dump-key>message</span>', $dumpedValue);
        $this->assertStringContainsString('<span class=sf-dump-str title="14 characters">message text 2</span>', $dumpedValue);
    }

    /** @test */
    public function it_can_send_the_toJson_payload()
    {
        $this->ray->toJson(['message' => 'message text 1']);

        $this->assertMatchesOsSafeSnapshot($this->client->sentPayloads());
    }

    /** @test */
    public function it_can_send_the_file_content_payload()
    {
        $this->ray->file(__DIR__ .'/testSettings/ray.php');

        $this->assertMatchesOsSafeSnapshot($this->client->sentPayloads());
    }

    /** @test */
    public function it_can_send_the_new_screen_payload()
    {
        $this->ray->newScreen('my-screen');
        $this->ray->newScreen();

        $this->assertMatchesOsSafeSnapshot($this->client->sentPayloads());
    }

    /** @test */
    public function it_can_send_the_clear_screen_payload()
    {
        $this->ray->clearScreen();

        $this->assertMatchesOsSafeSnapshot($this->client->sentPayloads());
    }

    /** @test */
    public function it_can_send_the_class_name()
    {
        $this->ray->className($this);

        $this->assertMatchesOsSafeSnapshot($this->client->sentPayloads());
    }

    /** @test */
    public function it_can_send_a_falsy_value()
    {
        $this->ray->send(false);

        $this->assertMatchesOsSafeSnapshot($this->client->sentPayloads());
    }

    /** @test */
    public function it_is_macroable()
    {
        Ray::macro('myCustomFunction', function (string $value) {
            $payload = new LogPayload($value . '-suffix');

            $this->sendRequest([$payload]);

            return $this;
        });

        $this->ray->myCustomFunction('my value');

        $this->assertMatchesOsSafeSnapshot($this->client->sentPayloads());
    }

    /** @test */
    public function when_ray_is_not_running_the_pause_call_will_not_blow_up()
    {
        $this->ray->pause();

        $this->assertEquals('create_lock', $this->client->sentPayloads()[0]['payloads'][0]['type']);
    }

    /** @test */
    public function it_can_send_custom_stuff_to_ray()
    {
        $this->ray->sendCustom('my custom content');
        $this->assertMatchesOsSafeSnapshot($this->client->sentPayloads());

        $this->ray->sendCustom('my custom content', 'custom label');
        $this->assertMatchesOsSafeSnapshot($this->client->sentPayloads());
    }

    /** @test */
    public function it_can_send_data_to_ray_and_return_the_data()
    {
        $data = ['a' => 1, 'b' => 2];

        $result = $this->ray->pass($data);

        $this->assertEquals($data, $result);

        $dumpedValue = $this->getValueOfLastSentContent('values')[0];

        $this->assertStringContainsString('<span class=sf-dump-key>a</span>', $dumpedValue);
        $this->assertStringContainsString('<span class=sf-dump-key>b</span>', $dumpedValue);
    }

    /** @test */
    public function it_can_rewrite_the_file_paths_using_the_config_values()
    {
        $settings = SettingsFactory::createFromConfigFile();

        $settings->remote_path = 'tests';
        $settings->local_path = 'local_path';

        $this->ray = new Ray($settings, $this->client, 'fakeUuid');

        $this->ray->send('hey');

        $this->assertEquals('/local_path/RayTest.php', $this->client->sentPayloads()[0]['payloads'][0]['origin']['file']);
    }

    /** @test */
    public function it_returns_itself_and_does_not_send_anything_when_calling_send_without_arguments()
    {
        $settings = SettingsFactory::createFromConfigFile();

        $this->ray = new Ray($settings, $this->client, 'fakeUuid');

        $result = $this->ray->send();

        $this->assertCount(0, $this->client->sentPayloads());
        $this->assertEquals($this->ray, $result);
        $this->assertNull($this->getValueOfLastSentContent('values'));
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

    protected function assertMatchesOsSafeSnapshot($data)
    {
        $this->assertMatchesJsonSnapshot(json_encode($data));
    }
}
