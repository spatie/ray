<?php

namespace Spatie\Ray\Tests;

use BadFunctionCallException;
use BadMethodCallException;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Arr;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Spatie\Backtrace\Frame;
use Spatie\Ray\Origin\Hostname;
use Spatie\Ray\PayloadFactory;
use Spatie\Ray\Payloads\CallerPayload;
use Spatie\Ray\Payloads\LogPayload;
use Spatie\Ray\Ray;
use Spatie\Ray\Settings\SettingsFactory;
use Spatie\Ray\Tests\TestClasses\FakeClient;
use Spatie\Snapshots\MatchesSnapshots;
use Spatie\TestTime\TestTime;

class RayTest extends TestCase
{
    use MatchesSnapshots;

    /** @var \Spatie\Ray\Tests\TestClasses\FakeClient */
    protected $client;

    /** @var \Spatie\Ray\Settings\Settings */
    protected $settings;

    /** @var \Spatie\Ray\Ray */
    protected $ray;

    public function setUp(): void
    {
        parent::setUp();

        Hostname::set('fake-hostname');

        $this->client = new FakeClient();

        $this->settings = SettingsFactory::createFromConfigFile();

        $this->ray = new Ray($this->settings, $this->client, 'fakeUuid');

        $this->ray->enable();

        Ray::rateLimiter()->clear();
    }

    /** @test */
    public function it_can_send_a_string_to_ray()
    {
        $this->ray->send('a');

        $this->assertMatchesOsSafeSnapshot($this->client->sentPayloads());
    }

    /** @test */
    public function it_can_send_a_strings_that_might_be_interpreted_as_callables_to_ray()
    {
        $this->ray->send('value');

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

    /** @test */
    public function it_can_send_a_screen_color()
    {
        $this->ray->screenGreen();

        $this->assertMatchesOsSafeSnapshot($this->client->sentPayloads());
    }

    /** @test */
    public function it_can_send_a_label()
    {
        $this->ray->send('my value')->label('my label');

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
        $this->ray->send('hey')->showIf(function () {
            return true;
        });
        $this->assertCount(1, $this->client->sentPayloads());

        $this->client->reset();
        $this->ray->send('hey')->showIf(function () {
            return false;
        });
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
        $this->ray->send('hey')->removeWhen(function () {
            return true;
        });
        $this->assertCount(2, $this->client->sentPayloads());

        $this->client->reset();
        $this->ray->send('hey')->removeWhen(function () {
            return false;
        });
        $this->assertCount(1, $this->client->sentPayloads());

        $this->client->reset();
        $this->ray->send('hey')->removeIf(function () {
            return true;
        });
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
        $this->ray->trace(function (Frame $frame) {
            return $frame->class === 'PHPUnit\TextUI\TestRunner';
        });

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
    public function it_can_send_the_exception_payload()
    {
        $this->ray->exception(new Exception('This is an exception'));

        $payloads = $this->client->sentPayloads();

        $this->assertCount(2, $payloads);
        $this->assertEquals('exception', $payloads[0]['payloads'][0]['type']);
        $this->assertEquals(Exception::class, $payloads[0]['payloads'][0]['content']['class']);
        $this->assertEquals('This is an exception', $payloads[0]['payloads'][0]['content']['message']);
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
    public function it_can_send_multiple_json_payloads()
    {
        $this->ray->json(
            '{"message": "message text 1"}',
            '{"message": "message text 2"}'
        );

        $dumpedValue1 = $this->client->sentPayloads()[0]['payloads'][0]['content']['content'];
        $dumpedValue2 = $this->client->sentPayloads()[0]['payloads'][1]['content']['content'];

        $this->assertStringContainsString('<span class=sf-dump-key>message</span>', $dumpedValue1);
        $this->assertStringContainsString('<span class=sf-dump-key>message</span>', $dumpedValue2);
        $this->assertStringContainsString('<span class=sf-dump-str title="14 characters">message text 1</span>', $dumpedValue1);
        $this->assertStringContainsString('<span class=sf-dump-str title="14 characters">message text 2</span>', $dumpedValue2);
    }

    /** @test */
    public function it_can_send_the_toJson_payload()
    {
        $this->ray->toJson(['message' => 'message text 1']);

        $this->assertMatchesOsSafeSnapshot($this->client->sentPayloads());
    }

    /** @test */
    public function it_can_send_multiple_toJson_payloads()
    {
        $this->ray->toJson(
            ['message' => 'message text 1'],
            ['message' => 'message text 2']
        );

        $this->assertMatchesOsSafeSnapshot($this->client->sentPayloads());
    }

    /** @test */
    public function it_can_send_the_file_content_payload()
    {
        $this->ray->file(__DIR__ .'/testSettings/ray.php');
        $this->ray->file('missing.php');

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
    public function it_can_send_the_clear_all_payload()
    {
        $this->ray->clearAll();

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
        $payload = new CallerPayload([
            new Frame('/app/app/MyFile.php', 1, []),
            new Frame('/app/app/MyFile.php', 2, []),
        ]);

        $payload->remotePath = '/app';
        $payload->localPath = '/some/local/path';

        $this->assertEquals('/some/local/path/app/MyFile.php', $payload->getContent()['frame']['file_name']);
    }

    /** @test */
    public function it_only_rewrites_paths_for_matching_remote_paths()
    {
        $payload = new CallerPayload([
            new Frame('/app/files/MyFile.php', 1, []),
            new Frame('/app/files/MyFile.php', 2, []),
        ]);

        $payload->remotePath = '/files';
        $payload->localPath = '/some/local/path';

        $this->assertEquals('/app/files/MyFile.php', $payload->getContent()['frame']['file_name']);

        $payload->remotePath = '/app';
        $payload->localPath = '/some/local/path';

        $this->assertEquals('/some/local/path/files/MyFile.php', $payload->getContent()['frame']['file_name']);
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

    /** @test */
    public function it_can_determine_how_many_times_a_particular_piece_of_code_was_called_for_a_given_name()
    {
        foreach (range(1, 2) as $i) {
            ray()->count('first');

            foreach (range(1, 2) as $j) {
                ray()->count('second');
                ray()->count('another');
            }

            ray()->count('another');
        }

        $this->assertEquals(2, Ray::$counters->get('first'));
        $this->assertEquals(4, Ray::$counters->get('second'));
        $this->assertEquals(6, Ray::$counters->get('another'));
    }

    /** @test */
    public function it_can_determine_how_many_times_a_particular_piece_of_code_was_called_without_a_name()
    {
        foreach (range(1, 2) as $i) {
            ray()->count();

            foreach (range(1, 2) as $j) {
                ray()->count();
            }
        }

        $this->assertEquals("Called 4 times.", $this->client->sentPayloads()[5]['payloads'][0]['content']['content']);
    }

    /** @test */
    public function it_creates_a_Ray_instance_with_default_settings_when_create_is_called_without_arguments()
    {
        $ray = Ray::create(null, '1-2-3-4');

        $this->assertNotNull($ray);
        $this->assertEquals('1-2-3-4', $ray->uuid);
        $this->assertEquals($ray->settings, SettingsFactory::createFromConfigFile());
    }

    /** @test */
    public function it_merges_default_settings_into_existing_settings()
    {
        $settings = SettingsFactory::createFromConfigFile();

        $this->assertNull($settings->test);
        $this->assertEquals(23517, $settings->port);

        $settings->setDefaultSettings(['test' => 'testvalue']);

        $this->assertEquals('testvalue', $settings->test);
        $this->assertEquals(23517, $settings->port);
    }

    /** @test */
    public function it_can_send_the_php_info_payload()
    {
        $this->ray->phpinfo();

        $payloads = $this->client->sentPayloads();

        $this->assertCount(1, $payloads);

        $this->assertEquals('table', $payloads[0]['payloads'][0]['type']);
    }

    /** @test */
    public function the_php_info_can_report_specific_options()
    {
        $this->ray->phpinfo('default_mimetype');

        $payloads = $this->client->sentPayloads();

        $this->assertCount(1, $payloads);

        $this->assertArrayHasKey('default_mimetype', $payloads[0]['payloads'][0]['content']['values']);
    }

    /** @test */
    public function it_sends_an_image_payload()
    {
        $this->ray->image('http://localhost/test.jpg');

        $this->assertMatchesOsSafeSnapshot($this->client->sentPayloads());
    }

    /** @test */
    public function it_sends_a_base64_encoded_image_payload()
    {
        $this->ray->image('dGVzdCBzdHJpbmc=');
        $this->ray->image('data:image/png;base64,dGVzdCBzdHJpbmc=');

        $this->assertMatchesOsSafeSnapshot($this->client->sentPayloads());
    }

    /** @test */
    public function it_sends_a_table_payload()
    {
        $this->ray->table([
            'First' => 'First value',
            'Second' => 'Second value',
            'Third' => 'Third value',
        ]);

        $this->assertMatchesOsSafeSnapshot($this->client->sentPayloads());
    }

    /** @test */
    public function it_can_send_a_carbon_payload()
    {
        $frozenTime = TestTime::freeze('Y-m-d H:i:s', '2020-01-01 00:00:00');

        $carbon = new Carbon();

        ray()->carbon($carbon);

        $this->assertCount(1, $this->client->sentPayloads());

        $payload = $this->client->sentPayloads()[0];
        $this->assertEquals($frozenTime, $payload['payloads'][0]['content']['formatted']);
        $this->assertEquals($frozenTime->getTimestamp(), $payload['payloads'][0]['content']['timestamp']);
        $this->assertEquals(date_default_timezone_get(), $payload['payloads'][0]['content']['timezone']);
    }

    /** @test */
    public function it_sends_an_xml_payload()
    {
        $this->ray->xml('<one><two>2</two></one>');

        $this->assertMatchesOsSafeSnapshot($this->client->sentPayloads());
    }

    /** @test */
    public function it_can_send_the_raw_values()
    {
        $this->ray->raw(new Carbon(), 'string', ['a' => 1]);

        $payloads = $this->client->sentPayloads();

        $this->assertEquals('log', $payloads[0]['payloads'][0]['type']);
        $this->assertEquals('log', $payloads[0]['payloads'][1]['type']);
        $this->assertEquals('log', $payloads[0]['payloads'][2]['type']);
    }

    /** @test */
    public function it_returns_a_ray_instance_when_calling_raw_without_arguments()
    {
        $instance = $this->ray->raw();

        $this->assertInstanceOf(Ray::class, $instance);
        $this->assertMatchesOsSafeSnapshot($this->client->sentPayloads());
    }

    /** @test */
    public function it_will_send_a_specialized_payloads_by_default()
    {
        $this->ray->send(new Carbon(), 'string', ['a => 1']);

        $payloads = $this->client->sentPayloads();

        $this->assertEquals('carbon', $payloads[0]['payloads'][0]['type']);
        $this->assertEquals('log', $payloads[0]['payloads'][1]['type']);
        $this->assertEquals('log', $payloads[0]['payloads'][2]['type']);
    }

    /** @test */
    public function it_sends_the_hide_application_payload()
    {
        $this->ray->hideApp();

        $this->assertMatchesOsSafeSnapshot($this->client->sentPayloads());
    }

    /** @test */
    public function it_sends_the_show_application_payload()
    {
        $this->ray->showApp();

        $this->assertMatchesOsSafeSnapshot($this->client->sentPayloads());
    }

    /** @test */
    public function it_sends_an_html_payload()
    {
        $this->ray->html('<strong>test</strong>');

        $this->assertMatchesOsSafeSnapshot($this->client->sentPayloads());
    }

    /** @test */
    public function it_sends_a_text_payload()
    {
        $this->ray->text('text string');
        $this->ray->text('another   <strong>text</strong>' . PHP_EOL . '  string');

        $lastPayload = $this->client->sentPayloads()[1]['payloads'][0];

        $this->assertStringContainsString('&nbsp;&nbsp;&nbsp;&lt;strong&gt;', $lastPayload['content']['content']);
        $this->assertStringContainsString('<br>', $lastPayload['content']['content']);
        $this->assertMatchesOsSafeSnapshot($this->client->sentPayloads());
    }

    /** @test */
    public function it_sends_a_null_payload()
    {
        $this->ray->send(null);

        $this->assertMatchesOsSafeSnapshot($this->client->sentPayloads());
    }

    /** @test */
    public function it_returns_zero_when_accessing_a_missing_counter()
    {
        $this->assertEquals(0, Ray::$counters->get('missing'));
        ray()->count('missing');
        $this->assertEquals(1, Ray::$counters->get('missing'));
    }

    /** @test */
    public function it_sets_the_ray_instance_for_a_counter()
    {
        $ray1 = ray();
        $ray2 = ray();

        $ray1->count('first');

        $ray1::$counters->setRay('first', $ray1);

        $this->assertEquals($ray1, $ray1::$counters->increment('first')[0]);

        $ray1::$counters->setRay('first', $ray2);

        $this->assertEquals($ray2, $ray1::$counters->increment('first')[0]);
    }

    /** @test */
    public function it_clears_all_counters()
    {
        Ray::$counters->clear();

        $this->assertEquals(0, Ray::$counters->get('first'));

        ray()->count('first');

        $this->assertEquals(1, Ray::$counters->get('first'));

        ray()->clearCounters();

        $this->assertEquals(0, Ray::$counters->get('first'));
    }

    /** @test */
    public function it_returns_the_value_of_a_named_counter()
    {
        $this->assertEquals(0, ray()->counterValue('first'));

        ray()->count('first');

        $this->assertEquals(1, ray()->counterValue('first'));

        ray()->count('first');

        $this->assertEquals(2, ray()->counterValue('first'));
    }

    /** @test */
    public function it_will_respect_the_raw_values_config_setting()
    {
        $this->settings->always_send_raw_values = true;
        $this->ray->send(new Carbon());
        $this->assertEquals('log',  $this->client->sentPayloads()[0]['payloads'][0]['type']);
    }

    /** @test */
    public function it_can_be_disabled()
    {
        $this->ray->send('test payload 1');
        $this->ray->disable();
        $this->ray->send('test payload 2');

        $this->assertCount(1, $this->client->sentPayloads());
    }

    /** @test */
    public function it_can_be_reenabled_after_being_disabled()
    {
        $this->ray->enable();
        $this->ray->send('test payload 1');
        $this->ray->disable();
        $this->ray->send('test payload 2');
        $this->ray->enable();
        $this->ray->send('test payload 3');

        $this->assertCount(2, $this->client->sentPayloads());
    }

    /** @test */
    public function it_returns_the_correct_enabled_state()
    {
        Ray::$enabled = true;
        $this->assertTrue($this->ray->enabled());
        $this->assertFalse($this->ray->disabled());

        Ray::$enabled = false;
        $this->assertFalse($this->ray->enabled());
        $this->assertTrue($this->ray->disabled());
    }

    /** @test */
    public function it_defaults_to_enabled_state()
    {
        $this->assertTrue($this->ray->enabled());
    }

    /** @test */
    public function it_checks_the_availablity_of_the_Ray_server()
    {
        $this->client->changePortAndReturnOriginal(34993);

        $this->assertFalse($this->client->performAvailabilityCheck());
    }

    /** @test */
    public function it_respects_the_enabled_property()
    {
        $ray = $this->getNewRay()->disable();

        $this->assertFalse($ray->enabled());
        $this->assertFalse($this->getNewRay()->enabled());

        $this->getNewRay()->enable();

        $this->assertTrue($ray->enabled());
        $this->assertTrue($this->getNewRay()->enabled());
    }

    /** @test */
    public function it_respects_the_enabled_property_when_sending_payloads()
    {
        $ray = $this->getNewRay()->disable();
        $ray->send('test message 1');
        $this->assertCount(0, $this->client->sentPayloads());

        $ray->enable();
        $ray->send('test message 2');
        $this->assertCount(1, $this->client->sentPayloads());

        $ray->disable();
        $ray->send('test message 3');
        $this->assertCount(1, $this->client->sentPayloads());
    }

    /** @test */
    public function it_can_quickly_send_a_request()
    {
        $before = microtime(true);

        $payloads = PayloadFactory::createForValues([
            'value 1' => 'nested',
            'value 2',
        ]);

        $this->ray->sendRequest($payloads);

        $after = microtime(true);

        $this->assertLessThan(0.005, $after - $before);
    }

    /** @test */
    public function it_can_quickly_call_the_ray_helper()
    {
        $before = microtime(true);

        ray('a');

        $after = microtime(true);

        $this->assertLessThan(0.05, $after - $before);
    }

    /** @test */
    public function it_can_quickly_call_send_function()
    {
        $before = microtime(true);

        $this->ray->send('a');

        $after = microtime(true);

        $this->assertLessThan(0.005, $after - $before);
    }

    /** @test */
    public function it_can_limit_the_number_of_payloads_sent_from_a_loop()
    {
        $limit = 5;

        for ($i = 0; $i < 10; $i++) {
            $this->getNewRay()->limit($limit)->send("limited loop iteration $i");
        }

        $this->assertCount($limit, $this->client->sentPayloads());
    }

    /** @test */
    public function it_only_limits_the_number_of_payloads_sent_from_the_line_that_calls_limit()
    {
        $limit = 5;
        $iterations = 10;

        for ($i = 0; $i < $iterations; $i++) {
            $this->getNewRay()->limit($limit)->send("limited loop iteration $i");
            $this->getNewRay()->send("unlimited loop iteration $i");
        }

        $this->assertCount($limit + $iterations, $this->client->sentPayloads());
    }

    /** @test */
    public function it_can_handle_multiple_consecutive_calls_to_limit()
    {
        $limit = 2;

        for ($i = 0; $i < 10; $i++) {
            $this->getNewRay()->limit($limit)
                ->send("limited loop A iteration $i");

            $this->getNewRay()->limit($limit)
                ->send("limited loop B iteration $i");
        }

        $this->assertMatchesOsSafeSnapshot($this->client->sentPayloads());
    }

    /** @test */
    public function it_can_conditionally_send_payloads_using_if_with_a_truthy_conditional_and_without_a_callback()
    {
        for ($i = 0; $i < 10; $i++) {
            $this->ray->if($i < 5)->text("value: {$i}");
        }

        $this->assertCount(5, $this->client->sentPayloads());
    }

    /** @test */
    public function it_can_conditionally_send_payloads_using_if_with_a_callable_conditional_param()
    {
        for ($i = 0; $i < 10; $i++) {
            $this->ray->if(function () use ($i) {
                return $i < 5;
            })->text("value: {$i}");
        }

        $this->assertCount(5, $this->client->sentPayloads());
    }

    /** @test */
    public function it_can_conditionally_send_payloads_using_if_with_a_callback()
    {
        $this->ray->if(true, function ($ray) {
            $ray->text('one');
        });

        $this->ray->if(false, function ($ray) {
            $ray->text('two');
        });

        $this->assertCount(1, $this->client->sentPayloads());
    }

    /** @test */
    public function it_can_chain_method_calls_when_using_if_with_a_callback_and_a_false_condition()
    {
        $this->ray->if(false, function ($ray) {
            $ray->text('one')->green();
        })
        ->text('two')
        ->blue();

        $this->ray
            ->text('three')
            ->if(false, function ($ray) {
                $ray->green();
            });

        $this->assertMatchesOsSafeSnapshot($this->client->sentPayloads());
    }

    /** @test */
    public function it_can_chain_multiple_when_calls_with_callbacks_together()
    {
        $this->ray
            ->text('test')
            ->if(true, function ($ray) {
                $ray->green();
            })
            ->if(false, function ($ray) {
                $ray->text('text modified');
            })
            ->if(true, function ($ray) {
                $ray->large();
            })
            ->hide();

        $this->assertMatchesOsSafeSnapshot($this->client->sentPayloads());
    }

    /** @test */
    public function it_cannot_call_when_rate_limit_max_has_reached()
    {
        Ray::rateLimiter()
            ->clear()
            ->max(1);

        ray('this can pass');
        ray('this cannot pass, but triggers a warning call');
        ray('this cannot pass');

        $this->assertCount(2, $this->client->sentPayloads());

        $this->assertSame('Rate limit has been reached...', $this->client->sentPayloads()[1]['payloads'][0]['content']['content']);
    }

    /** @test */
    public function it_sends_a_payload_once_when_called_with_arguments()
    {
        for ($i = 0; $i < 5; $i++) {
            $this->getNewRay()->once($i);
        }

        $this->assertCount(1, $this->client->sentPayloads());
        $this->assertEquals([0], $this->client->sentPayloads()[0]['payloads'][0]['content']['values']);
    }

    /** @test */
    public function it_sends_a_payload_once_when_called_without_arguments()
    {
        for ($i = 0; $i < 5; $i++) {
            $this->getNewRay()->once()->text($i);
        }

        $this->assertCount(1, $this->client->sentPayloads());
        $this->assertEquals(0, $this->client->sentPayloads()[0]['payloads'][0]['content']['content']);
    }

    /** @test */
    public function it_sends_a_payload_once_while_allowing_calls_to_limit()
    {
        for ($i = 0; $i < 5; $i++) {
            $this->ray->once($i);
            $this->getNewRay()->limit(5)->text($i);
        }

        $this->assertCount(6, $this->client->sentPayloads());
    }

    /** @test */
    public function it_does_nothing_if_no_exceptions_are_thrown_from_a_callable_while_using_catch_with_a_callback()
    {
        $ray = $this->getNewRay();

        $ray->send(function () use ($ray) {
            return $ray->text('hello world');
        })->catch(function ($exception, $ray) {
            $ray->text($exception->getMessage());
        });

        $this->assertCount(1, $this->client->sentPayloads());
        $this->assertMatchesOsSafeSnapshot($this->client->sentPayloads());
    }

    /** @test */
    public function it_handles_exceptions_using_catch_with_a_callback_and_classname_parameter()
    {
        $this->getNewRay()->send(function () {
            throw new Exception('test');
        })->catch(Exception::class);

        // 2 payloads for exceptions
        $this->assertCount(2, $this->client->sentPayloads());
    }

    /** @test */
    public function it_handles_exceptions_using_and_catch_without_a_callback()
    {
        $this->getNewRay()->send(function () {
            throw new Exception('test');
        })->catch();

        // 2 payloads are sent when ray->exception() is called
        $this->assertCount(2, $this->client->sentPayloads());
    }

    /** @test */
    public function it_handles_exceptions_using_catch_with_a_callback()
    {
        $this->getNewRay()->send(function () {
            throw new Exception('test');
        })->catch(function ($e, $ray) {
            return $ray->text($e->getMessage());
        });

        $this->assertCount(1, $this->client->sentPayloads());
        $this->assertMatchesOsSafeSnapshot($this->client->sentPayloads());
    }

    /** @test */
    public function it_handles_exceptions_using_catch_with_a_callback_and_a_typed_parameter()
    {
        $this->getNewRay()->send(function () {
            throw new Exception('test');
        })->catch(function (Exception $e, $ray) {
            return $ray->text($e->getMessage());
        });

        $this->assertCount(1, $this->client->sentPayloads());
        $this->assertMatchesOsSafeSnapshot($this->client->sentPayloads());
    }

    /** @test */
    public function it_handles_exceptions_using_catch_with_a_callback_and_a_union_type_parameter_on_php8_and_higher()
    {
        if (PHP_MAJOR_VERSION < 8) {
            $this->markTestSkipped('test requires PHP 8+');
        }

        // we need to use include here to avoid PHP 7.x parsing/syntax errors for union types/other PHP 8 features
        include __DIR__ . '/includes/Php8OnlyTests.php';

        // the included file has a function with the same name as this method, so call it
        $function = __FUNCTION__;
        $function($this, $this->getNewRay(), $this->client);
    }

    /** @test */
    public function it_handles_exceptions_using_catch_with_an_array_of_callbacks_with_typed_parameters()
    {
        $this->getNewRay()->send(function () {
            throw new InvalidArgumentException('test');
        })->catch([
            function (BadMethodCallException $e, $ray) {
                return $ray->text(get_class($e));
            },
            function (InvalidArgumentException $e, $ray) {
                $ray->text(get_class($e));
            },
        ]);

        $this->assertCount(1, $this->client->sentPayloads());
        $this->assertMatchesOsSafeSnapshot($this->client->sentPayloads());
    }

    /** @test */
    public function it_handles_exceptions_using_catch_with_an_array_of_exception_classnames()
    {
        $this->getNewRay()->send(function () {
            throw new InvalidArgumentException('test');
        })->catch([
            BadMethodCallException::class,
            InvalidArgumentException::class,
        ]);

        $this->assertCount(2, $this->client->sentPayloads());
    }

    /** @test */
    public function it_does_not_handle_exceptions_using_catch_with_an_array_of_exception_classnames_that_do_not_match_the_exception()
    {
        $this->expectException(\InvalidArgumentException::class);

        $this->getNewRay()->send(function () {
            throw new InvalidArgumentException('test');
        })->catch([
            BadMethodCallException::class,
            BadFunctionCallException::class,
        ]);

        $this->assertCount(0, $this->client->sentPayloads());
    }

    /** @test */
    public function it_does_not_handle_exceptions_using_catch_with_a_callback_and_a_typed_parameter_different_than_the_exception_class()
    {
        $this->expectException(\Exception::class);

        $this->getNewRay()->send(function () {
            throw new Exception('test');
        })->catch(function (InvalidArgumentException $e, $ray) {
            return $ray->text($e->getMessage());
        });

        $this->assertCount(0, $this->client->sentPayloads());
    }

    /** @test */
    public function it_allows_chaining_additional_methods_after_handling_an_exception()
    {
        $this->getNewRay()->send(function ($ray) {
            $ray->text('hello world');

            throw new Exception('test');
        })->catch()->blue()->small();

        $this->assertCount(5, $this->client->sentPayloads());
    }

    /** @test */
    public function it_throws_exceptions_when_calling_throwExceptions()
    {
        $this->expectException(Exception::class);

        $this->getNewRay()->send(function ($ray) {
            $ray->text('hello world');

            throw new Exception('test');
        })->throwExceptions();
    }

    /** @test */
    public function it_can_dump_a_string_with_a_global_function_name()
    {
        if (PHP_MAJOR_VERSION < 8) {
            $this->markTestSkipped('test requires PHP 8+');
        }

        $this->ray->send('array_map');

        $this->assertMatchesOsSafeSnapshot($this->client->sentPayloads());
    }

    /** @test */
    public function it_can_send_a_separator()
    {
        $this->ray->send('separator');

        $this->assertMatchesOsSafeSnapshot($this->client->sentPayloads());
    }

    protected function getNewRay(): Ray
    {
        return Ray::create($this->client, 'fakeUuid');
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

    public function assertMatchesOsSafeSnapshot($data)
    {
        $this->assertMatchesJsonSnapshot(json_encode($data));
    }
}
