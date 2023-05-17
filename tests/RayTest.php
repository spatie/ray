<?php

use Carbon\Carbon;
use Illuminate\Support\Arr;


use Spatie\Backtrace\Frame;

use Spatie\Ray\Origin\Hostname;

use Spatie\Ray\PayloadFactory;
use Spatie\Ray\Payloads\CallerPayload;
use Spatie\Ray\Payloads\LogPayload;
use Spatie\Ray\Ray;
use Spatie\Ray\Settings\SettingsFactory;
use Spatie\Ray\Tests\TestClasses\FakeClient;
use Spatie\Ray\Tests\TestClasses\PrivateClass;
use Spatie\TestTime\TestTime;

function getNewRay(): Ray
{
    return Ray::create(test()->client, 'fakeUuid');
}

function getValueOfLastSentContent(string $contentKey)
{
    $payload = test()->client->sentPayloads();

    if (! count($payload)) {
        return null;
    }

    $lastPayload = end($payload);

    return Arr::get($lastPayload, "payloads.0.content.{$contentKey}");
}

if (PHP_MAJOR_VERSION >= 8) {
    include __DIR__ . '/includes/Php8OnlyTests.php';
}

beforeEach(function () {
    Hostname::set('fake-hostname');

    $this->client = new FakeClient();

    $this->settings = SettingsFactory::createFromConfigFile();

    $this->ray = new Ray($this->settings, $this->client, 'fakeUuid');

    $this->ray->enable();

    Ray::rateLimiter()->clear();
});

it('can send a string to ray', function () {
    $this->ray->send('a');

    assertMatchesOsSafeSnapshot($this->client->sentPayloads());
});

it('can send a strings that might be interpreted as callables to ray', function () {
    $this->ray->send('value');

    assertMatchesOsSafeSnapshot($this->client->sentPayloads());
});

test('the ray function also works', function () {
    Ray::$fakeUuid = 'fakeUuid';

    ray('a');

    assertMatchesOsSafeSnapshot($this->client->sentPayloads());
});

it('can send an array to ray', function () {
    $this->ray->send(['a' => 1, 'b' => 2]);

    $dumpedValue = getValueOfLastSentContent('values')[0];

    expect($dumpedValue)->toContain('<span class=sf-dump-key>a</span>');
    expect($dumpedValue)->toContain('<span class=sf-dump-key>b</span>');
});

it('can send multiple things in one go to ray', function () {
    $this->ray->send('first', 'second', 'third');

    assertMatchesOsSafeSnapshot($this->client->sentPayloads());
});

it('can send a color and a size', function () {
    $this->ray->send('test', 'test2')->color('green')->size('big');

    assertMatchesOsSafeSnapshot($this->client->sentPayloads());
});

it('can send a screen color', function () {
    $this->ray->screenGreen();

    assertMatchesOsSafeSnapshot($this->client->sentPayloads());
});

it('can send a label', function () {
    $this->ray->send('my value')->label('my label');

    assertMatchesOsSafeSnapshot($this->client->sentPayloads());
});

it('has a helper function', function () {
    expect(ray())->toBeInstanceOf(Ray::class);
});

it('can send a hide payload to ray', function () {
    $this->ray->hide();

    assertMatchesOsSafeSnapshot($this->client->sentPayloads());
});

it('can send a remove payload to ray', function () {
    $this->ray->remove();

    assertMatchesOsSafeSnapshot($this->client->sentPayloads());
});

it('can conditionally show something using a boolean', function () {
    $this->ray->send('hey')->showIf(true);
    expect($this->client->sentPayloads())->toHaveCount(1);

    $this->client->reset();
    $this->ray->send('hey')->showIf(false);
    expect($this->client->sentPayloads())->toHaveCount(2);
});

it('can conditionally show something using a callable', function () {
    $this->ray->send('hey')->showIf(function () {
        return true;
    });
    expect($this->client->sentPayloads())->toHaveCount(1);

    $this->client->reset();
    $this->ray->send('hey')->showIf(function () {
        return false;
    });
    expect($this->client->sentPayloads())->toHaveCount(2);
});

it('can conditionally remove something using a boolean', function () {
    $this->ray->send('hey')->removeWhen(true);
    expect($this->client->sentPayloads())->toHaveCount(2);

    $this->client->reset();
    $this->ray->send('hey')->removeWhen(false);
    expect($this->client->sentPayloads())->toHaveCount(1);

    $this->client->reset();
    $this->ray->send('hey')->removeIf(true);
    expect($this->client->sentPayloads())->toHaveCount(2);
});

it('can conditionally remove something using a callable', function () {
    $this->ray->send('hey')->removeWhen(function () {
        return true;
    });
    expect($this->client->sentPayloads())->toHaveCount(2);

    $this->client->reset();
    $this->ray->send('hey')->removeWhen(function () {
        return false;
    });
    expect($this->client->sentPayloads())->toHaveCount(1);

    $this->client->reset();
    $this->ray->send('hey')->removeIf(function () {
        return true;
    });
    expect($this->client->sentPayloads())->toHaveCount(2);
});

it('can measure time and memory', function () {
    $this->ray->measure();
    expect($this->client->sentPayloads())->toHaveCount(1);
    expect(getValueOfLastSentContent('is_new_timer'))->toBeTrue();
    expect(getValueOfLastSentContent('total_time'))->toEqual(0);
    expect(getValueOfLastSentContent('max_memory_usage_during_total_time'))->toEqual(0);
    expect(getValueOfLastSentContent('time_since_last_call'))->toEqual(0);
    expect(getValueOfLastSentContent('max_memory_usage_since_last_call'))->toEqual(0);

    usleep(1000);

    $this->ray->measure();
    expect($this->client->sentPayloads())->toHaveCount(2);
    expect(getValueOfLastSentContent('is_new_timer'))->toBeFalse();
    expect(getValueOfLastSentContent('total_time'))->not->toEqual(0);
    expect(getValueOfLastSentContent('max_memory_usage_during_total_time'))->not->toEqual(0);
    expect(getValueOfLastSentContent('time_since_last_call'))->not->toEqual(0);
    expect(getValueOfLastSentContent('max_memory_usage_since_last_call'))->not->toEqual(0);

    usleep(1000);
    $this->ray->measure();
    expect($this->client->sentPayloads())->toHaveCount(3);
    expect(getValueOfLastSentContent('total_time'))->toBeGreaterThanOrEqual(getValueOfLastSentContent('time_since_last_call'));

    $this->ray->stopTime();

    $this->ray->measure();
    expect(getValueOfLastSentContent('is_new_timer'))->toBeTrue();
    expect(getValueOfLastSentContent('total_time'))->toEqual(0);
    expect(getValueOfLastSentContent('max_memory_usage_during_total_time'))->toEqual(0);
    expect(getValueOfLastSentContent('time_since_last_call'))->toEqual(0);
    expect(getValueOfLastSentContent('max_memory_usage_since_last_call'))->toEqual(0);
});

it('can measure using multiple timers', function () {
    $this->ray->measure('my-timer');
    expect(getValueOfLastSentContent('name'))->toEqual('my-timer');
});

it('can measure a closure', function () {
    $closure = function () {
        sleep(1);
    };

    $this->ray->measure($closure);

    expect($this->client->sentPayloads())->toHaveCount(1);
    expect(getValueOfLastSentContent('total_time'))->not->toEqual(0);
    expect(getValueOfLastSentContent('max_memory_usage_during_total_time'))->not->toEqual(0);
    expect(getValueOfLastSentContent('time_since_last_call'))->not->toEqual(0);
    expect(getValueOfLastSentContent('max_memory_usage_since_last_call'))->not->toEqual(0);
});

it('removes a named stopwatch when stopping time', function () {
    $this->ray->measure('test-timer');
    /** @phpstan-ignore-next-line */
    expect(isset($this->ray::$stopWatches['test-timer']))->toBeTrue();

    $this->ray->stopTime('test-timer');
    /** @phpstan-ignore-next-line */
    expect(isset($this->ray::$stopWatches['test-timer']))->toBeFalse();
});

it('can send backtrace to ray', function () {
    $this->ray->trace();
    $frames = getValueOfLastSentContent('frames');

    expect(count($frames))->toBeGreaterThanOrEqual(10);

    $firstFrame = $frames[0];

    expect($firstFrame['class'])->toEqual('P\Tests\RayTest');
    expect($firstFrame['method'])->toEqual('{closure}');
});

it('can send backtrace frames starting from a specific frame', function () {
    $this->ray->trace(function (Frame $frame) {
        return $frame->class === 'PHPUnit\TextUI\TestRunner';
    });

    $frames = getValueOfLastSentContent('frames');

    $firstFrame = $frames[0];

    expect($firstFrame['class'])->toEqual('PHPUnit\TextUI\TestRunner');
    expect($firstFrame['method'])->toEqual('run');
});

it('has a backtrace alias for trace', function () {
    $this->ray->backtrace();
    $frames = getValueOfLastSentContent('frames');

    expect(count($frames))->toBeGreaterThanOrEqual(10);

    $firstFrame = $frames[0];

    expect($firstFrame['class'])->toEqual('P\Tests\RayTest');
    expect($firstFrame['method'])->toEqual('{closure}');
});

it('can send the caller to ray', function () {
    $this->ray->caller();

    $frame = getValueOfLastSentContent('frame');

    expect($frame['method'])->toEqual('call_user_func');
    expect($frame['class'])->toEqual(null);
});

it('can send the ban payload', function () {
    $this->ray->ban();

    assertMatchesOsSafeSnapshot($this->client->sentPayloads());
});

it('can send the charles payload', function () {
    $this->ray->charles();

    assertMatchesOsSafeSnapshot($this->client->sentPayloads());
});

it('can send the notify payload', function () {
    $this->ray->notify('notification text');

    assertMatchesOsSafeSnapshot($this->client->sentPayloads());
});

it('can send the exception payload', function () {
    $this->ray->exception(new Exception('This is an exception'));

    $payloads = $this->client->sentPayloads();

    expect($payloads)->toHaveCount(2);
    expect($payloads[0]['payloads'][0]['type'])->toEqual('exception');
    expect($payloads[0]['payloads'][0]['content']['class'])->toEqual(Exception::class);
    expect($payloads[0]['payloads'][0]['content']['message'])->toEqual('This is an exception');
});

it('can send the json payload', function () {
    $this->ray->json('{"message": "message text 2"}');

    $dumpedValue = $this->client->sentPayloads()[0]['payloads'][0]['content']['content'];

    expect($dumpedValue)->toContain('<span class=sf-dump-key>message</span>');
    expect($dumpedValue)->toContain('<span class=sf-dump-str title="14 characters">message text 2</span>');
});

it('can send multiple json payloads', function () {
    $this->ray->json(
        '{"message": "message text 1"}',
        '{"message": "message text 2"}'
    );

    $dumpedValue1 = $this->client->sentPayloads()[0]['payloads'][0]['content']['content'];
    $dumpedValue2 = $this->client->sentPayloads()[0]['payloads'][1]['content']['content'];

    expect($dumpedValue1)->toContain('<span class=sf-dump-key>message</span>');
    expect($dumpedValue2)->toContain('<span class=sf-dump-key>message</span>');
    expect($dumpedValue1)->toContain('<span class=sf-dump-str title="14 characters">message text 1</span>');
    expect($dumpedValue2)->toContain('<span class=sf-dump-str title="14 characters">message text 2</span>');
});

it('can send the toJson payload', function () {
    $this->ray->toJson(['message' => 'message text 1']);

    assertMatchesOsSafeSnapshot($this->client->sentPayloads());
});

it('can send multiple toJson payloads', function () {
    $this->ray->toJson(
        ['message' => 'message text 1'],
        ['message' => 'message text 2']
    );

    assertMatchesOsSafeSnapshot($this->client->sentPayloads());
});

it('can send the file content payload', function () {
    $this->ray->file(__DIR__ .'/testSettings/ray.php');
    $this->ray->file('missing.php');

    assertMatchesOsSafeSnapshot($this->client->sentPayloads());
});

it('can send the new screen payload', function () {
    $this->ray->newScreen('my-screen');
    $this->ray->newScreen();

    assertMatchesOsSafeSnapshot($this->client->sentPayloads());
});

it('can sanitize the name of a new screen', function() {
   $this->ray->newScreen('__pest_evaluable_this_is_the_test_name');

   $usedName = $this->client->sentPayloads()[0]['payloads'][0]['content']['name'];

   expect($usedName)->toBe('this is the test name');
});


it('can send the clear screen payload', function () {
    $this->ray->clearScreen();

    assertMatchesOsSafeSnapshot($this->client->sentPayloads());
});

it('can send the clear all payload', function () {
    $this->ray->clearAll();

    assertMatchesOsSafeSnapshot($this->client->sentPayloads());
});

it('can send the class name', function () {
    $this->ray->className($this);

    assertMatchesOsSafeSnapshot($this->client->sentPayloads());
});

it('can send a falsy value', function () {
    $this->ray->send(false);

    assertMatchesOsSafeSnapshot($this->client->sentPayloads());
});

it('is macroable', function () {
    Ray::macro('myCustomFunction', function (string $value) {
        $payload = new LogPayload($value . '-suffix');

        $this->sendRequest([$payload]);

        return $this;
    });

    $this->ray->myCustomFunction('my value');

    assertMatchesOsSafeSnapshot($this->client->sentPayloads());
});

it('when ray is not running the pause call will not blow up', function () {
    $this->ray->pause();

    expect($this->client->sentPayloads()[0]['payloads'][0]['type'])->toEqual('create_lock');
});

it('can send custom stuff to ray', function () {
    $this->ray->sendCustom('my custom content');
    assertMatchesOsSafeSnapshot($this->client->sentPayloads());

    $this->ray->sendCustom('my custom content', 'custom label');
    assertMatchesOsSafeSnapshot($this->client->sentPayloads());
});

it('can send data to ray and return the data', function () {
    $data = ['a' => 1, 'b' => 2];

    $result = $this->ray->pass($data);

    expect($result)->toEqual($data);

    $dumpedValue = getValueOfLastSentContent('values')[0];

    expect($dumpedValue)->toContain('<span class=sf-dump-key>a</span>');
    expect($dumpedValue)->toContain('<span class=sf-dump-key>b</span>');
});

it('can rewrite the file paths using the config values', function () {
    $payload = new CallerPayload([
        new Frame('/app/app/MyFile.php', 1, []),
        new Frame('/app/app/MyFile.php', 2, []),
    ]);

    $payload->remotePath = '/app';
    $payload->localPath = '/some/local/path';

    expect($payload->getContent()['frame']['file_name'])->toEqual('/some/local/path/app/MyFile.php');
});

it('only rewrites paths for matching remote paths', function () {
    $payload = new CallerPayload([
        new Frame('/app/files/MyFile.php', 1, []),
        new Frame('/app/files/MyFile.php', 2, []),
    ]);

    $payload->remotePath = '/files';
    $payload->localPath = '/some/local/path';

    expect($payload->getContent()['frame']['file_name'])->toEqual('/app/files/MyFile.php');

    $payload->remotePath = '/app';
    $payload->localPath = '/some/local/path';

    expect($payload->getContent()['frame']['file_name'])->toEqual('/some/local/path/files/MyFile.php');
});

it('returns itself and does not send anything when calling send without arguments', function () {
    $settings = SettingsFactory::createFromConfigFile();

    $this->ray = new Ray($settings, $this->client, 'fakeUuid');

    $result = $this->ray->send();

    expect($this->client->sentPayloads())->toHaveCount(0);
    expect($result)->toEqual($this->ray);
    expect(getValueOfLastSentContent('values'))->toBeNull();
});

it('can determine how many times a particular piece of code was called for a given name', function () {
    foreach (range(1, 2) as $i) {
        ray()->count('first');

        foreach (range(1, 2) as $j) {
            ray()->count('second');
            ray()->count('another');
        }

        ray()->count('another');
    }

    expect(Ray::$counters->get('first'))->toEqual(2);
    expect(Ray::$counters->get('second'))->toEqual(4);
    expect(Ray::$counters->get('another'))->toEqual(6);
});

it('can determine how many times a particular piece of code was called without a name', function () {
    foreach (range(1, 2) as $i) {
        ray()->count();

        foreach (range(1, 2) as $j) {
            ray()->count();
        }
    }

    expect($this->client->sentPayloads()[5]['payloads'][0]['content']['content'])->toEqual("Called 4 times.");
});

it('creates a Ray instance with default settings when create is called without arguments', function () {
    $ray = Ray::create(null, '1-2-3-4');

    expect($ray)->not->toBeNull();
    expect($ray->uuid)->toEqual('1-2-3-4');
    expect(SettingsFactory::createFromConfigFile())->toEqual($ray->settings);
});

it('merges default settings into existing settings', function () {
    $settings = SettingsFactory::createFromConfigFile();

    expect($settings->test)->toBeNull();
    expect($settings->port)->toEqual(23517);

    $settings->setDefaultSettings(['test' => 'testvalue']);

    expect($settings->test)->toEqual('testvalue');
    expect($settings->port)->toEqual(23517);
});

it('can send the php info payload', function () {
    $this->ray->phpinfo();

    $payloads = $this->client->sentPayloads();

    expect($payloads)->toHaveCount(1);

    expect($payloads[0]['payloads'][0]['type'])->toEqual('table');
});

it('the php info can report specific options', function () {
    $this->ray->phpinfo('default_mimetype');

    $payloads = $this->client->sentPayloads();

    expect($payloads)->toHaveCount(1);

    expect($payloads[0]['payloads'][0]['content']['values'])->toHaveKey('default_mimetype');
});

it('sends an image payload', function () {
    $this->ray->image('http://localhost/test.jpg');

    assertMatchesOsSafeSnapshot($this->client->sentPayloads());
});

it('sends a base64 encoded image payload', function () {
    $this->ray->image('dGVzdCBzdHJpbmc=');
    $this->ray->image('data:image/png;base64,dGVzdCBzdHJpbmc=');

    assertMatchesOsSafeSnapshot($this->client->sentPayloads());
});

it('sends a table payload', function () {
    $this->ray->table([
        'First' => 'First value',
        'Second' => 'Second value',
        'Third' => 'Third value',
    ]);

    assertMatchesOsSafeSnapshot($this->client->sentPayloads());
});

it('can send a carbon payload', function () {
    $frozenTime = TestTime::freeze('Y-m-d H:i:s', '2020-01-01 00:00:00');

    $carbon = new Carbon();

    ray()->carbon($carbon);

    expect($this->client->sentPayloads())->toHaveCount(1);

    $payload = $this->client->sentPayloads()[0];
    expect($payload['payloads'][0]['content']['formatted'])->toEqual($frozenTime);
    expect($payload['payloads'][0]['content']['timestamp'])->toEqual($frozenTime->getTimestamp());
    expect($payload['payloads'][0]['content']['timezone'])->toEqual(date_default_timezone_get());
});

it('sends an xml payload', function () {
    $this->ray->xml('<one><two>2</two></one>');

    assertMatchesOsSafeSnapshot($this->client->sentPayloads());
});

it('can send the raw values', function () {
    $this->ray->raw(new Carbon(), 'string', ['a' => 1]);

    $payloads = $this->client->sentPayloads();

    expect($payloads[0]['payloads'][0]['type'])->toEqual('log');
    expect($payloads[0]['payloads'][1]['type'])->toEqual('log');
    expect($payloads[0]['payloads'][2]['type'])->toEqual('log');
});

it('returns a ray instance when calling raw without arguments', function () {
    $instance = $this->ray->raw();

    expect($instance)->toBeInstanceOf(Ray::class);
    assertMatchesOsSafeSnapshot($this->client->sentPayloads());
});

it('will send a specialized payloads by default', function () {
    $this->ray->send(new Carbon(), 'string', ['a => 1']);

    $payloads = $this->client->sentPayloads();

    expect($payloads[0]['payloads'][0]['type'])->toEqual('carbon');
    expect($payloads[0]['payloads'][1]['type'])->toEqual('log');
    expect($payloads[0]['payloads'][2]['type'])->toEqual('log');
});

it('sends the hide application payload', function () {
    $this->ray->hideApp();

    assertMatchesOsSafeSnapshot($this->client->sentPayloads());
});

it('sends the show application payload', function () {
    $this->ray->showApp();

    assertMatchesOsSafeSnapshot($this->client->sentPayloads());
});

it('sends an html payload', function () {
    $this->ray->html('<strong>test</strong>');

    assertMatchesOsSafeSnapshot($this->client->sentPayloads());
});

it('sends an url payload', function () {
    $this->ray->url('https://spatie.be');

    assertMatchesOsSafeSnapshot($this->client->sentPayloads());
});

it('sends a correct url payload even without a protocol', function () {
    $this->ray->url('spatie.be');

    assertMatchesOsSafeSnapshot($this->client->sentPayloads());
});

it('sends an url with label payload', function () {
    $this->ray->url('https://spatie.be', 'Spatie');

    assertMatchesOsSafeSnapshot($this->client->sentPayloads());
});

it('sends a link payload', function () {
    $this->ray->link('https://spatie.be', 'Spatie');

    assertMatchesOsSafeSnapshot($this->client->sentPayloads());
});

it('sends a text payload', function () {
    $this->ray->text('text string');
    $this->ray->text('another   <strong>text</strong>' . PHP_EOL . '  string');

    $lastPayload = $this->client->sentPayloads()[1]['payloads'][0];

    expect($lastPayload['content']['content'])->toContain('&nbsp;&nbsp;&nbsp;&lt;strong&gt;');
    expect($lastPayload['content']['content'])->toContain('<br>');
    assertMatchesOsSafeSnapshot($this->client->sentPayloads());
});

it('sends a null payload', function () {
    $this->ray->send(null);

    assertMatchesOsSafeSnapshot($this->client->sentPayloads());
});

it('returns zero when accessing a missing counter', function () {
    expect(Ray::$counters->get('missing'))->toEqual(0);
    ray()->count('missing');
    expect(Ray::$counters->get('missing'))->toEqual(1);
});

it('sets the ray instance for a counter', function () {
    $ray1 = ray();
    $ray2 = ray();

    $ray1->count('first');

    $ray1::$counters->setRay('first', $ray1);

    expect($ray1::$counters->increment('first')[0])->toEqual($ray1);

    $ray1::$counters->setRay('first', $ray2);

    expect($ray1::$counters->increment('first')[0])->toEqual($ray2);
});

it('clears all counters', function () {
    Ray::$counters->clear();

    expect(Ray::$counters->get('first'))->toEqual(0);

    ray()->count('first');

    expect(Ray::$counters->get('first'))->toEqual(1);

    ray()->clearCounters();

    expect(Ray::$counters->get('first'))->toEqual(0);
});

it('returns the value of a named counter', function () {
    expect(ray()->counterValue('first'))->toEqual(0);

    ray()->count('first');

    expect(ray()->counterValue('first'))->toEqual(1);

    ray()->count('first');

    expect(ray()->counterValue('first'))->toEqual(2);
});

it('will respect the raw values config setting', function () {
    $this->settings->always_send_raw_values = true;
    $this->ray->send(new Carbon());
    expect($this->client->sentPayloads()[0]['payloads'][0]['type'])->toEqual('log');
});

it('can be disabled', function () {
    $this->ray->send('test payload 1');
    $this->ray->disable();
    $this->ray->send('test payload 2');

    expect($this->client->sentPayloads())->toHaveCount(1);
});

it('can be reenabled after being disabled', function () {
    $this->ray->enable();
    $this->ray->send('test payload 1');
    $this->ray->disable();
    $this->ray->send('test payload 2');
    $this->ray->enable();
    $this->ray->send('test payload 3');

    expect($this->client->sentPayloads())->toHaveCount(2);
});

it('returns the correct enabled state', function () {
    Ray::$enabled = true;
    expect($this->ray->enabled())->toBeTrue();
    expect($this->ray->disabled())->toBeFalse();

    Ray::$enabled = false;
    expect($this->ray->enabled())->toBeFalse();
    expect($this->ray->disabled())->toBeTrue();
});

it('defaults to enabled state', function () {
    expect($this->ray->enabled())->toBeTrue();
});

it('checks the availablity of the Ray server', function () {
    $this->client->changePortAndReturnOriginal(34993);

    expect($this->client->performAvailabilityCheck())->toBeFalse();
});

it('respects the enabled property', function () {
    $ray = getNewRay()->disable();

    expect($ray->enabled())->toBeFalse();
    expect(getNewRay()->enabled())->toBeFalse();

    getNewRay()->enable();

    expect($ray->enabled())->toBeTrue();
    expect(getNewRay()->enabled())->toBeTrue();
});

it('respects the enabled property when sending payloads', function () {
    $ray = getNewRay()->disable();
    $ray->send('test message 1');
    expect($this->client->sentPayloads())->toHaveCount(0);

    $ray->enable();
    $ray->send('test message 2');
    expect($this->client->sentPayloads())->toHaveCount(1);

    $ray->disable();
    $ray->send('test message 3');
    expect($this->client->sentPayloads())->toHaveCount(1);
});

it('can quickly send a request', function () {
    $before = microtime(true);

    $payloads = PayloadFactory::createForValues([
        'value 1' => 'nested',
        'value 2',
    ]);

    $this->ray->sendRequest($payloads);

    $after = microtime(true);

    expect($after - $before)->toBeLessThan(0.005);
});

it('can quickly call the ray helper', function () {
    $before = microtime(true);

    ray('a');

    $after = microtime(true);

    expect($after - $before)->toBeLessThan(0.05);
});

it('can quickly call send function', function () {
    $before = microtime(true);

    $this->ray->send('a');

    $after = microtime(true);

    expect($after - $before)->toBeLessThan(0.005);
});

it('can limit the number of payloads sent from a loop', function () {
    $limit = 5;

    for ($i = 0; $i < 10; $i++) {
        getNewRay()->limit($limit)->send("limited loop iteration $i");
    }

    expect($this->client->sentPayloads())->toHaveCount($limit);
});

it('only limits the number of payloads sent from the line that calls limit', function () {
    $limit = 5;
    $iterations = 10;

    for ($i = 0; $i < $iterations; $i++) {
        getNewRay()->limit($limit)->send("limited loop iteration $i");
        getNewRay()->send("unlimited loop iteration $i");
    }

    expect($this->client->sentPayloads())->toHaveCount($limit + $iterations);
});

it('can handle multiple consecutive calls to limit', function () {
    $limit = 2;

    for ($i = 0; $i < 10; $i++) {
        getNewRay()->limit($limit)
        ->send("limited loop A iteration $i");

        getNewRay()->limit($limit)
        ->send("limited loop B iteration $i");
    }

    assertMatchesOsSafeSnapshot($this->client->sentPayloads());
});

it('can conditionally send payloads using if with a truthy conditional and without a callback', function () {
    for ($i = 0; $i < 10; $i++) {
        $this->ray->if($i < 5)->text("value: {$i}");
    }

    expect($this->client->sentPayloads())->toHaveCount(5);
});

it('can conditionally send payloads using if with a callable conditional param', function () {
    for ($i = 0; $i < 10; $i++) {
        $this->ray->if(function () use ($i) {
            return $i < 5;
        })->text("value: {$i}");
    }

    expect($this->client->sentPayloads())->toHaveCount(5);
});

it('can conditionally send payloads using if with a callback', function () {
    $this->ray->if(true, function ($ray) {
        $ray->text('one');
    });

    $this->ray->if(false, function ($ray) {
        $ray->text('two');
    });

    expect($this->client->sentPayloads())->toHaveCount(1);
});

it('can chain method calls when using if with a callback and a false condition', function () {
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

    assertMatchesOsSafeSnapshot($this->client->sentPayloads());
});

it('can chain multiple when calls with callbacks together', function () {
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

    assertMatchesOsSafeSnapshot($this->client->sentPayloads());
});

it('cannot call when rate limit max has reached', function () {
    Ray::rateLimiter()
        ->clear()
        ->max(1);

    ray('this can pass');
    ray('this cannot pass, but triggers a warning call');
    ray('this cannot pass');

    expect($this->client->sentPayloads())->toHaveCount(2);

    expect($this->client->sentPayloads()[1]['payloads'][0]['content']['content'])->toBe('Rate limit has been reached...');
});

it('sends a payload once when called with arguments', function () {
    for ($i = 0; $i < 5; $i++) {
        getNewRay()->once($i);
    }

    expect($this->client->sentPayloads())->toHaveCount(1);
    expect($this->client->sentPayloads()[0]['payloads'][0]['content']['values'])->toEqual([0]);
});

it('sends a payload once when called without arguments', function () {
    for ($i = 0; $i < 5; $i++) {
        getNewRay()->once()->text($i);
    }

    expect($this->client->sentPayloads())->toHaveCount(1);
    expect($this->client->sentPayloads()[0]['payloads'][0]['content']['content'])->toEqual(0);
});

it('sends a payload once while allowing calls to limit', function () {
    for ($i = 0; $i < 5; $i++) {
        $this->ray->once($i);
        getNewRay()->limit(5)->text($i);
    }

    expect($this->client->sentPayloads())->toHaveCount(6);
});

it('does nothing if no exceptions are thrown from a callable while using catch with a callback', function () {
    $ray = getNewRay();

    $ray->send(function () use ($ray) {
        return $ray->text('hello world');
    })->catch(function ($exception, $ray) {
        $ray->text($exception->getMessage());
    });

    expect($this->client->sentPayloads())->toHaveCount(1);
    assertMatchesOsSafeSnapshot($this->client->sentPayloads());
});

it('handles exceptions using catch with a callback and classname parameter', function () {
    getNewRay()->send(function () {
        throw new Exception('test');
    })->catch(Exception::class);

    // 2 payloads for exceptions
    expect($this->client->sentPayloads())->toHaveCount(2);
});

it('handles exceptions using and catch without a callback', function () {
    getNewRay()->send(function () {
        throw new Exception('test');
    })->catch();

    // 2 payloads are sent when ray->exception() is called
    expect($this->client->sentPayloads())->toHaveCount(2);
});

it('handles exceptions using catch with a callback', function () {
    getNewRay()->send(function () {
        throw new Exception('test');
    })->catch(function ($e, $ray) {
        return $ray->text($e->getMessage());
    });

    expect($this->client->sentPayloads())->toHaveCount(1);
    assertMatchesOsSafeSnapshot($this->client->sentPayloads());
});

it('handles exceptions using catch with a callback and a typed parameter', function () {
    getNewRay()->send(function () {
        throw new Exception('test');
    })->catch(function (Exception $e, $ray) {
        return $ray->text($e->getMessage());
    });

    expect($this->client->sentPayloads())->toHaveCount(1);
    assertMatchesOsSafeSnapshot($this->client->sentPayloads());
});

it('handles exceptions using catch with an array of callbacks with typed parameters', function () {
    getNewRay()->send(function () {
        throw new InvalidArgumentException('test');
    })->catch([
        function (BadMethodCallException $e, $ray) {
            return $ray->text(get_class($e));
        },
        function (InvalidArgumentException $e, $ray) {
            $ray->text(get_class($e));
        },
    ]);

    expect($this->client->sentPayloads())->toHaveCount(1);
    assertMatchesOsSafeSnapshot($this->client->sentPayloads());
});

it('handles exceptions using catch with an array of exception classnames', function () {
    getNewRay()->send(function () {
        throw new InvalidArgumentException('test');
    })->catch([
        BadMethodCallException::class,
        InvalidArgumentException::class,
    ]);

    expect($this->client->sentPayloads())->toHaveCount(2);
});

it('does not handle exceptions using catch with an array of exception classnames that do not match the exception', function () {
    getNewRay()->send(function () {
        throw new InvalidArgumentException('test');
    })->catch([
        BadMethodCallException::class,
        BadFunctionCallException::class,
    ]);

    expect($this->client->sentPayloads())->toHaveCount(0);
})->throws(InvalidArgumentException::class);

it('does not handle exceptions using catch with a callback and a typed parameter different than the exception class', function () {
    getNewRay()->send(function () {
        throw new Exception('test');
    })->catch(function (InvalidArgumentException $e, $ray) {
        return $ray->text($e->getMessage());
    });

    expect($this->client->sentPayloads())->toHaveCount(0);
})->throws(Exception::class);

it('allows chaining additional methods after handling an exception', function () {
    getNewRay()->send(function ($ray) {
        $ray->text('hello world');

        throw new Exception('test');
    })->catch()->blue()->small();

    expect($this->client->sentPayloads())->toHaveCount(5);
});

it('throws exceptions when calling throwExceptions', function () {
    getNewRay()->send(function ($ray) {
        $ray->text('hello world');

        throw new Exception('test');
    })->throwExceptions();
})->throws(Exception::class);

it('can dump a string with a global function name', function () {
    $this->ray->send('array_map');

    assertMatchesOsSafeSnapshot($this->client->sentPayloads());
})->skip(PHP_MAJOR_VERSION < 8, 'test requires PHP 8+');

it('can send a separator', function () {
    $this->ray->send('separator');

    assertMatchesOsSafeSnapshot($this->client->sentPayloads());
});

it('can set the project name', function () {
    ray()->project('my project');

    expect(Ray::$projectName)->toEqual('my project');

    ray('send request');

    expect($this->client->sentRequests()[0]['meta']['project_name'])->toEqual('my project');
});

it('can dump long integers as string', function () {
    $this->ray->send(11111111111111110);
    $this->ray->send(11111111111111111);

    expect($this->client->sentRequests()[0]['payloads'][0]['content']['values'])->toBe([11111111111111110]);
    expect($this->client->sentRequests()[1]['payloads'][0]['content']['values'])->toBe(["11111111111111111"]);
});

it('can invade private properties', function () {
    $this->ray->invade(new PrivateClass())->privateProperty->red();

    $sentRequests = $this->client->sentRequests();
    expect($sentRequests)->toHaveCount(2);

    expect($sentRequests[0]['payloads'][0]['content']['values'])->toBe(['this is the value of the private property']);
});

it('can invade private methods', function () {
    $this->ray->invade(new PrivateClass())->privateMethod()->red();

    $sentRequests = $this->client->sentRequests();
    expect($sentRequests)->toHaveCount(2);

    expect($sentRequests[0]['payloads'][0]['content']['values'])->toBe(['this is the result of the private method']);
});
